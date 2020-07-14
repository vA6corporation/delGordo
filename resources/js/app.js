require('./bootstrap');
import Vue from 'vue'
import App from './App'
import router from './router'
import store from './store'
import VueFeather from 'vue-feather'

Vue.component(VueFeather.name, VueFeather);
// window.Vue = require('vue');

new Vue({
    router,
    store,
    render: h => h(App),
}).$mount('#app');
  