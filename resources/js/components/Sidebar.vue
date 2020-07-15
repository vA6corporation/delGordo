<template>
  <div class="sidebar">
    <!--
      Tip 1: You can change the color of the sidebar using: data-color="blue | green | orange | red"
    -->
    <div class="sidebar-wrapper">
      <div class="logo">
        <a href="javascript:void(0)" class="simple-text logo-mini">
          DG
        </a>
        <a href="javascript:void(0)" class="simple-text logo-normal">
          Del Gordo
        </a>
      </div>
      <ul class="nav">
        <li class="nav-item" v-for="item in modules" :key="item.name">
          <router-link class="nav-link" :to="item.path" @click.native="fetchCurrentModule(item)">
            <feather :type="item.icon"/>
            {{ item.label }}
          </router-link>
        </li>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  computed: {
    ...mapGetters({
      modules: 'modules/modules'
    }),
  },
  methods: {
    ...mapActions({
      fetchCurrentModule: 'modules/fetchCurrentModule',
    }),
  }
}
</script>

<style>

</style>