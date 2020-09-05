<template>
  <form @submit.prevent="submit" class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-header">
          <h3 class="card-title">Permisos: {{ user.name }}</h3>
        </div>
        <div class="card-body">
          <ul class="list-group list-group-flush">
            <li class="list-group-item d-flex justify-content-between">
              <span>Dashboard</span>
              <toggle-button v-model="credential.dashboard"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Punto de venta</span>
              <toggle-button v-model="credential.pos"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Punto de venta(Admin)</span>
              <toggle-button v-model="credential.posAdmin"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Productos</span>
              <toggle-button v-model="credential.products"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Categorias</span>
              <toggle-button v-model="credential.categories"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Sub Categorias</span>
              <toggle-button v-model="credential.subCategories"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Inventarios</span>
              <toggle-button v-model="credential.inventories"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Inventarios (Admin)</span>
              <toggle-button v-model="credential.inventoriesAdmin"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Clientes</span>
              <toggle-button v-model="credential.customers"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Ventas</span>
              <toggle-button v-model="credential.sales"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Ventas(Admin)</span>
              <toggle-button v-model="credential.salesAdmin"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Despacho</span>
              <toggle-button v-model="credential.dispatchedsSale"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Delivery</span>
              <toggle-button v-model="credential.deliveriesSale"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Usuarios</span>
              <toggle-button v-model="credential.users"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Lugares de envio</span>
              <toggle-button v-model="credential.deliveries"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Metodos de pago</span>
              <toggle-button v-model="credential.paymentMethods"></toggle-button>
            </li>
            <li class="list-group-item d-flex justify-content-between">
              <span>Motivos de anulacion</span>
              <toggle-button v-model="credential.deletedReasons"></toggle-button>
            </li>
          </ul>
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
      credential: {},
      user: {},
    }
  },
  methods: {
    fetchData() {
      var userId = this.$route.params.userId;
      axios.get(`credentials/${userId}`).then(res => {
        console.log(res);
        this.credential = res.data.credential || {};
        this.user = res.data.user;
      });
    },
    submit() {
      if (this.credential.id) {
        axios.put(`credentials/${this.credential.id}`, { credential: this.credential }).then(res => {
          console.log(res.data);
          this.credential = res.data.credential;
          // this.fetchData();
          this.$snotify.success('Se han guardado los cambios');
        }).catch(err => {
          console.log(err.response);
        });
      } else {
        var userId = this.$route.params.userId;
        this.credential.user_id = userId;
        axios.post('credentials', { credential: this.credential }).then(res => {
          console.log(res.data);
          this.credential = res.data.credential;
          // this.fetchData();
          this.$snotify.success('Se han guardado los cambios');
        }).catch(err => {
          console.log(err.response);
        });
      }
    }
  }
}
</script>

<style>

</style>