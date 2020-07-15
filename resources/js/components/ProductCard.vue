<template>
<div class="card" style="border: 1px solid black">
  <header class="bg-primary">
    <img src="@/assets/img/1.jpg" alt="producto">
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
              {{ counter }}
            </div>
            <button type="button" class="btn btn-dark" @click="counter++">
              <feather class="feather-sm" type="plus"/>
            </button>
          </div>
          <div class="form-group d-flex justify-content-end">
            <div type="button" class="d-inline lead justify-content-center d-flex align-items-center" style="width: 44px">
              Kg
            </div>
            <button type="button" class="btn btn-dark" @click="counter--">
              <feather class="feather-sm" type="minus"/> 
            </button>
          </div>
        </div>
        <div class="col d-flex align-items-center">
          <button @click="addToCart" class="btn btn-dark">
            <feather class="feather-sm" type="shopping-cart"/>
            Agregar 
          </button>
        </div>
      </div>
  </div>
</div>
</template>
<script>
export default {
  data(){
    return {
      counter: 0,
      message: 'Agregar'
    }
  },
  props: ['product'],
  methods:{
    addToCart() {

    },
  }
}
</script>