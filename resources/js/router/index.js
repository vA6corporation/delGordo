import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

const routes = [
  {
    path: '/:saleId/checkout',
    name: 'checkout',
    component: require('@/views/Checkout').default
  },
  {
    path: '/payment',
    name: 'payment',
    component: require('@/views/Payment').default
  },
  {
    path: '/shopping',
    name: 'shopping',
    component: require('@/views/Shopping').default
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
    component: require('@/views/product/Create').default
  },
  {
    path: '/products/:productId/edit',
    component: require('@/views/product/Edit').default
  },
  {
    path: '/categories',
    name: 'categories',
    component: require('@/views/category/Categories').default
  },
  {
    path: '/categories/create',
    component: require('@/views/category/Create').default
  },
  {
    path: '/categories/:categoryId/edit',
    component: require('@/views/category/Edit').default
  },
  {
    path: '/subCategories',
    name: 'categories',
    component: require('@/views/subCategory/SubCategories').default
  },
  {
    path: '/subCategories/create',
    component: require('@/views/subCategory/Create').default
  },
  {
    path: '/subCategories/:subCategoryId/edit',
    component: require('@/views/subCategory/Edit').default
  },
  {
    path: '/deliveries',
    component: require('@/views/delivery/Deliveries').default
  },
  {
    path: '/deliveries/create',
    component: require('@/views/delivery/Create').default
  },
  {
    path: '/deliveries/:deliveryId/edit',
    component: require('@/views/delivery/Edit').default
  },
  {
    path: '/paymentMethods',
    component: require('@/views/paymentMethod/PaymentMethods').default
  },
  {
    path: '/paymentMethods/create',
    component: require('@/views/paymentMethod/Create').default
  },
  {
    path: '/paymentMethods/:paymentMethodId/edit',
    component: require('@/views/paymentMethod/Edit').default
  },
  {
    path: '/inventories',
    component: require('@/views/inventory/Inventories').default
  },
  {
    path: '/inventories/create',
    component: require('@/views/subCategory/Edit').default
  },
  {
    path: '/inventories/:productId/create',
    component: require('@/views/inventory/Create').default
  },
  {
    path: '/inventories/:productId/details',
    component: require('@/views/inventory/Details').default
  },
  {
    path: '/customers',
    component: require('@/views/customer/Customers').default
  },
  {
    path: '/sales',
    component: require('@/views/sale/Sales').default
  },
  {
    path: '/sales/:saleId/details',
    component: require('@/views/sale/Details').default
  },
  {
    path: '/pos',
    component: require('@/views/pos/Pos').default
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
