<template>
<div>
  <div class="row" style="padding-left:10vw; margin-top:1.5em; marging-left:0; margin-right:0;">
  <h4>
    <a href="" style="color:black">
    <!-- <i class="fas fa-chevron-left"></i>  -->
    Seguir Comprando
    </a>
  </h4>
  </div>
  <div class="container">
    <div class="card padding">
      <header>
        <h2>Carrito de Compras</h2>
      </header>
      <div class="card-body">
        <div class="row">
          <div class="col-md-6">
            <!-- <product-shopping-component style="font-size:1.5rem"></product-shopping-component> -->
            <ul class="list-group">
              <li class="list-group-item" v-for="item in products" :key="item.id">
                <div class="row">
                  <div class="col-md-6 text-center">
                    <div class="form-group">
                      <img :src="`/api/products/${item.image_url}`" width="100" alt="producto">
                    </div>
                    <h3>
                      {{ item.name }} x {{ item.counter }}
                    </h3>
                  </div>
                  <div class="col text-center">
                    <h2 class="form-group">
                      S/ {{ item.sale_price.toFixed(2) }}
                    </h2>
                    <button class="btn btn-secondary" @click="removeProduct(item)">
                      <feather type="trash-2"/>
                    </button>
                  </div>
                </div>
              </li>
              <a href="#" class="list-group-item list-group-item-action">
                <h2 class="text-center">
                  Total: S/ {{ totalProducts.toFixed(2) }}
                </h2>
              </a>
            </ul>
          </div>
          <div class="col" payments>
            <p>Paga con cualquiera de estos métodos a través de PayPal</p>
            <img src="@/assets/img/ways.jpg" alt="imagen" width="120">
            <img src="@/assets/img/paypal.png" alt="imagen" width="120">
            <div>
            <button type="button" class="btn btn-info" data-toggle="modal" data-target="#myModal">
              Pagar ahora
            </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="myModal" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
    <div class="modal-header">
      <h4 class="modal-title">Del Gordo</h4>
    </div>
    <div class="modal-body">
      <form action="/action_page.php">
        <div class="form-group">
        <label for="email">Por favor, ingrese su DNI, teléfono y dirección de entrega</label>
        <input type="number" class="form-control" id="email" placeholder="DNI: 77035606" name="email">
        </div>
        <div class="form-group">
        <input type="number" class="form-control" id="phone" placeholder="Tel: 934094501" name="email">
        </div>
        <div class="form-group">
        <input type="text" class="form-control" id="phone" placeholder="Direc: Cerro Prieto 269" name="email">
        </div>
      </form>
    </div>
    </div>
  </div>
  </div>
</div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
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
  computed: {
    ...mapGetters({
      products: 'sale/products',
      totalProducts: 'sale/totalProducts',
    }),
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      removeProduct: 'sale/removeProduct',
    }),
  }
}
</script>

<style>

</style>