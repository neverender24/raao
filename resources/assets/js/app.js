require('./bootstrap');

window.Vue = require('vue');

import Vue from 'vue'
import App from './App.vue'
import router from "./router"
import { VuejsDatatableFactory } from 'vuejs-datatable';

Vue.use( VuejsDatatableFactory );

const app = new Vue({
    el: "#app",
    template: "<app></app>",
    components: {
        App,
    },
    router 
}); 

 