<template>
<div id="shopping_cart">
  <ul class="list-group list-group-flush">
    <li class="list-group-item d-flex justify-content-between" v-for='item in products' :key="item.id">
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
    <strong>S/ {{ totalProducts }}</strong>
  </div>
  <!-- <div class="row lead" style="margin-right: 0px; margin-left: 0px;">
    <div class="col-8">
      <article class="total card-product">
      </article>
    </div>
  </div>  -->
  <h5 style="text-align: center;">
    <router-link to="/payment">
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

<style scoped>
#shopping_cart {
  z-index: 100;
  position:absolute;
  right:3vw;
  top: 25vh;
  padding: 10px;
  border: 1px solid black;
  border-radius: 5px;
  background-color: white;
}

#shopping_cart a {
  color: black;
  font-family:  DK Lemon Yellow Sun;
}
</style>