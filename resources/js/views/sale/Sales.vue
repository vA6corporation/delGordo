<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Ventas</h3>
            <div class="btn-toolbar">
              <!-- <router-link class="btn btn-info" to="/sales/create">
                <feather type="plus"/>
                Nuevo
              </router-link> -->
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <thead>
              <th>F/H de venta</th>
              <th>Cliente</th>
              <!-- <th>Sub Categoria</th> -->
              <th>T. de Venta</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in sales" :key='item.id'>
                <td>{{ formatDate(item.created_at) }} / {{ formatTime(item.created_at) }}</td>
                <td>{{ item.customer.name }}</td>
                <td>S/ {{ item.items.map(e => e.sale_price).reduce((a, b) => a + b, 0) }}</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/sales/${item.id}/details` }" class="dropdown-item">Detalles</router-link>
                      <!-- <router-link :to="{ path: `/inventories/${item.id}/details` }" class="dropdown-item">Ver Paketes</router-link> -->
                      <!-- <button class="dropdown-item" type="button">Another action</button> -->
                      <!-- <button class="dropdown-item" type="button">Retirar</button> -->
                    </div>
                  </div>
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
      sales: [],
    }
  },
  methods: {
    fetchData() {
      axios.get('sales').then(res => {
        console.log(res.data);
        this.sales = res.data.sales;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>