<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nueva Oficina</h3>
        </div>
        <div class="card-body">
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
        </div>
      </div>
    </div>  
  </form>
</template>

<script>
export default {
  data() {
    return {
      office: {},
    }
  },
  methods: {
    submit() {
      axios.post('offices', { office: this.office }).then(res => {
        console.log(res.data);
        this.$snotify.success('Oficina registrado correctamente');
        this.$router.replace('/offices');
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>