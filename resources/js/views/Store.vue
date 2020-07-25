<template>
<div>
  <nav class="navbar navbar-dark bg-dark fixed-top d-sm-block d-md-none">
    <a class="navbar-brand" href="#">Categorias</a>
    <button class="navbar-toggler collapsed" type="button" data-toggle="collapse" data-target="#navbarsExample01" aria-controls="navbarsExample01" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

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
  <div class="row d-sm-block d-md-none">
    <div class="col m-0 p-0">
      <div class="card rounded-0 mb-0">
        <div class="card-header border-bottom-0 text-center">
          <span v-if="category_id">
            {{ categories.find(e => e.id == category_id).name }}
          </span>
          <span v-else>
            Todos los productos
          </span> 
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="item in filterProducts" :key="item.id">
            <div class="form-row">
              <div class="col-2">
                <img :src="`/api/products/${item.image_url}`" class="img-fluid" alt="producto">
              </div>
              <div class="col text-center d-flex align-items-center justify-content-center">
                <div>
                  <h4>
                    {{ item.name }} {{ item.counter }}
                  </h4>
                  <h5 class="card-subtitle">
                    S/ {{ item.sale_price.toFixed(2) }}
                    <span style="font-size:0.8rem">x Kg</span>
                  </h5>
                </div>
                <!-- <h5 class="text-success" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h5>
                <h5 class="text-danger" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h5>
                <p>Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</p> -->
              </div>
              <div class="col" style="max-width: 8.5rem">
                <div class="btn-group p-0 btn-sm h-100">
                  <button class="btn btn-sm btn-secondary" @click="minusP(item)">
                    <feather type="minus"/>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="plusP(item)">
                    <feather type="plus"/>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="addP(item)">
                    <feather type="shopping-cart"/>
                  </button>
                </div>
              </div>
            </div>
          </li>
        </ul>
      </div>
      <div class="card rounded-0">
        <div class="card-header border-bottom-0 text-center">
          Tienes {{ pickProducts.length }} Items
        </div>
        <ul class="list-group list-group-flush">
          <li class="list-group-item" v-for="item in pickProducts" :key="item.id">
            <div class="form-row">
              <div class="col-2">
                <img :src="`/api/products/${item.image_url}`" class="img-fluid" alt="producto">
              </div>
              <div class="col text-center">
                <h5>
                  {{ item.name }} {{ item.counter }} Kg
                </h5>
                <!-- <h5 class="text-success" v-if="checkInventory(item).length">Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h5>
                <h5 class="text-danger" v-else>No Disponible: {{ checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0).toFixed(2) }} Kg</h5>
                <p>Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</p> -->
              </div>
              <div class="col" style="max-width: 8.5rem">
                <div class="btn-group p-0 btn-sm h-100">
                  <button class="btn btn-sm btn-secondary" @click="minusP(item)">
                    <feather type="minus"/>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="plusP(item)">
                    <feather type="plus"/>
                  </button>
                  <button class="btn btn-sm btn-secondary" @click="removeP(item)">
                    <feather type="trash-2"/>
                  </button>
                </div>
              </div>
            </div>
          </li>
          <li class="list-group-item">
            <!-- <h2 class="text-center font-weight-bold mb-0">Total: S/ {{ totalProducts.toFixed(2) }}</h2> -->
            <!-- <p>Total: S/ {{ (checkInventory(item).map(e => e.weight).reduce((a, b) => a + b, 0) * item.sale_price).toFixed(2) }}</p> -->
            <h1 class="text-center">
              Total: S/ {{ totalProducts.toFixed(2) }}
            </h1>
          </li>
          <li class="list-group-item">
            <!-- <router-link to="/store" class="btn btn-info mr-auto">
              <feather type="chevron-left"/> 
              Segir Comprando
            </router-link> -->
            <router-link to="/shopping" class="btn btn-outline-info float-right">
              Proceder al Pago
              <feather type="chevron-right"/>
            </router-link>
            <!-- <h2 class="text-center font-weight-bold">Total: S/ {{ totalProducts.toFixed(2) }}</h2> -->
          </li>
        </ul>
      </div>
    </div>
  </div>
  <div class="row d-none d-md-block">
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
          <div class="col-md-4" v-for="item in filterProducts" :key="item.id">
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
      pickProducts: 'sale/products',
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

  .btn-group button {
    background-color: black;
    color: white;
  }
</style>
