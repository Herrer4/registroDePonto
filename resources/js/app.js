require('./bootstrap');

window.Vue = require('vue').default;

import VueSweetalert2 from 'vue-sweetalert2';
Vue.use(VueSweetalert2);

import Vuetify from 'vuetify';
Vue.use(Vuetify);

import VueMask from 'v-mask';
Vue.use(VueMask);

const files = require.context('./', true, /\.vue$/i)
files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

import Vue from 'vue';
import VueHtmlToPaper from 'vue-html-to-paper';

const options = {
    name: '_blank',
    specs: [
        'fullscreen=yes',
        'titlebar=yes',
        'scrollbars=yes'
    ],
    styles: [
        'https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css',
        'https://unpkg.com/kidlat-css/css/kidlat.css'
    ]
}

Vue.use(VueHtmlToPaper, options);

import VuetifyMoney from "vuetify-money";
Vue.use(VuetifyMoney);

import store from './store.js'
import router from './router.js'
const app = new Vue({
    vuetify: new Vuetify(),
    store,
    router,
}).$mount('#app');
