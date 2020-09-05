<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nuevo Motivo de Anulacion</h3>
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombre</label>
              <input type="text" v-model="deletedReason.name" class="form-control" placeholder="Nombre" required>
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
    <div class="col">
    </div>
  </form>
</template>

<script>
import Compressor from 'compressorjs'

export default {
  data() {
    return {
      deletedReason: {},
    }
  },
  methods: {
    submit() {
      axios.post('deletedReasons', { deletedReason: this.deletedReason }).then(res => {
        console.log(res.data);
        this.$snotify.success('Motivo de baja registrado correctamente');
        this.$router.replace('/deletedReasons')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>