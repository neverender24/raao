import Vue from 'vue'
import VueRouter from 'vue-router'

import DashboardComponent from "./../pages/dashboard"
import Raaohs from "../admin/raaohs.vue"
import Accounts from "../admin/accounts.vue"
import Axios from 'axios'

Vue.use(VueRouter)

const routes = [
    {
        path: '/',
        name: 'dashboard',
        component: Raaohs,
    },
    {
        path: '/raaohs',
        name: 'raaohs',
        component: Raaohs,
    },
    {
        path: '/accounts',
        name: 'accounts',
        component: Accounts,
    },
];

const router = new VueRouter({
    routes // short for `routes: routes`
});

export default router