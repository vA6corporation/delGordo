<template>
  <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <div class="navbar-toggle d-inline">
          <button type="button" class="navbar-toggler" @click="$emit('toggle-sidebar')">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </button>
        </div>
        <a class="navbar-brand" href="#">{{ (office || {}).name }}</a>
      </div>
      <div>
        <ul class="ml-auto">
          <li class="dropdown nav-item">
            <a href="#" class="dropdown-toggle nav-link text-white" data-toggle="dropdown" aria-expanded="false">
              <img class="rounded-circle" style="width: 2.5rem" src="@/assets/img/user.svg" alt="Profile Photo">
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="nav-link">
                <span class="text-muted text-capitalize">{{ (user || {}).name }}</span>
              </li>
              <div class="dropdown-divider"></div>
              <li class="nav-link">
                <a href="/store" class="nav-item dropdown-item">Tienda</a>
              </li>
              <li class="nav-link">
                <a href="#" class="nav-item dropdown-item">Perfil</a>
              </li>
              <li class="nav-link">
                <a href="#" class="nav-item dropdown-item">Ajustes</a>
              </li>
              <li class="nav-link">
                <router-link class="nav-item dropdown-item" @click.native="fetchOffice(null)" to="/setOffice">Cambiar Sucursal</router-link>
              </li>
              <li class="dropdown-divider"></li>
              <li class="nav-link">
                <a href="#" class="nav-item dropdown-item" @click.prevent="logout">Salir</a>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      office: 'system/office',
      currentModule: 'modules/currentModule',      
      user: 'user/user',
    }),
  },
  methods: {
    ...mapActions({
      fetchOffice: 'system/fetchOffice',
    }),
    logout() {
      this.$router.replace('/login');
      axios.post('logout').then(res => {
        console.log(res);
        location.reload();
      });
    }
  }
}
</script>