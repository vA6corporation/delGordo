<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Editar Usuario</h3>
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombres</label>
              <input type="text" v-model="user.name" class="form-control" placeholder="Nombres" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Email</label>
              <input type="email" v-model="user.email" class="form-control" placeholder="Email" required>
            </div>
          </div>
          <!-- <div class="row form-group">
            <div class="col">
              <label for="">Contraseña</label>
              <input type="password" v-model="user.password" class="form-control" placeholder="Contraseña" required>
            </div>
          </div> -->
          <!-- <div class="row form-group">
            <div class="col">
              <label for="">Confirme Contraseña</label>
              <input type="password" v-model="user.password_confirmation" class="form-control" placeholder="Contraseña" required>
            </div>
          </div> -->
          <div class="row form-group">
            <div class="col">
              <label for="">Mostrar todos los delivery</label>
              <toggle-button v-model="user.is_admin"/>
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
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      user: {},
    }
  },
  methods: {
    fetchData() {
      let userId = this.$route.params.userId;
      axios.get(`users/${userId}`).then(res => {
        console.log(res);
        let user = res.data.user;
        this.user = user;
      });
    },
    submit() {
      if (this.user.password != this.user.password_confirmation) {
        return this.$snotify.error('Las contraseñas no coinciden');
      }
      axios.put(`users/${this.user.id}`, { user: this.user }).then(res => {
        console.log(res.data);
        this.$snotify.success('Se han guardado los cambios');
        // this.$router.replace('/users');
      }).catch(err => {
        // this.$snotify.error(err.response.message);
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>