<template>
  <div id="app">
    <topbar/>
    <sidebar/>
    <vue-snotify/>
    <div class="container-fluid">
      <router-view/>
    </div>
  </div>
</template>

<script>
import Topbar from '@/components/Topbar'
import Sidebar from '@/components/Sidebar'

export default {
  components: {
    Topbar,
    Sidebar,
  },
  mounted() {

  }
}
</script>

<style scoped>
  .container-fluid {
    padding: 80px 30px 30px 280px;
  }
</style>