<template>
<div class="row">
  <!-- <search/> -->
  <pos-modal @confirm="submit"/>
  <div class="col">
    <div class="form-group">
      <form @submit.prevent="searchProducts" class="search-input">
        <input type="text" v-model="key" class="form-control" placeholder="BUSCADOR" required>
        <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <i class="tim-icons icon-simple-remove"></i>
        </button> -->
      </form>
    </div>
    <div class="form-row">
      <div class="col">
        <div class="card">
          <div class="card-header py-2">
            <div class="d-flex justify-content-between">
              <h3 class="card-title mb-0">Punto de Venta</h3>
              <div class="btn-toolbar">
                <button type="button" data-toggle="modal" data-target="#posModal" class="btn btn-info" to="/deliveries/create">
                  <feather type="save"/>
                  Guardar
                </button>
              </div>
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action d-flex justify-content-between align-items-center" v-for="item in products" :key="item.id">
              <div>
                <span class="lead">{{ item.name }} {{ item.sub_category.name }}</span>
                <br>
                <span>{{ item.category.name }}</span>
              </div>
              <div class="align-items-center">
                <span class="mr-2">{{ item.counter }} Kg - Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }}Kg - Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</span>
                <br>
                <div class="btn-group float-right">
                  <button type="button" @click="removeProduct(item)" class="btn btn-secondary">
                    <feather type="trash-2"/>
                  </button>
                  <button type="button" @click="minusProduct(item)" class="btn btn-info">
                    <feather type="minus"/>
                  </button>
                  <button type="button" @click="addProduct(item)" class="btn btn-info">
                    <feather type="plus"/>
                  </button>
                </div>
              </div>
            </a>
          </ul>
        </div>
      </div>
      <div class="col-md-4" v-if="productsPane.length">
        <div class="card">
          <div class="card-header py-2">
            <div class="d-flex justify-content-between">
              <h3 class="card-title mb-0">Resultados</h3>
              <div class="btn-toolbar">
                <!-- <router-link class="btn btn-info" to="/deliveries/create">
                  <feather type="save"/>
                  Guardar
                </router-link> -->
              </div>
            </div>
          </div>
          <ul class="list-group list-group-flush">
            <a href="#" class="list-group-item list-group-item-action" @click.prevent="addProduct(item)" v-for="item in productsPane" :key="item.id">
              <span class="lead">{{ item.name }} {{ item.sub_category.name }}</span>
              <br>
              <span>{{ item.category.name }}</span>
            </a>
          </ul>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import PosModal from '@/components/PosModal'
import { mapGetters, mapActions } from 'vuex'

export default {
  components: {
    PosModal,
  },
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      key: '',
      deliveries: [],
      productsPane: [],
    }
  },
  computed: {
    ...mapGetters({
      products: 'sale/products',
    }),
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      removeProduct: 'sale/removeProduct',
      removeAllProducts: 'sale/removeAllProducts',
      // plusProduct: 'sale/plusProduct',
      minusProduct: 'sale/minusProduct',
    }),
    submit(sale) {
      console.log(sale);
      console.log('estamos listos');
      var inventories = [];
      this.products.forEach(item => {
        inventories.push(...this.checkInventory(item));
      });
      if (inventories.length) {
        axios.post('sales', { sale, inventories }).then(res => {
          console.log(res);
          this.$snotify.success('Venta registrada correctamente');
          this.removeAllProducts();
        });
      } else {
        this.$snotify.error('Es necesario al menos un producto');
      }
    },
    searchProducts() {
      axios.get(`products/${this.key}/search`).then(res => {
        console.log(res);
        this.productsPane = res.data.products;
      }).catch(err => {
        console.log(err.response);
        this.$snotify.error(err.response.data);
      });
      this.key = '';
    },
    fetchData() {
      axios.get('deliveries').then(res => {
        console.log(res.data);
        this.deliveries = res.data.deliveries;
      }).catch(err => {
        console.log(err.response);
      });
    }
  }
}
</script>

<style>
.search-input input {
  padding-right: 2rem;
  padding-left: 2rem;
  height: 4rem;
  border: none;
  font-size: 17px;
  font-weight: 100;
}
</style>