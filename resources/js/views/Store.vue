<template>
<div>
  <nav class="navbar navbar-expand-md navbar-light bg-black shadow-sm fixed-top">
    <router-link class="container" to="/store">
      <ul class="navbar-nav mr-auto d-none d-md-block">
        <img src="@/assets/img/logo.png" alt="logo">
      </ul> 
      <img src="@/assets/img/store_header.png" alt="logo" style="height: 10vh;"> 
      <ul class="navbar-nav ml-auto">
        <li class="nav-link">
          <router-link to="/shopping" class="d-flex align-items-center" style="color: white; font-size: 2rem;">
            <feather class="feather-lg mr-2" type="shopping-cart"/>
            {{ products.length }}
          </router-link>
        </li>
      </ul>
    </router-link>
  </nav>
  <div class="row mt-5">
    <div class="col my-3">
      <nav class="navbar bg-white d-sm-block d-md-none" data-toggle="collapse" data-target="#navbarsExample01">
        <a class="navbar-brand text-reset" href="#">Categorias</a>
        <a href="#" class="text-reset">
          <feather type="chevron-down"/>
        </a>
        <div class="navbar-collapse collapse" id="navbarsExample01" style="">
          <ul class="navbar-nav mr-auto">
            <li class="nav-item" v-for="item in categories" :key="item.id">
              <a class="nav-link" href="#" @click="category_id = item.id">{{ item.name }}</a>
            </li>
          </ul>
          <form class="form-inline my-2 my-md-0">
            <input class="form-control" type="text" placeholder="Search" aria-label="Search">
          </form>
        </div>
      </nav>
    </div>
  </div>
  <div class="row d-sm-block d-md-none">
    <div class="col-md-4 form-group" v-for="item in filterProducts" :key="item.id">
      <product-card :product="item"/>
    </div>
    <!-- <div class="col m-0 p-0">
      <div class="card rounded-0">
        <div class="card-header border-bottom-0 text-center">
          Tienes {{ pickProducts.length }} Items
        </div>
      </div>
    </div> -->
  </div>
  <div class="row d-none d-md-block">
    <categories v-model="category_id"/>
    <shoping-card/>
    <div class="col">
      <!-- <div id="bestseller" class="row mb-4">
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
      </div> -->
      <div class="container mb-4">
        <div class="row">
          <div class="col">
            <input type="text" v-model="key" class="form-control" placeholder="Buscar producto">
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col-md-4 form-group" v-for="item in filterProducts" :key="item.id">
            <product-card :product="item"/>
          </div>
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
import { mapActions, mapGetters } from 'vuex'

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
      categories: [],
      category_id: null,
      key: null,
    }
  },
  computed: {
    ...mapGetters({
      // pickProducts: 'sale/products',
      totalProducts: 'sale/totalProducts'
    }),
    filterProducts() {
      return this.products.filter(e => !this.category_id || this.category_id == e.category_id)
        .filter(e => !this.key || e.name.includes(this.key));
    }
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      removeProduct: 'sale/removeProduct',
      removeAllProducts: 'sale/removeAllProducts',
      plusProduct: 'sale/plusProduct',
      minusProduct: 'sale/minusProduct',
    }),
    removeP(product) {
      this.removeProduct(product);
      axios.delete(`shoppings/${product.id}`).catch(err => {
        console.log(err.response);
      });
    },
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
    fetchData() {
      axios.get('categories').then(res => {
        console.log(res);
        this.categories = res.data.categories;
      });
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
  #bestseller {
    background-color: black;
    opacity: 0.6;
  }

  .categories {
    top: 5rem;
    /* position: fixed; */
    /* height: 0.5rem; */
  }

  .btn-group button {
    background-color: black;
    color: white;
  }
</style>
