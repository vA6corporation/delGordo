<template>
<div id="shopping_cart">
  <ul class="list-group list-group-flush">
    <li class="list-group-item d-flex justify-content-between px-0" v-for='item in products' :key="item.id">
      <span>
        {{ item.name }}
      </span>
      <span>
        x{{ item.counter }}  
      </span> 
    </li>
  </ul>
  <div class="form-group font-weight-bold d-flex justify-content-between">
    <strong>Total</strong>
    <strong>S/ {{ totalProducts.toFixed(2) }}</strong>
  </div>
  <h5 style="text-align: center;">
    <router-link to="/shopping">
      Proceder al pago  
      <feather type="chevron-right"/>
    </router-link>
  </h5>
</div>
</template>

<script>
import { mapGetters } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      products: 'sale/products',
      totalProducts: 'sale/totalProducts'
    }),
  }
}
</script>

<style lang='scss' scoped>


#shopping_cart {
  z-index: 100;
  position: absolute;
  right: 3vw;
  top: 25vh;
  padding: 10px;
  border: 1px solid black;
  border-radius: 5px;
  background-color: white;

  span, strong {
    font-size: 1.3rem;
  }
}

#shopping_cart a {
  color: black;
  // font-family:  DK Lemon Yellow Sun;
}
</style>