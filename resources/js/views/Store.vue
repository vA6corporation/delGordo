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
          <a href="#" @click.prevent="check" class="d-flex align-items-center" style="color: white; font-size: 2rem;">
            <feather class="feather-lg mr-2" type="shopping-cart"/>
            {{ saleProducts.length }}
          </a>
        </li>
      </ul>
    </router-link>
  </nav>
  <div class="row mt-5 mt-md-0">
    <div class="col my-3">
      <nav class="navbar bg-white d-sm-block d-md-none" data-toggle="collapse" data-target="#navbarsExample01">
        <a class="navbar-brand text-reset" href="#">Categorias</a>
        <a href="#" class="text-reset">
          <feather type="chevron-down"/>
        </a>
        <div class="navbar-collapse collapse" id="navbarsExample01" style="">
          <ul class="navbar-nav mr-auto">
            <form class="form-inline my-2 my-md-0">
              <input class="form-control" @click.stop type="text" placeholder="Search" aria-label="Search">
            </form>
            <li class="nav-item" v-for="item in categories" :key="item.id">
              <a class="nav-link" href="#" @click.prevent="category_id = item.id">{{ item.name }}</a>
            </li>
            <li class="nav-item">
              <a href="#" @click.prevent="category_id = null">
                Todas las Categorias
              </a>
            </li>
          </ul>
        </div>
      </nav>
    </div>
  </div>
  <div class="row d-sm-block d-md-none">
    <div class="col-md-4 form-group" v-for="item in filterProducts" :key="item.id">
      <product-card :product="item"/>
    </div>
  </div>
  <div class="row d-none d-md-block">
    <div class="col">
      <div class="container mb-4">
        <div class="row">
          <div class="col search-input">
            <input type="text" v-model="key" class="form-control" placeholder="Buscar producto">
          </div>
        </div>
      </div>
      <div class="container">
        <div class="row">
          <div class="col">
            <categories v-model="category_id"/>
          </div>
          <div class="col-10 col-xl-9">
            <div class="row">
              <div class="col-md-4 form-group" v-for="item in filterProducts" :key="item.id">
                <product-card :product="item"/>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="modal fade" id="checkModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="checkModalLabel">Mensaje</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
          </button>
        </div>
        <div class="modal-body">
          Agregar productos antes de proceder al carrito
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-dismiss="modal">Volver</button>
        </div>
      </div>
    </div>
  </div>
</div>
</template>

<script>
import Categories from '@/components/Categories'
import ShoppingCard from '@/components/ShoppingCard'
import ProductCard from '@/components/ProductCard'
import { mapActions, mapGetters } from 'vuex'

export default {
  components: {
    Categories,
    ShoppingCard,
    ProductCard,
  },
  mounted() {
    var categoryId = this.$route.params.categoryId;
    this.category_id = categoryId;
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
      totalProducts: 'sale/totalProducts',
      saleProducts: 'sale/products',
    }),
    filterProducts() {
      return this.products.filter(e => !this.category_id || this.category_id == e.category_id)
        .filter(e => !this.key || e.name.toLowerCase().includes((this.key || '').toLowerCase()));
    }
  },
  methods: {
    ...mapActions({
      addProduct: 'sale/addProduct',
      removeProduct: 'sale/removeProduct',
      removeAllProducts: 'sale/removeAllProducts',
      minusProduct: 'sale/minusProduct',
    }),
    check() {
      if (this.saleProducts.length) {
        this.$router.replace('shopping');
      } else {
        $('#checkModal').modal('show');
      }
    },
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
      this.addProduct(product);
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
    },
    fetchData() {
      axios.get('categories').then(res => {
        console.log(res);
        this.categories = res.data.categories;
      });
      axios.get('shoppings').then(res => {
        console.log(res);
        this.removeAllProducts();
        var shoppings = res.data.shoppings;
        shoppings.forEach(item => {
          var product = item.product;
          console.log(product);
          if (product) {
            product.counter = item.counter;
            console.log(product);
            this.addProduct(product);
          }
        });
      });
      axios.get('products/all').then(res => {
        console.log(res);
        this.products = res.data.products;
      });
    }
  }
}
</script>

<style>
  @media only screen and (min-width: 500px) {
    #menu{
        padding-top: 10vh;
    }
}

  #bestseller {
    background-color: black;
    opacity: 0.6;
  }

  .container {
    max-width: 100%;
  }

  .categories {
    top: 5rem;
  }

  .btn-group button {
    background-color: black;
    color: white;
  }

  .search-input input {
    padding-right: 2rem;
    padding-left: 2rem;
    height: 4rem;
    border: none;
    font-size: 17px;
    font-weight: 100;
  }
</style>
