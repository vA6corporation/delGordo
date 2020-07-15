<template>
<div id="shopping_cart">
  <div class="row lead" style="margin-right: 0px; margin-left: 0px;">
    <div class="col-8">
      <article class="total card-product">
        <div class="row font-weight-bold">
          <div class="col-8">
            <strong>Total</strong>
          </div> <div class="col-3">
            <strong>S/0</strong>
          </div>
        </div>
      </article>
    </div>
  </div> 
  <h5 style="text-align: center;">
    <a href="http://localhost:4000/carrito">
      Proceder al pago 
      <!-- <i class="fas fa-chevron-right"></i> -->
    </a>
  </h5>
</div>
</template>

<script>
export default {

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