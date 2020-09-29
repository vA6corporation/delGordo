<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Oficinas</h3>
            <div class="btn-toolbar">
              <router-link class="btn btn-info" to="/offices/create">
                <feather type="plus"/>
                Nuevo
              </router-link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <caption>
              <page-navigation v-model="page" :pages="pages" :count="count" :items="offices.length" @confirm="fetchData"/>
            </caption>
            <thead>
              <th>Nombre</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in offices" :key='item.id'>
                <td>{{ item.name }}</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/offices/${item.id}/edit` }" class="dropdown-item">Editar</router-link>
                      <!-- <router-link :to="{ path: `/offices/${item.id}/credentials` }" class="dropdown-item">Permisos</router-link> -->
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
      offices: [],
      page: 1,
      pages: null,
      count: null,
    }
  },
  methods: {
    fetchData() {
      // var params = { page: this.page };
      axios.get('offices').then(res => {
        console.log(res.data);
        this.offices = res.data.offices;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>