<template>
  <nav class="navbar navbar-expand-md navbar-light bg-black shadow-sm">
    <div class="container">
      <button type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
        <span class="navbar-toggler-icon"></span>
      </button> 
      <div class="collapse navbar-collapse">
        <ul class="navbar-nav mr-auto">
          <img src="@/assets/img/logo.png" alt="logo">
        </ul> 
        <img src="@/assets/img/store_header.png" alt="logo" style="height: 10vh;"> 
        <ul class="navbar-nav ml-auto">
          <li class="nav-link">
            <a href="carrito" style="color: white; font-size: 2rem;">
              <i class="fas fa-shopping-cart"></i> 0 
            </a>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {

}
</script>