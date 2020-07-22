<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Clientes</h3>
            <div class="btn-toolbar">
              <!-- <router-link class="btn btn-info" to="/customers/create">
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
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in customers" :key='item.id'>
                <td>{{ item.name }}</td>
                <!-- <td>{{ item.category.name }}</td> -->
                <!-- <td>{{ item.sub_category.name }}</td> -->
                <!-- <td>S/ {{ item.sale_price.toFixed(2) }}</td> -->
                <td>
                  <router-link :to="{ path: `/customers/${item.id}/edit` }">
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
      customers: [],
    }
  },
  methods: {
    fetchData() {
      axios.get('customers').then(res => {
        console.log(res.data);
        this.customers = res.data.customers;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>