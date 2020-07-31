<template>
  <nav class="navbar navbar-expand-md navbar-light bg-black shadow-sm">
    <div class="container">
      <button type="button" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation" class="navbar-toggler">
        <span class="navbar-toggler-icon"></span>
      </button>
      <router-link class="collapse navbar-collapse" to="/store">
        <ul class="navbar-nav mr-auto">
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
    </div>
  </nav>
</template>

<script>
import { mapGetters } from 'vuex'
export default {
  computed: {
    ...mapGetters({
      products: 'sale/products',
    }),
  }
}
</script>