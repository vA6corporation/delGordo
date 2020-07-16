<template>
<div class="card" style="border: 1px solid black">
  <header class="bg-white">
    <img :src="src" alt="producto">
  </header>
  <div class="card-body pro-card" style="">
      <h2 class="card-title text-center">
        <a href="#">
          {{ product.name }}
          <!-- {{ product.subcategory_id }} -->
        </a>
      </h2> 
      <h4 class="card-subtitle mb-2 text-center">
        S/ {{ product.sale_price.toFixed(2) }}
        <span style="font-size:0.8rem">x Kg</span>
      </h4>
      <div class="row">
        <div class="col-6">
          <div class="mb-1 d-flex justify-content-end">
            <div type="button" class="btn btn-dark mr-1" style="width: 44px">
              <span class="lead">
                {{ product.counter || 0 }}
              </span>
            </div>
            <button type="button" class="btn btn-dark" @click="plusP(product); $forceUpdate()">
              <feather class="feather-sm" type="plus"/>
            </button>
          </div>
          <div class="form-group d-flex justify-content-end">
            <div type="button" class="d-inline lead justify-content-center d-flex align-items-center" style="width: 44px">
              Kg
            </div>
            <button type="button" class="btn btn-dark" @click="minusP(product); $forceUpdate()">
              <feather class="feather-sm" type="minus"/> 
            </button>
          </div>
        </div>
        <div class="col d-flex align-items-center">
          <button @click="addP(product); $forceUpdate()" class="btn btn-dark">
            <feather class="feather-sm" type="shopping-cart"/>
            <span class="lead">
              Agregar 
            </span>
          </button>
        </div>
      </div>
  </div>
</div>
</template>
<script>
import { mapActions } from 'vuex'

export default {
  props: ['product'],
  data() {
    return {
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
      plusProduct: 'sale/plusProduct',
      minusProduct: 'sale/minusProduct',
    }),
    addP(product) {
      this.addProduct(product);
      axios.post('shoppings', { product }).catch(err => {
        console.log(err.response);
      });
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
    }
  }
}
</script>
