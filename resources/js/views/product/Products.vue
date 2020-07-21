<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Productos</h3>
            <div class="btn-toolbar">
              <router-link class="btn btn-info" to="/products/create">
                <feather type="plus"/>
                Nuevo
              </router-link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <th>Nombre</th>
              <th>Categoria</th>
              <th>Sub Categoria</th>
              <th>P. de Venta</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in products" :key='item.id'>
                <td>{{ item.name }}</td>
                <td>{{ item.category.name }}</td>
                <td>{{ item.sub_category.name }}</td>
                <td>S/ {{ item.sale_price.toFixed(2) }}</td>
                <td>
                  <router-link :to="{ path: `/products/${item.id}/edit` }">
                    <feather type="edit"/>
                  </router-link>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      products: [],
    }
  },
  methods: {
    fetchData() {
      axios.get('products').then(res => {
        console.log(res.data);
        this.products = res.data.products;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>