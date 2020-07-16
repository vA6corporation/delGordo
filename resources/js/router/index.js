import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter)

  const routes = [
    {
      path: '/payment',
      name: 'payment',
      component: require('@/views/Payment').default
    },
    {
      path: '/store',
      name: 'store',
      component: require('@/views/Store').default
    },
    {
      path: '/home',
      name: 'home',
      component: require('@/views/Home').default
    },
    {
      path: '/products',
      name: 'products',
      component: require('@/views/product/Products').default
    },
    {
      path: '/products/create',
      // name: 'products',
      component: require('@/views/product/Create').default
    },
    {
      path: '/products/:productId/edit',
      // name: 'products',
      component: require('@/views/product/Edit').default
    },
    {
      path: '/categories',
      name: 'categories',
      component: require('@/views/category/Categories').default
    },
    {
      path: '/categories/create',
      // name: 'products',
      component: require('@/views/category/Create').default
    },
  ]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
