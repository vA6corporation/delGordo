<template>
<form @submit.prevent="submit" class="row top-one">
  <div class="col my-4">
    <div class="container">
      <h2>DATOS DE LA ENTREGA</h2>
      <hr>
      <div class="row form-group">
        <label for="" class="col-md-3 col-form-label">DNI/RUC</label>
        <div class="col">
          <input type="text" class="form-control" placeholder="DNI o RUC" required>
        </div>
      </div>
      <div class="row form-group">
        <label for="" class="col-md-3 col-form-label">NOMBRE Y APELLIDO</label>
        <div class="col">
          <input type="text" class="form-control" placeholder="NOMBRE Y APELLIDO" required>
        </div>
      </div>
      <div class="row form-group">
        <label for="" class="col-md-3 col-form-label">CELULAR</label>
        <div class="col">
          <input type="text" class="form-control" placeholder="CELULAR" required>
        </div>
      </div>
      <div class="row form-group">
        <label for="" class="col-md-3 col-form-label">DIRECCION DE ENTREGA</label>
        <div class="col">
          <input type="text" class="form-control" placeholder="DIRECCION DE ENTREGA" required>
        </div>
      </div>
      <div class="row form-group">
        <label for="" class="col-md-3 col-form-label">REFERENCIA DE LA DIRECCION</label>
        <div class="col">
          <input type="text" class="form-control" placeholder="REFERENCIA DE LA DIRECCION" required>
        </div>
      </div>
      <div class="form-group">
        <button type="submit" class="btn btn-info float-right">
          <feather type="save"/>
          Guardar 
        </button>
      </div>
    </div>
  </div>
</form>
</template>

<script>
import Categories from '@/components/Categories'
import ShoppingCard from '@/components/ShoppingCard'
import ProductCard from '@/components/ProductCard'

export default {
  components: {
    Categories,
    ShoppingCard,
    ProductCard,
  },
  mounted() {
    this.fetchData();
  },
  data() {
    return {
      products: [],
    }
  },
  methods: {
    submit() {
      
    },
    fetchData() {
      axios.get('products').then(res => {
        console.log(res);
        this.products = res.data.products;
      });
    }
  }
}
</script>

<style scoped>
  label, input, button {
    font-size: 1.2rem;
    font-weight: 550;
  } 

  .top-one {
    background-image: url("../assets/img/bg_wood.png"); 
    background-repeat: repeat;
    height: calc(100vh - 92.8px);
  }

  #bestseller {
    background-color: black;
    opacity: 0.6;
  }

  .container {
    max-width: 50rem;
  }
</style>