<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nuevo Repartidor</h3>
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombres</label>
              <input type="text" v-model="deliveryman.name" class="form-control" placeholder="Nombres" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">DNI</label>
              <input type="text" v-model="deliveryman.document" class="form-control" placeholder="DNI" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Celular</label>
              <input type="text" v-model="deliveryman.phone" class="form-control" placeholder="Celular" required>
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
import Compressor from 'compressorjs'

export default {
  data() {
    return {
      deliveryman: {},
    }
  },
  methods: {
    submit() {
      axios.post('deliveryman', { deliveryman: this.deliveryman }).then(res => {
        console.log(res.data);
        this.$snotify.success('repartidor registrado correctamente');
        this.$router.replace('/deliveryman')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>