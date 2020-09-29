<template>
  <div class="sidebar d-md-block" :class="{ 'd-none': show }">
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
        <template v-for="item in modules">
          <li class="nav-item" :key="item.name" v-if="isAuthorized(item.name)">
            <router-link class="nav-link" :to="item.path" @click.native="fetchCurrentModule(item); $emit('toggle-sidebar')">
              <feather :type="item.icon"/>
              {{ item.label }}
            </router-link>
          </li>
        </template>
      </ul>
    </div>
  </div>
</template>

<script>
import { mapGetters, mapActions } from 'vuex'

export default {
  props: ['show'],
  computed: {
    ...mapGetters({
      modules: 'modules/modules',
      isAuthorized: 'user/isAuthorized',
      // office: 'system/office',
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