<template>
<div>
  <checkout-modal description="Del Gordo" :transaction-amount="totalProducts + (sale.delivery_price || 0)" :sale="sale" @confirm="successCheckout" @error="errorCheckout"/>
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
                  {{ item.name }} {{ item.counter }} {{ item.short_unit }}
                </h5>
                <span class="text-success lead" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(3) }} {{ item.short_unit }}</span>
                <span class="text-danger lead" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} {{ item.short_unit }}</span>
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
          <li class="list-group-item">
            <h2 class="text-center font-weight-bold mb-0">Total: S/ {{ totalProducts.toFixed(2) }}</h2>
          </li>
          <li class="list-group-item d-flex">
            <router-link to="/store" class="btn btn-dark mr-auto" style="background-color: white;">
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
  <div id="table_shopping" class="container d-none d-md-block">
    <table class='table' style="background-color: white; margin-top: 5vh;">
            <thead style="background-color:rgb(245, 166, 35)">
              <th style="text-align:center">#</th>
              <th>Producto Solicitado</th>
              <th colspan="1"> Solicitado</th>
              <th>Modificar Cantidad</th>
              <th colspan="1"> A Despachar</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <tr v-for="(item, index) in products" :key="item.id">
                <td style="text-align:center">{{ index + 1 }}</td>
                <td class="text-truncate" style="max-width: 18rem">
                  <img style="height: 5vh;" :src="`/api/products/${item.image_url}`" alt=""/>
                  <span class="ml-2">{{ item.name }}</span>
                </td>
                <td>
                  <p>
                    {{ item.counter }} {{ item.short_unit }}
                  </p>
                </td>
                <td>
                  <button type="button" class="btn btn-sm btn-secondary" @click="removeP(item)">
                    <feather type="trash-2"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary" @click="minusP(item)">
                    <feather type="minus"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary" @click="plusP(item)">
                    <feather type="plus"/>
                  </button>
                </td>
                <td>
                  <span class="text-success" v-if="checkInventory(item).length">{{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(3) }} {{ item.short_unit }}</span>
                  <span class="text-danger" v-else>No Disponible</span>
                </td>
                <td>
                  S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}
                </td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                  <td colspan="4"></td>
                  <th scope="row">Total:</th>
                  <td>S/ {{ totalProducts.toFixed(2) }}</td>
              </tr>
            </tfoot>
      </table>
    <div class="row">
      <div class="col d-flex justify-content-end">
        <router-link to="/store" class="btn my-3 cart_buttons mr-2" style="background-color: white !important">
          <feather type="chevron-left"/> 
          Seguir Comprando
        </router-link>
        <button type="button" class="btn btn-dark my-3 cart_buttons" @click="checkDelivery">
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
import CheckoutModal from '@/components/CheckoutModal'

export default {
  components: {
    CustomerModal,
    CheckoutModal,
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
      token: process.env.VUE_APP_TOKEN || 'TEST-dd114825-b697-4ce1-9f03-e5fce322b40f',
      delivery: null,
      customer: {},
      deliveries: [],
    }
  },
  computed: {
    ...mapGetters({
      sale: 'sale/getSale',
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
    successCheckout(data) {
      this.$router.replace(`/${data.sale.id}/checkoutDetails`);
      axios.get('shoppings/removeAll').catch(err => {
        console.log(err.response);
      });
      this.removeAllProducts();
      console.log(data);
      location.reload();
    },
    errorCheckout(error) {
      console.log(data);
    },
    submit(sale) {
      var inventories = [];
      this.products.forEach(item => {
        inventories.push(...this.checkInventory(item));
      });
      if (inventories.length) {
        console.log('hola mundo');
        this.setSale(sale);
        $('.modal').modal('hide');
        $('#checkoutModal').modal('show');
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
    color: black;
  }
  #table_shopping{
    width: 50%;
  }
  .cart_buttons{
    background-color: rgb(245, 166, 35)!important;
    color: black!important;
  }
  
  @media only screen and (max-width: 1500px) {
    #table_shopping{
    width: 60%;
  }
  }
  @media only screen and (max-width: 1300px) {
    #table_shopping{
    width: 70%;
  }
  }
  @media only screen and (max-width: 1100px) {
    #table_shopping{
    width: 90%;
  }
  }
  @media only screen and (max-width: 850px) {
    #table_shopping{
    width: 98%;
  }
  }
</style>