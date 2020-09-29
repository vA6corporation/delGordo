<template>
  <div id="app">
    <topbar @toggle-sidebar="show = !show"/>
    <sidebar :show='show' @toggle-sidebar="show = !show" v-if="office"/>
    <vue-snotify/>
    <div :class="[office ? 'container-fluid' : 'container']">
      <router-view/>
    </div>
  </div>
</template>

<script>
import Topbar from '@/components/Topbar'
import Sidebar from '@/components/Sidebar'
import { mapActions, mapGetters } from 'vuex';

export default {
  components: {
    Topbar,
    Sidebar,
  },
  mounted() {
    axios.get('users/session').then(res => {
      console.log(res);
      this.fetchUser(res.data.user);
      this.fetchCredentials(res.data.credentials);
      let office = res.data.office;
      // console.log(officeId);
      if (office) {
        this.fetchOffice(office);
      } else {
        this.$router.replace('/setOffice');
      }
    }).catch(error => {
      console.log(error.response);
    });
  },
  data() {
    return {
      show: true,
    }
  },
  computed: {
    ...mapGetters({
      office: 'system/office',
    }),
  },
  methods: {
    ...mapActions({
      fetchUser: 'user/fetchUser',
      fetchCredentials: 'user/fetchCredentials',
      fetchOffice: 'system/fetchOffice',
    }),
  }
}
</script>

<style scoped>
  .container {
    padding: 80px 30px 30px 30px;
  }

  .container-fluid {
    padding: 80px 30px 30px 280px;
  }

  @media only screen and (max-width: 768px) {
    .container-fluid {
      padding: 80px 30px 30px 30px;
    }
  }
</style>