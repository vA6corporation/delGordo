import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

  const routes = [
    {
      path: '/store',
      name: 'store',
      component: () => import('@/views/Store')
    },
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
    {
      path: '/products/:productId/edit',
      // name: 'products',
      component: () => import('@/views/product/Edit')
    },
    {
      path: '/categories',
      name: 'categories',
      component: () => import('@/views/category/Categories')
    },
    {
      path: '/categories/create',
      // name: 'products',
      component: () => import('@/views/category/Create')
    },
  ]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
