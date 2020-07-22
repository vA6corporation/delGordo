<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalles de Inventario</h3>
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item d-flex justify-content-between">
              <span>Peso</span>
              <span>F. Ingreso</span>
          </li>
          <li class="list-group-item d-flex justify-content-between" v-for="(item, index) in product.inventory" :key="index">
            <span>{{ item.weight }} Kg</span>
            <span>{{ formatDate(item.created_at) }}</span>
          </li>
        </ul>
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
            <span>{{ product.inventory.map(e => e.weight).reduce((a, b) => a + b, 0) }} Kg</span>
          </li>
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
      console.log('chulapi');
      this.inventories.push(Object.assign({}, this.inventory));
    },
    fetchData() {
      var productId = this.$route.params.productId;
      axios.get(`products/${productId}`).then(res => {
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