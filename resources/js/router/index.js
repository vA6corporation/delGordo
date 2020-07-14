import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

  const routes = [
    {
      path: '/home',
      name: 'home',
      component: () => import('@/views/Home')
    },
    {
      path: '/products',
      name: 'products',
      component: () => import('@/views/product/Products')
    },
    {
      path: '/products/create',
      // name: 'products',
      component: () => import('@/views/product/Create')
    },
  ]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
