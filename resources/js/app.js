require('./bootstrap');
import Vue from 'vue'
import App from './App'
import StoreApp from './StoreApp'
import router from './router'
import store from './store'
import VueFeather from 'vue-feather'
import Snotify from 'vue-snotify'

Vue.component(VueFeather.name, VueFeather);

Vue.use(Snotify, { toast: { timeout: 4000 } });

new Vue({
    router,
    store,
    render: function(h) {
        if (this.$route.path == '/store') {
            return h(StoreApp);
        } else {
            return h(App); 
        }
    },
}).$mount('#app');
  