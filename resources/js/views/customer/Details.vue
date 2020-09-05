<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Detalles del Cliente</h3>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush lead">
            <li class="list-group-item d-flex justify-content-between">
              <span>Nombres:</span>
              <span>{{ customer.name }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>DNI:</span>
              <span>{{ customer.document }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Direccion:</span>
              <span>{{ customer.address }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Referencia:</span>
              <span>{{ customer.reference }}</span>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Celular:</span>
              <span>{{ customer.mobile }}</span>
            </li>
          </ul>
          <!-- <div class="row form-group">
            <div class="col">
              <label for="">Nombres</label>
              <input type="text" v-model="customer.name" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">DNI</label>
              <input type="text" v-model="customer.document" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Direccion</label>
              <input type="text" v-model="customer.address" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Referencia</label>
              <input type="text" v-model="customer.reference" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row form-group">
            <div class="col">
              <label for="">Celular</label>
              <input type="text" v-model="customer.mobile" class="form-control" placeholder="Nombre" required>
            </div>
          </div>
          <div class="row">
            <div class="col">
              <button type="submit" class="btn btn-info float-right">
                <feather type="save"/>
                Guardar
              </button>
            </div>
          </div> -->
        </div>
      </div>
    </div>
  </form>
</template>

<script>
export default {
  mounted() {
    this.priviewImage = this.$el.querySelector('#priviewImage');
    this.fetchData();
  },
  data() {
    return {
      customer: {
        name: '',
      },
    }
  },
  methods: {
    fetchData() {
      var customerId = this.$route.params.customerId;
      axios.get(`customers/${customerId}`).then(res => {
        console.log(res);
        this.customer = res.data.customer;
      });
    },
    async submit() {
      axios.put(`customers/${this.customer.id}`, { customer: this.customer }).then(res => {
        console.log(res.data);
        this.$snotify.success('Se han guardado los cambios');
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>

</style>