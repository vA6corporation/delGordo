<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Inventario</h3>
            <div class="btn-toolbar">
              <!-- <router-link class="btn btn-info" to="/products/create">
                <feather type="plus"/>
                Nuevo
              </router-link> -->
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <th>Nombre</th>
              <th>Paquetes</th>
              <th>T. Kilos</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in products" :key='item.id'>
                <td>{{ item.name }}</td>
                <td>{{ item.packages }} Pak</td>
                <td>{{ item.weights }} Kg</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/inventories/${item.id}/create` }" class="dropdown-item">Ingresar</router-link>
                      <!-- <button class="dropdown-item" type="button">Another action</button> -->
                      <!-- <button class="dropdown-item" type="button">Retirar</button> -->
                    </div>
                  </div>
                </td>
                <!-- <td>{{ item.category }}</td>
                <td>{{ item.sub_category }}</td>
                <td>{{ item.sale_price }}</td> -->
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
      axios.get('products/withInventory').then(res => {
        console.log(res);
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