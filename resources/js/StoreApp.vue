<template>
  <div id="app">
    <topbar/>
    <vue-snotify/>
    <div class="container-fluid">
      <router-view/>
    </div>
  </div>
</template>

<script>
import Topbar from '@/components/TopbarStore'
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