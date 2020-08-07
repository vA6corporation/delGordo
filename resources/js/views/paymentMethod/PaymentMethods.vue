<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Metodos de Pago</h3>
            <div class="btn-toolbar">
              <router-link class="btn btn-info" to="/paymentMethods/create">
                <feather type="plus"/>
                Nuevo
              </router-link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <caption>
              <page-navigation v-model="page" :pages="pages" :count="count" :items="paymentMethods.length" @confirm="fetchData"/>
            </caption>
            <thead>
              <th>Metodo</th>
              <!-- <th>Cliente</th> -->
              <!-- <th>Codigo</th> -->
              <!-- <th>Sub Categoria</th> -->
              <!-- <th>T. de Venta</th> -->
              <!-- <th>Entrega</th> -->
              <!-- <th>Precio</th> -->
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in paymentMethods" :key='item.id'>
                <td>{{ item.name }}</td>
                <!-- <td>S/ {{ item.price.toFixed(2) }}</td> -->
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/paymentMethods/${item.id}/edit` }" class="dropdown-item">Editar</router-link>
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
      paymentMethods: [],
      page: 1,
      pages: null,
      count: null,
    }
  },
  methods: {
    fetchData() {
      var params = { page: this.page };
      axios.get('paymentMethods', { params }).then(res => {
        console.log(res.data);
        this.paymentMethods = res.data.paymentMethods;
        this.pages = res.data.pages;
        this.count = res.data.count;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>