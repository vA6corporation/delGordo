<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Nuevo Delivery</h3>
        </div>
        <div class="card-body">
          <div class="row form-group">
            <div class="col">
              <label for="">Distrito</label>
              <input type="text" v-model="delivery.name" class="form-control" placeholder="Distrito" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Precio envio</label>
              <input type="number" step="any" min="0" v-model="delivery.price" class="form-control" placeholder="Precio de envio" required>
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
      <!-- <div class="card">
        <input id="inputFile" @change="setImage($event.target.files[0])" type="file" hidden>
        <div class="card-header">
          <h3>Imagen del deliveryo</h3>
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
export default {
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      delivery: {},
    }
  },
  methods: {
    fetchData() {
      var deliveryId = this.$route.params.deliveryId;
      axios.get(`deliveries/${deliveryId}`).then(res => {
        console.log(res);
        this.delivery = res.data.delivery;
      });
    },
    submit() {
      axios.put(`deliveries/${this.delivery.id}`, { delivery: this.delivery }).then(res => {
        console.log(res.data);
        this.$snotify.success('Se han guardado los cambios');
        // this.$router.replace('/deliveries')
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>