<template>
<div>
  <shopping-modal @confirm="submit"/>
  <div class="row d-sm-block d-md-none">
    <div class="col m-0 p-0">
      <div class="card rounded-0">
        <div class="card-header border-bottom-0 text-center">
          Tienes {{ products.length }} Items
        </div>
        <!-- <div class="card-body">

        </div> -->
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
                  <button class="btn btn-sm btn-secondary" @click="removeP(item)">
                    <feather type="trash-2"/>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="minusP(item)">
                    <feather type="minus"/>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="plusP(item)">
                    <feather type="plus"/>
                  </button>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <h2 class="text-center font-weight-bold mb-0">Total: S/ {{ totalProducts.toFixed(2) }}</h2>
          </li>
          <li class="list-group-item d-flex">
            <router-link to="/store" class="btn btn-dark mr-auto">
              <feather type="chevron-left"/> 
              Segir Comprando
            </router-link>
            <button type="button" data-toggle="modal" data-target="#shoppingModal" class="btn btn-outline-secondary">
              Pagar 
              <feather type="chevron-right"/>
            </button>
            <!-- <h2 class="text-center font-weight-bold">Total: S/ {{ totalProducts.toFixed(2) }}</h2> -->
          </li>
        </ul>
      </div>
    </div>
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
    <div class="card">
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
                      <button class="btn btn-secondary" @click="removeP(item)">
                        <feather type="trash-2"/>
                      </button>
                      <button class="btn btn-secondary" @click="minusP(item)">
                        <feather type="minus"/>
                      </button>
                      <button class="btn btn-secondary" @click="plusP(item)">
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
                <h2 class="text-center font-weight-bold">Total: S/ {{ totalProducts.toFixed(2) }}</h2>
              </a>
            </ul>
          </div>
          <div class="col" payments>
            <p>Paga con cualquiera de estos métodos a través de Mercado Pago</p>
            <img src="@/assets/img/ways.jpg" alt="imagen" width="120">
            <img src="@/assets/img/paypal.png" alt="imagen" width="120">
            <div>
            <button type="button" class="btn btn-dark" data-toggle="modal" data-target="#shoppingModal">
              Pagar ahora
            </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'
import ShoppingModal from '@/components/ShoppingModal'

export default {
  components: {
    ShoppingModal,
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
  },
  data() {
    return {
      customer: {},
    }
  },
  computed: {
    ...mapGetters({
      products: 'sale/products',
      totalProducts: 'sale/totalProducts',
    }),
  },
  methods: {
    removeP(product) {
      axios.delete(`shoppings/${product.id}`).catch(err => {
        console.log(err.response);
      });
      this.removeProduct(product);
    },
    plusP(product) {
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
    submit(customer) {
      axios.get('shoppings/removeAll').catch(err => {
        console.log(err.response);
      });
      var inventories = [];
      this.products.forEach(item => {
        inventories.push(...this.checkInventory(item));
      });
      if (inventories.length) {
        axios.post('sales', { customer, inventories }).then(res => {
          console.log(res);
          $('.modal').modal('hide');
          this.$snotify.success('Compra realizada correctamente');
          this.removeAllProducts();
          this.$router.replace('/store');
        }).catch(err => {
          console.log(err.response);
        });
      } else {
        this.$snotify.error('Debe haber almenos un producto disponible');
        $('.modal').modal('hide');
      }
    },
    checkInventory(product) {
      var totalOne = 0;
      var totalCollectionOne = [];
      var totalTwo = 0;
      var totalCollectionTwo = [];
      
      for (const inventory of product.inventory) {
        if (product.counter % inventory.weight) {
          continue;
        }
        if (product.counter >= (totalOne + inventory.weight)) {
          totalOne += inventory.weight;
          totalCollectionOne.push(inventory);
        }
      }

      for (const inventory of product.inventory) {
        if (product.counter >= (totalTwo + inventory.weight)) {
          totalTwo += inventory.weight;
          totalCollectionTwo.push(inventory);
        }
      }

      var minOne = product.counter - totalOne;
      var minTwo = product.counter - totalTwo; 

      if (minOne < minTwo) {
        if (!totalCollectionOne.length && product.inventory.length) {
          totalCollectionOne.push(product.inventory.slice(-1)[0]);
          return totalCollectionOne;
        } else {
          return totalCollectionOne;
        }
      } else {
        if (!totalCollectionTwo.length && product.inventory.length) {
          totalCollectionTwo.push(product.inventory.slice(-1)[0]);
          return totalCollectionTwo;
        } else {
          return totalCollectionTwo;
        }
      }
      // return [];
    },
    ...mapActions({
      removeAllProducts: 'sale/removeAllProducts',
      addProduct: 'sale/addProduct',
      removeProduct: 'sale/removeProduct',
      plusProduct: 'sale/plusProduct',
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