<template>
  <nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent">
    <div class="container-fluid">
      <div class="navbar-wrapper">
        <div class="navbar-toggle d-inline">
          <button type="button" class="navbar-toggler">
            <span class="navbar-toggler-bar bar1"></span>
            <span class="navbar-toggler-bar bar2"></span>
            <span class="navbar-toggler-bar bar3"></span>
          </button>
        </div>
        <a class="navbar-brand" href="#">Dashboard</a>
      </div>
      <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-bar navbar-kebab"></span>
        <span class="navbar-toggler-bar navbar-kebab"></span>
        <span class="navbar-toggler-bar navbar-kebab"></span>
      </button>
      <div class="collapse navbar-collapse" id="navigation">
        <ul class="navbar-nav ml-auto">
          <!-- <li class="dropdown nav-item">
            <a href="#" class="nav-link" data-toggle="dropdown">
              <div class="notification d-none d-lg-block d-xl-block"></div>
              <feather type='activity'/>
              <p class="d-lg-none">
                Notifications
              </p>
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="nav-link"><a href="#" class="nav-item dropdown-item">Mike John responded to your email</a></li>
              <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">You have 5 more tasks</a></li>
              <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Your friend Michael is in town</a></li>
              <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another notification</a></li>
              <li class="nav-link"><a href="javascript:void(0)" class="nav-item dropdown-item">Another one</a></li>
            </ul>
          </li> -->
          <li class="dropdown nav-item">
            <a href="#" class="dropdown-toggle nav-link text-white" data-toggle="dropdown" aria-expanded="false">
              <img class="rounded-circle" style="width: 2.5rem" src="@/assets/img/user.svg" alt="Profile Photo">
            </a>
            <ul class="dropdown-menu dropdown-menu-right">
              <li class="nav-link">
                <a href="#" class="nav-item dropdown-item">Perfil</a>
              </li>
              <li class="nav-link">
                <a href="#" class="nav-item dropdown-item">Ajustes</a>
              </li>
              <li class="dropdown-divider"></li>
              <li class="nav-link">
                <a href="#" class="nav-item dropdown-item" @click.prevent="logout">Salir</a>
              </li>
            </ul>
          </li>
          <li class="separator d-lg-none"></li>
        </ul>
      </div>
    </div>
  </nav>
</template>

<script>
export default {
  methods: {
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