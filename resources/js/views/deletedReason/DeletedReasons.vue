<template>
  <div class="row">
    <div class="col">
      <div class="card">
        <div class="card-header">
          <div class="d-flex justify-content-between">
            <h3 class="card-title mb-0">Motivos de anulacion</h3>
            <div class="btn-toolbar">
              <router-link class="btn btn-info" to="/deletedReasons/create">
                <feather type="plus"/>
                Nuevo
              </router-link>
            </div>
          </div>
        </div>
        <div class="card-body">
          <table class="table">
            <caption>
              <page-navigation v-model="page" :pages="pages" :count="count" :items="deletedReasons.length" @confirm="fetchData"/>
            </caption>
            <thead>
              <th>Metodo</th>
              <th>Opciones</th>
            </thead>
            <tbody>
              <tr v-for="item in deletedReasons" :key='item.id'>
                <td>{{ item.name }}</td>
                <td>
                  <div class="btn-toolbar">
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                      <feather type="more-vertical"/>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right">
                      <router-link :to="{ path: `/deletedReasons/${item.id}/edit` }" class="dropdown-item">Editar</router-link>
                      <a href="#" @click.prevent="deleteDeletedReason(item.id)" class="dropdown-item">Eliminar</a>
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
      deletedReasons: [],
      page: 1,
      pages: null,
      count: null,
    }
  },
  methods: {
    deleteDeletedReason(id) {
      var ok = confirm('Esta seguro de eliminar?...');
      if (ok) {
        axios.delete(`deletedReasons/${id}`).then(res => {
          console.log(res);
          this.fetchData();
        });
      }
    },
    fetchData() {
      var params = { page: this.page };
      axios.get('deletedReasons', { params }).then(res => {
        console.log(res.data);
        this.deletedReasons = res.data.deletedReasons;
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