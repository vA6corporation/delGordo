<template>
<div>
  <customer-modal @confirm="submit"/>
  <div class="row d-sm-block d-md-none">
    <form @submit.prevent="checkDelivery" class="col m-0 p-0">
      <div class="card rounded-0">
        <div class="card-header border-bottom-0 text-center">
          Tienes {{ products.length }} Items
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="item in products" :key="item.id">
            <div class="form-row">
              <div class="col-2">
                <img :src="`/api/products/${item.image_url}`" class="img-fluid" alt="producto">
              </div>
              <div class="col text-center">
                <h5>
                  {{ item.name }} {{ item.counter }} Kg
                </h5>
                <h5 class="text-success" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h5>
                <h5 class="text-danger" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h5>
                <p>Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</p>
              </div>
              <div class="col" style="max-width: 8.5rem">
                <div class="btn-group p-0 btn-sm h-100">
                  <button type="button" class="btn btn-sm btn-secondary" @click="removeP(item)">
                    <feather type="trash-2"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary" @click="minusP(item)">
                    <feather type="minus"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary" @click="plusP(item)">
                    <feather type="plus"/>
                  </button>
                </div>
              </div>
            </div>
          </li>
          <!-- <li class="list-group-item">
            <select class="custom-select" v-model="delivery" required>
              <option :value="null">SELECCIONE EL DISTRITO DE ENVIO...</option>
              <option v-for="item in deliveries" :value="item" :key="item.id">{{ `${item.name} envio: S/ ${item.price.toFixed(2)}` }}</option>
            </select>
          </li> -->
          <li class="list-group-item">
            <h2 class="text-center font-weight-bold mb-0">Total: S/ {{ totalProducts.toFixed(2) }}</h2>
          </li>
          <li class="list-group-item d-flex">
            <router-link to="/store" class="btn btn-dark mr-auto">
              <feather type="chevron-left"/> 
              Segir Comprando
            </router-link>
            <button type="submit" class="btn btn-outline-secondary">
              Pagar 
              <feather type="chevron-right"/>
            </button>
            <!-- <h2 class="text-center font-weight-bold">Total: S/ {{ totalProducts.toFixed(2) }}</h2> -->
          </li>
        </ul>
      </div>
    </form>
    <!-- <ul class="list-group">
      <li class="list-group-item"></li>
    </ul> -->
  </div>
  <div class="container d-none d-md-block">
    <div class="row">
      <div class="col">
        <router-link to="/store" class="btn btn-dark my-3">
          <feather type="chevron-left"/> 
          Seguir Comprando
        </router-link>
      </div>
    </div>
    <form class="card" @submit.prevent="checkDelivery">
      <div class="card-body">
        <h2>Carrito de Compras</h2>
        <div class="row">
          <div class="col-md-6">
            <ul class="list-group">
              <li class="list-group-item" v-for="item in products" :key="item.id">
                <div class="row">
                  <div class="col-md-6 text-center">
                    <img :src="`/api/products/${item.image_url}`" width="150" alt="producto">
                  </div>
                  <div class="col text-center">
                    <h3>{{ item.name }} {{ item.counter }} Kg</h3>
                    <h4 class="form-group">S/ {{ item.sale_price.toFixed(2) }}</h4>
                    <div class="form-group">
                      <button type="button" class="btn btn-secondary" @click="removeP(item)">
                        <feather type="trash-2"/>
                      </button>
                      <button type="button" class="btn btn-secondary" @click="minusP(item)">
                        <feather type="minus"/>
                      </button>
                      <button type="button" class="btn btn-secondary" @click="plusP(item)">
                        <feather type="plus"/>
                      </button>
                    </div>
                    <h4 class="text-success" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h4>
                    <h4 class="text-danger" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h4>
                    <p>Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</p>
                  </div>
                </div>
              </li>
              <a href="#" class="list-group-item list-group-item-action">
                <h2 class="text-center font-weight-bold">Total: S/ {{ (totalProducts + (delivery ? delivery.price : 0)).toFixed(2) }}</h2>
              </a>
            </ul>
          </div>
          <div class="col" payments>
            <!-- <select class="custom-select" v-model="delivery" required>
              <option :value="null">SELECCIONE EL DISTRITO DE ENVIO...</option>
              <option v-for="item in deliveries" :value="item" :key="item.id">{{ `${item.name} envio: S/ ${item.price.toFixed(2)}` }}</option>
            </select> -->
            <p>Paga con cualquiera de estos métodos a través de Mercado Pago</p>
            <img src="@/assets/img/ways.jpg" alt="imagen" width="120">
            <img src="@/assets/img/paypal.png" alt="imagen" width="120">
            <div>
            <button type="submit" class="btn btn-dark">
              Pagar ahora
            </button>
            </div>
          </div>
        </div>
      </div>
    </form>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import CustomerModal from '@/components/CustomerModal'

export default {
  components: {
    CustomerModal,
  },
  mounted() {
    axios.get('shoppings').then(res => {
      console.log(res);
      var shoppings = res.data.shoppings;
      shoppings.forEach(item => {
        var product = item.product;
        product.counter = item.counter;
        console.log(product);
        this.addProduct(product);
      });
    });
    // axios.get('deliveries').then(res => {
    //   console.log(res);
    //   this.deliveries = res.data.deliveries;
    // });
  },
  data() {
    return {
      delivery: null,
      customer: {},
      deliveries: [],
    }
  },
  computed: {
    ...mapGetters({
      products: 'sale/products',
      totalProducts: 'sale/totalProducts',
    }),
  },
  methods: {
    checkDelivery() {
      $('#shoppingModal').modal('show');
    },
    removeP(product) {
      axios.delete(`shoppings/${product.id}`).catch(err => {
        console.log(err.response);
      });
      this.removeProduct(product);
    },
    plusP(product) {
      this.addProduct(product);
      axios.post('shoppings', { product }).catch(err => {
        console.log(err.response);
      });
    },
    minusP(product) {
      this.minusProduct(product);
      axios.post('shoppings', { product }).catch(err => {
        console.log(err.response);
      });
    },
    submit(sale) {
      axios.get('shoppings/removeAll').catch(err => {
        console.log(err.response);
      });
      var inventories = [];
      // var sale = {
      //   delivery_id: this.delivery.id,
      // };
      this.products.forEach(item => {
        inventories.push(...this.checkInventory(item));
      });
      if (inventories.length) {
        axios.post('sales', { inventories, sale }).then(res => {
          console.log(res);
          $('.modal').modal('hide');
          var sale = res.data.sale;
          // this.$snotify.success('Compra realizada correctamente');
          this.removeAllProducts();
          // this.$router.replace('/store');
          this.$router.replace(`/${sale.id}/checkout`);
        }).catch(err => {
          console.log(err.response);
        });
      } else {
        this.$snotify.error('Debe haber almenos un producto disponible');
        $('.modal').modal('hide');
      }
    },
    ...mapActions({
      removeAllProducts: 'sale/removeAllProducts',
      addProduct: 'sale/addProduct',
      removeProduct: 'sale/removeProduct',
      // plusProduct: 'sale/plusProduct',
      minusProduct: 'sale/minusProduct',
    }),
  }
}
</script>

<style scoped>
  .btn-group button {
    background-color: black;
    color: white;
  }

  .btn-dark {
    background-color: black;
    color: white;
  }
</style>