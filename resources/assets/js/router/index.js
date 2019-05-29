import Vue from 'vue'
import VueRouter from 'vue-router'

import DashboardComponent from "./../pages/dashboard"
import Raaohs from "../admin/raaohs.vue"

Vue.use(VueRouter)

const routes = [
    {
        path: '/dashboard',
        name: 'dashboard',
        component: DashboardComponent,
    },
    {
        path: '/raaohs',
        name: 'raaohs',
        component: Raaohs,
    }
];

const router = new VueRouter({
    routes // short for `routes: routes`
});

export default router