<template>
  <form @submit.prevent="submit" class="row">
    <move-modal :inventory="inventory" @confirm="fetchData"></move-modal>
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalles de Inventario</h3>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <th>Peso</th>
              <th>Codigo</th>
              <th>Vendido</th>
              <th>Entrega</th>
              <th>F. Ingreso</th>
              <th></th>
            </thead>
            <tbody>
              <tr v-for='(item, index) in product.inventory_all' :key="index">
                <td>{{ item.weight.toFixed(3) }}</td>
                <td>{{ item.codigo }}</td>
                <td v-if="item.sale_id">Si</td>
                <td v-else>No</td>
                <td v-if="item.delivered_date">Si</td>
                <td v-else>No</td>
                <td>{{ formatDate(item.created_at) }}</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <a href="#" class="dropdown-item" data-toggle="modal" data-target="#moveModal" @click="inventory = item">Mover de Sucursal</a>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="col">
      <div class="card">
        <div class="card-header">
          <h3>Datos del Producto</h3>
        </div>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between">
            <span>Nombre:</span>
            <span>{{ product.name }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Categoria:</span>
            <span>{{ product.category.name }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Sub Categoria:</span>
            <span>{{ product.sub_category.name }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Peso Total:</span>
            <span>{{ product.inventory.map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(3) }} Kg</span>
          </li>
        </ul>
      </div>
    </div>
  </form>
</template>

<script>
import MoveModal from '@/components/MoveModal'

export default {
  components: {
    MoveModal
  },
  mounted() {
    this.inventories.push(Object.assign({}, this.inventory));
    this.fetchData();
  },
  data() {
    return {
      product: {
        category: {},
        sub_category: {},
        inventory: [],
      },
      inventory: {
        weight: null,
        quantity: 1
      },
      inventories: [],
    }
  },
  methods: {
    addInventory() {
      this.inventories.push(Object.assign({}, this.inventory));
    },
    fetchData() {
      var productId = this.$route.params.productId;
      axios.get(`products/${productId}/inventoryAll`).then(res => {
        console.log(res);
        this.product = res.data.product;
      }).catch(err => {
        console.log(err.response);
      });
    },
    submit() {
      if (!this.inventories.length) {
        return this.$snotify.error('Es necesario al menos 1 ingreso');
      }
      this.inventories.forEach(item => {
        item.product_id = this.product.id;
      });
      axios.post('inventories', { inventories: this.inventories }).then(res => {
        console.log(res.data);
        this.$snotify.success('Inventario registrado correctamente');
        this.$router.replace('/inventories')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>