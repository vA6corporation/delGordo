<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6 mx-auto">
      <div class="card text-center">
        <div class="card-header">
          <h3 class="card-title">Seleccione una sucursal</h3>
        </div>
        <ul class="list-group list-group-flush">
          <a href="#" class="list-group-item list-group-item-action" v-for="item in offices" :key="item.id" @click.prevent="setOffice(item)">
            {{ item.name }}
          </a>
        </ul>
        <!-- <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombre</label>
              <input type="text" v-model="office.name" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="submit" class="btn btn-info float-right">
                <feather type="save"/>
                Guardar
              </button>
            </div>
          </div>
        </div> -->
      </div>
    </div>  
  </form>
</template>

<script>
import { mapActions } from 'vuex'

export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      offices: [],
    }
  },
  methods: {
    ...mapActions({
      fetchOffice: 'system/fetchOffice',
    }),
    fetchData() {
      axios.get('offices').then(res => {
        console.log(res);
        this.offices = res.data.offices;
      });
    },
    setOffice(office) {
      axios.get(`${office.id}/setOffice`).then(res => {
        console.log(res);
        this.fetchOffice(office);
        this.$router.replace('/dashboard');
      });
      // axios.post('offices', { office: this.office }).then(res => {
      //   console.log(res.data);
      //   this.$snotify.success('Oficina registrado correctamente');
      //   this.$router.replace('/offices');
      // }).catch(err => {
      //   console.log(err.response);
      // });
    }
  }
}
</script>