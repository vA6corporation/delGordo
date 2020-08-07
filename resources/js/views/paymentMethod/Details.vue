<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalles de Venta</h3>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between">
              <span>Producto</span>
              <span>Codigo</span>
              <span>Peso</span>
          </li>
          <li class="list-group-item d-flex justify-content-between" v-for="(item, index) in sale.items" :key="index">
            <!-- <span>{{ formatDate(item.created_at) }}</span> -->
            {{ item.product.name }}
            <span>{{ item.codigo }}</span>
            <span>{{ item.weight }} Kg</span>
          </li>
        </ul>
      </div>
    </div>
    <div class="col">
      <div class="form-group">
        <div class="card">
          <div class="card-header">
            <h3>Datos del Cliente</h3>
          </div>
          <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between">
              <span>Nombres:</span>
              <span>{{ sale.customer.name }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Direccion:</span>
              <span>{{ sale.customer.address }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Celuar:</span>
              <span>{{ sale.customer.mobile }}</span>
            </li>
            <!-- <li class="list-group-item d-flex justify-content-between">
              <span>Sub Categoria:</span>
              <span>{{ sale.sub_category.name }}</span>
            </li> -->
            <!-- <li class="list-group-item d-flex justify-content-between">
              <span>Peso Total:</span>
              <span>{{ sale.inventory.map(e => e.weight).reduce((a, b) => a + b, 0) }} Kg</span>
            </li> -->
          </ul>
        </div>
      </div>
      <div class="card">
        <div class="card-header">
          <h3>Datos de la Venta</h3>
        </div>
        <ul class="list-group">
          <li class="list-group-item d-flex justify-content-between">
            <span>Fecha:</span>
            <span>{{ formatDate(sale.created_at) }}</span>
          </li>
          <li class="list-group-item d-flex justify-content-between">
            <span>Total de Venta:</span>
            <span>{{ sale.items.map(e => e.weight).reduce((a, b) => a + b, 0) }}</span>
          </li>
          <!-- <li class="list-group-item d-flex justify-content-between">
            <span>Sub Categoria:</span>
            <span>{{ sale.sub_category.name }}</span>
          </li> -->
          <!-- <li class="list-group-item d-flex justify-content-between">
            <span>Peso Total:</span>
            <span>{{ sale.inventory.map(e => e.weight).reduce((a, b) => a + b, 0) }} Kg</span>
          </li> -->
        </ul>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  mounted() {
    this.inventories.push(Object.assign({}, this.inventory));
    this.fetchData();
  },
  data() {
    return {
      sale: {
        customer: {},
        items: [],
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
      console.log('chulapi');
      this.inventories.push(Object.assign({}, this.inventory));
    },
    async fetchData() {
      // var saleId = this.$route.params.saleId;
      var saleId = this.$route.params.saleId;
      await axios.get(`sales/${saleId}`).then(res => {
        console.log(res);
        this.sale = res.data.sale;
      }).catch(err => {
        console.log(err.response);
      });
      // axios.get(`sales/${saleId}`).then(res => {
      //   console.log(res);
      //   this.sale = res.data.sale;
      // }).catch(err => {
      //   console.log(err.response);
      // });
    },
    submit() {
      if (!this.inventories.length) {
        return this.$snotify.error('Es necesario al menos 1 ingreso');
      }
      this.inventories.forEach(item => {
        item.sale_id = this.sale.id;
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