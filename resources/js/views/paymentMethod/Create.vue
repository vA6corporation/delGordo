<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nuevo Metodo de Pago</h3>
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Nombre</label>
              <input type="text" v-model="paymentMethod.name" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <!-- <div class="row form-group">
            <div class="col">
              <label for="">Precio envio</label>
              <input type="number" step="any" min="0" v-model="paymentMethod.price" class="form-control" placeholder="Precio de envio" required>
            </div>
          </div> -->
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
      <!-- <div class="card">
        <input id="inputFile" @change="setImage($event.target.files[0])" type="file" hidden>
        <div class="card-header">
          <h3>Imagen del paymentMethodo</h3>
        </div>
        <label for="inputFile" class="card-body text-white text-center border" style="cursor:pointer">
          <feather class="feather-xxl" type="image" v-if='!src'/>
          <img id="priviewImage" class="img-fluid" :src="src" alt="imagen" v-else>
        </label>
      </div> -->
    </div>
  </form>
</template>

<script>
import Compressor from 'compressorjs'

export default {
  data() {
    return {
      paymentMethod: {},
    }
  },
  methods: {
    submit() {
      axios.post('paymentMethods', { paymentMethod: this.paymentMethod }).then(res => {
        console.log(res.data);
        this.$snotify.success('metodo de pago registrado correctamente');
        this.$router.replace('/paymentMethods')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>