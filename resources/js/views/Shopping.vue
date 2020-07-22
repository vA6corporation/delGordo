<template>
<div>
  <shopping-modal @confirm="submit"/>
  <div class="row" style="padding-left:10vw; margin-top:1.5em; marging-left:0; margin-right:0;">
  <h4>
    <a href="" style="color:black">Seguir Comprando</a>
  </h4>
  </div>
  <div class="container">
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
                    <!-- <div class="form-group">
                    </div> -->
                    <!-- <h3>{{ item.name }} x {{ item.counter }}</h3> -->
                  </div>
                  <div class="col text-center">
                    <h3>{{ item.name }} {{ item.counter }} Kg</h3>
                    <h4 class="form-group">S/ {{ item.sale_price.toFixed(2) }}</h4>
                    <div class="form-group">
                      <button class="btn btn-secondary" @click="removeProduct(item)">
                        <feather type="trash-2"/>
                      </button>
                      <button class="btn btn-secondary" @click="minusProduct(item)">
                        <feather type="minus"/>
                      </button>
                      <button class="btn btn-secondary" @click="plusProduct(item)">
                        <feather type="plus"/>
                      </button>
                    </div>
                    <h4 class="text-success" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) }} Kg</h4>
                    <h4 class="text-danger" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) }} Kg</h4>
                    <p>Total: S/ {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price }}</p>
                  </div>
                </div>
              </li>
              <a href="#" class="list-group-item list-group-item-action">
                <h2 class="text-center font-weight-bold">Total: S/ {{ totalProducts.toFixed(2) }}</h2>
              </a>
            </ul>
          </div>
          <div class="col" payments>
            <p>Paga con cualquiera de estos métodos a través de PayPal</p>
            <img src="@/assets/img/ways.jpg" alt="imagen" width="120">
            <img src="@/assets/img/paypal.png" alt="imagen" width="120">
            <div>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#shoppingModal">
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
      var total = 0;
      var totalCollection = [];
      for (const inventory of product.inventory) {
        if (product.counter >= (total + inventory.weight)) {
          total += inventory.weight;
          totalCollection.push(inventory);
        }
      }
      if (!totalCollection.length && product.inventory.length) {
        totalCollection.push(product.inventory.slice(-1)[0]);
      }
      return totalCollection;
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

<style>

</style>