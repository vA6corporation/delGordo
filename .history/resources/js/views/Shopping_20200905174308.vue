<template>
<div>
  <customer-modal @confirm="submit"/>
  <div class="row d-sm-block d-md-none">
    <form @submit.prevent="checkDelivery" class="col m-0 p-0">
      <div class="card rounded-0">
          <table class='table'>
            <thead>
              <th>Producto</th>
              <th>Cantidad</th>
              <th>Precio U.</th>
              <th>Sub Total</th>
            </thead>
            <tbody>

            </tbody>
          </table>
          <li class="list-group-item d-flex">
            <router-link to="/store" class="btn btn-dark mr-auto">
              <feather type="chevron-left"/> 
              Segir Comprando
            </router-link>
            <button type="submit" class="btn btn-outline-secondary">
              Pagar 
              <feather type="chevron-right"/>
            </button>
          </li>
        </ul>
      </div>
    </form>
  </div>
  <div class="container d-none d-md-block">
    <form class="card mb-0 mt-3">
      <div class="card-body">
        <h3 class="text-center">Tienes {{ products.length }} Items</h3>
        <div class="row">
          <div class="col">
            <ul class="list-group">
              <li class="list-group-item" v-for="item in products" :key="item.id">
                <div class="row">
                  <div class="col-md-3">
                    <img :src="`/api/products/${item.image_url}`" width="100" alt="producto">
                  </div>
                  <div class="col text-center">
                    <h3>{{ item.name }}</h3>
                  </div>
                  <div>
                    <h4 class="text-danger">S/ {{ item.sale_price.toFixed(2) }}</h4>
                  </div>
                  <div class="col text-center">
                    <div class="form-group">
                      <div class="text-success lead" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(3) }} {{ item.short_unit }}</div>
                      <div class="text-danger lead" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(3) }} {{ item.short_unit }}</div>
                    </div>
                    <div class="form-group">
                      <button type="button" class="btn btn-sm btn-secondary mr-2 my-1" @click="removeP(item)">
                        <feather type="trash-2"/>
                      </button>
                      <div class="btn-group">
                        <button type="button" class="btn btn-sm btn-secondary" @click="minusP(item)">
                          <feather type="minus"/>
                        </button>
                        <div class="p-2 lead">{{ item.counter }} Kg</div>
                        <button type="button" class="btn btn-sm btn-secondary" @click="plusP(item)">
                          <feather type="plus"/>
                        </button>
                      </div>
                    </div>
                    <!-- <span>Sub Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</span> -->
                  </div>
                </div>
              </li>
              <div class="list-group-item">
                <!-- <div class="font-weight-bold lead d-flex justify-content-between">
                  <span>
                    Sub Total: 
                  </span>
                  <span>
                    S/ {{ (totalProducts).toFixed(2) }}
                  </span>
                </div> -->
                <!-- <div class="font-weight-bold lead d-flex justify-content-between">
                  <span>
                    Delivery: 
                  </span>
                  <span>
                    S/ {{ ((delivery ? delivery.price : 0)).toFixed(2) }}
                  </span>
                </div> -->
                <div class="font-weight-bold lead d-flex justify-content-between">
                  <span>
                    Total: 
                  </span>
                  <span>
                    S/ {{ (totalProducts + (delivery ? delivery.price : 0)).toFixed(2) }}
                  </span>
                </div>
              </div>
            </ul>
          </div>
          <!-- <div class="col" payments>
            <p>Paga con cualquiera de estos métodos a través de Mercado Pago</p>
            <img src="@/assets/img/ways.jpg" alt="imagen" width="120">
            <img src="@/assets/img/paypal.png" alt="imagen" width="120">
            <div>
            <button type="submit" class="btn btn-dark">
              Pagar ahora
            </button>
            </div>
          </div> -->
        </div>
      </div>
    </form>
    <div class="row">
      <div class="col d-flex justify-content-between">
        <router-link to="/store" class="btn btn-dark my-3">
          <feather type="chevron-left"/> 
          Seguir Comprando
        </router-link>
        <button type="button" class="btn btn-dark my-3" @click="checkDelivery">
          <feather type='check'/>
          Pagar ahora
        </button>
      </div>
    </div>
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
      this.removeAllProducts();
      var shoppings = res.data.shoppings;
      shoppings.forEach(item => {
        var product = item.product;
        if (product) {
          product.counter = item.counter;
          console.log(product);
          this.addProduct(product);
        }
      });
    });
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
    ...mapActions({
      removeAllProducts: 'sale/removeAllProducts',
      addProduct: 'sale/addProduct',
      plusProduct: 'sale/plusProduct',
      removeProduct: 'sale/removeProduct',
      setSale: 'sale/setSale',
      minusProduct: 'sale/minusProduct',
    }),
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
      console.log(product);
      this.plusProduct(product);
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
      var inventories = [];
      this.products.forEach(item => {
        inventories.push(...this.checkInventory(item));
      });
      if (inventories.length) {
        this.setSale(sale);
        this.$router.replace(`/checkout`);
      } else {
        this.$snotify.error('Debe haber almenos un producto disponible');
        $('.modal').modal('hide');
      }
      this.$loading(false);
    },
  }
}
</script>

<style scoped>
  .container {
    font-size: 0.9rem;
    max-width: 80rem;
  }

  .btn-group button {
    background-color: black;
    color: white;
  }

  .btn-dark {
    background-color: black;
    color: white;
  }
</style>