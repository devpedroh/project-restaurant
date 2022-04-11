import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '../views/Home.vue'
import PanelHome from '../views/panel/Home.vue'
import PanelLogin from '../views/panel/auth/Login.vue'

Vue.use(VueRouter)

const routes = [
  {
    path: '/',
    name: 'Home',
    component: Home
  },
  {
    path: '/panel',
    name: 'PanelHome',
    component: PanelHome
  },
  {
    path: '/panel/login',
    name: 'PanelLogin',
    component: PanelLogin
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
