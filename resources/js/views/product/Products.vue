<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title">Productos</h3>
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
            </thead>
            <tbody>
              <tr v-for="item in products" :key='item.id'>
                <td>{{ item }}</td>
                <td></td>
                <td></td>
                <td></td>
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
      }).catch(res => {
        console.log('pichula');
        console.log(res);
      });
    }
  }
}
</script>

<style>

</style>