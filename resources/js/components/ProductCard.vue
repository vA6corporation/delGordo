<template>
<div id="card-product" class="bg-card py-3 h-100">
  <div class="card mb-0" style="border: 1px solid black">
    <header class="bg-white">
      <img :src="src" alt="producto">
    </header>
    <div class="card-body pro-card" style="">
        <h2 class="card-title text-center">
          <a href="#">{{ product.name }}</a>
        </h2> 
        <h4 class="card-subtitle mb-2 text-center">
          S/ {{ product.sale_price.toFixed(2) }}
          <span style="font-size:0.8rem">x {{ product.short_unit }}</span>
        </h4>
        <div class="form-row">
          <div class="col-6">
            <div class="mb-1 d-flex justify-content-end">
              <div type="button" class="btn btn-dark mr-1" style="width: 44px">
                <span class="lead text-nowrap text-center">
                  {{ localProduct.counter || 0 }}
                </span>
              </div>
              <button type="button" class="btn btn-dark" @click="plusP(localProduct); $forceUpdate()">
                <feather class="feather-sm" type="plus"/>
              </button>
            </div>
            <div class="form-group d-flex justify-content-end">
              <div type="button" class="d-inline lead justify-content-center d-flex align-items-center text-center" style="width: 44px">
                {{ product.short_unit }}
              </div>
              <button type="button" class="btn btn-dark" @click="minusP(localProduct); $forceUpdate()">
                <feather class="feather-sm" type="minus"/> 
              </button>
            </div>
          </div>
          <div class="col d-flex align-items-center">
            <button @click="addP(localProduct); $forceUpdate()" class="btn btn-dark">
              <feather class="feather-sm" type="shopping-cart"/>
              <span class="lead">
                Agregar 
              </span>
            </button>
          </div>
        </div>
    </div>
  </div>
</div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
  props: ['product'],
  mounted() {
    this.localProduct = Object.assign({
      counter: 0,
    }, this.product);
  },
  data() {
    return {
      localProduct: {},
      counter: 0,
      message: 'Agregar'
    }
  },
  computed: {
    src() {
      if (this.product.image_url) {
        return `/api/products/${this.product.image_url}`;
      } else {
        return "@/assets/img/1.jpg";
      }
    }
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      addProduct: 'sale/addProduct',
      plusProduct: 'sale/plusProduct',
      minusProduct: 'sale/minusProduct',
    }),
    addP(product) {
      let doc = Object.assign({}, product);
      this.addProduct(doc);
      this.$snotify.success('Producto agregado');
      product.counter = 0;
      axios.post('shoppings', { product: doc }).catch(err => {
        console.log(err.response);
      });
    },
    plusP(product) {
      if (product.unit_code == 'KGM') {
        product.counter += 0.5;
      } else {
        product.counter += 1;
      }
    },
    minusP(product) {
      if (product.unit_code == 'KGM') {
        if (product.counter >= 0.5) {
          product.counter -= 0.5;
        }
      } else {
        if (product.counter >= 1) {
          product.counter -= 1;
        }
      }
    }
  }
}
</script>

<style scoped>
@media only screen and (max-width: 1400px) {
  #card-product {
    padding-left: 2rem !important;
    padding-right: 2rem !important;
  }   
}

#card-product {
  padding-left: 3rem;
  padding-right: 3rem;
}

.bg-card {
  background-image: url("../assets/img/backgroundCard.jpg"); 
  /* background-repeat: repeat; */
  background-size: contain;
}

</style>