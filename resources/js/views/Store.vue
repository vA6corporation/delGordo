<template>
<div class="row top-one">
  <categories v-model="category_id"/>
  <shoping-card/>
  <div class="col">
    <div id="bestseller" class="row mb-4">
      <div class="container py-3">
        <div class="row">
          <div class="col-4 text-center">
            <img src="@/assets/img/bestseller.png" style="width: 15rem">
          </div>
          <div class="col">
            <div class="row px-5">
              <div class="col">
                <img src="@/assets/img/destacado1.jpg" style="width: 8rem">
              </div>
              <div class="col">
                <img src="@/assets/img/destacado1.jpg" style="width: 8rem">
              </div>
              <div class="col">
                <img src="@/assets/img/destacado1.jpg" style="width: 8rem">
              </div>
              <div class="col">
                <img src="@/assets/img/destacado1.jpg" style="width: 8rem">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="container mb-4">
      <div class="row">
        <div class="col">
          <input type="text" v-model="key" class="form-control" placeholder="Buscar producto">
        </div>
      </div>
    </div>
    <div class="container">
      <div class="row">
        <div class="col-4" v-for="item in filterProducts" :key="item.id">
          <product-card :product="item"/>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Categories from '@/components/Categories'
import ShopingCard from '@/components/ShopingCard'
import ProductCard from '@/components/ProductCard'
import { mapActions } from 'vuex'

export default {
  components: {
    Categories,
    ShopingCard,
    ProductCard,
  },
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      products: [],
      category_id: null,
      key: null,
    }
  },
  computed: {
    filterProducts() {
      return this.products.filter(e => !this.category_id || this.category_id == e.category_id)
        .filter(e => !this.key || e.name.includes(this.key));
    }
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      // plusProduct: 'sale/plusProduct',
      // minusProduct: 'sale/minusProduct',
    }),
    fetchData() {
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
      axios.get('products').then(res => {
        console.log(res);
        this.products = res.data.products;
      });
    }
  }
}
</script>

<style>
  /* .top-one {
    background-image: url("../assets/img/bg_wood.png"); 
    background-repeat: repeat;
    height: calc(100vh - 92.8px);
  } */

  #bestseller {
    background-color: black;
    opacity: 0.6;
  }
</style>
