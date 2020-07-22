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

Vue.mixin({
    methods: {
        formatTime(date) {
            date = new Date(date);
            var ampm = 'AM';
            var hours = date.getHours();
            var minutes = date.getMinutes();
            if (hours >= 12) {
              ampm = 'PM';
            }
            if (hours >= 13) {
              hours -= 12;
            }
            // Formatear 2 dijitos
            if (hours < 10) {
              hours = `0${hours}`;
            }
            if (minutes < 10) {
              minutes = `0${minutes}`;
            }
            return `${hours} : ${minutes} ${ampm}`;
        },
        formatDate(date) {
            var d = new Date(date);
            var month = '' + (d.getMonth() + 1);
            var day = '' + d.getDate();
            var year = d.getFullYear();
            
            if (month.length < 2) month = '0' + month;
            if (day.length < 2) day = '0' + day;
          
            return [year, month, day].join('-');
        },
    }
});

new Vue({
    router,
    store,
    render: function(h) {
        if (this.$route.path == '/store' || this.$route.path == '/payment' || this.$route.path == '/shopping') {
            return h(StoreApp);
        } else {
            return h(App); 
        }
    },
}).$mount('#app');
  