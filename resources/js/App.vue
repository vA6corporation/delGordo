<template>
  <div id="app">
    <topbar @toggle-sidebar="show = !show"/>
    <sidebar :show='show' @toggle-sidebar="show = !show"/>
    <vue-snotify/>
    <div class="container-fluid">
      <router-view/>
    </div>
  </div>
</template>

<script>
import Topbar from '@/components/Topbar'
import Sidebar from '@/components/Sidebar'
import { mapActions } from 'vuex';

export default {
  components: {
    Topbar,
    Sidebar,
  },
  mounted() {
    axios.get('users/session').then(res => {
      console.log(res);
      this.fetchUser(res.data.user);
      this.fetchCredentials(res.data.user.credentials);
      // console.log(res.data.user.credentials);
    }).catch(error => {
      console.log(error.response);
    });
  },
  data() {
    return {
      show: true,
    }
  },
  methods: {
    ...mapActions({
      fetchUser: 'user/fetchUser',
      fetchCredentials: 'user/fetchCredentials',
    }),
  }
}
</script>

<style scoped>
  .container-fluid {
    padding: 80px 30px 30px 280px;
  }

  @media only screen and (max-width: 768px) {
    .container-fluid {
    padding: 80px 30px 30px 30px;
  }
}
</style>