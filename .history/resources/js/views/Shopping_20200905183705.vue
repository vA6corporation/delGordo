<template>
<div>
  <customer-modal @confirm="submit"/>
  <div class="row d-sm-block d-md-none">
    <form @submit.prevent="checkDelivery" class="col m-0 p-0">
      <div class="card rounded-0">
        <!--<div class="card-header border-bottom-0 text-center">
          Tienes {{ products.length }} Items
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="item in products" :key="item.id">
            <div class="form-row">
              <!-- <div class="col-2">
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
          </li>-->
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
    <table class='table' style="background-color: white;">
            <thead>
              <th>#</th>
              <th>Producto Solicitado</th>
              <th colspan="2">Cantidad Solicitada</th>
              <th>Modificar Cantidad</th>
              <th colspan="2">Cantidad a Despachar</th>
              <th>Sub Total</th>
            </thead>
            <tbody>
              <tr>
                <td>1</td>
                <td>Picanha Golden Angus</td>
                <td>10.5</td>
                <td>KG</td>
                <td>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="trash-2"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="minus"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="plus"/>
                  </button>
                </td>
                <td>10.35</td>
                <td>KG</td>
                <td>S/485.15</td>
              </tr>
              <tr>
                <td>2</td>
                <td>Pan Brioche</td>
                <td>5</td>
                <td>UND</td>
                <td>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="trash-2"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="minus"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="plus"/>
                  </button>
                </td>
                <td>5</td>
                <td>UND</td>
                <td>S/25.00</td>
              </tr>
              <tr>
                <td>3</td>
                <td>Vino  Domaine de la Romanée-Conti</td>
                <td>2</td>
                <td>BOT</td>
                <td>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="trash-2"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="minus"/>
                  </button>
                  <button type="button" class="btn btn-sm btn-secondary">
                    <feather type="plus"/>
                  </button>
                </td>
                <td>2</td>
                <td>BOT</td>
                <td>S/485.15</td>
              </tr>
            </tbody>
            <tfoot>
              <tr>
                  <th scope="row">Total:</th>
                  <td>S/. </td>
              </tr>
            </tfoot>
      </table>
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