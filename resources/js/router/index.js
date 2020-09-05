import Vue from 'vue'
import VueRouter from 'vue-router'

Vue.use(VueRouter);

const routes = [
  {
    path: '/deliveryman',
    name: 'deliveryman',
    component: require('@/views/deliveryman/Deliveryman').default
  },
  {
    path: '/deliveryman/create',
    name: 'deliveryman',
    component: require('@/views/deliveryman/Create').default
  },
  {
    path: '/deliveryman/:deliverymanId/edit',
    name: 'deliveryman',
    component: require('@/views/deliveryman/Edit').default
  },
  {
    path: '/:saleId/checkout',
    name: 'checkout',
    component: require('@/views/Checkout').default
  },
  {
    path: '/:saleId/checkoutDetails',
    name: 'checkout',
    component: require('@/views/CheckoutDetails').default
  },
  {
    path: '/checkout',
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
    path: '/store/:categoryId?',
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
    path: '/inventoriesAdmin',
    component: require('@/views/inventory/InventoriesAdmin').default
  },
  {
    path: '/inventories/:productId/detailsAdmin',
    component: require('@/views/inventory/DetailsAdmin').default
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
    path: '/customers/:customerId/edit',
    component: require('@/views/customer/Edit').default
  },
  {
    path: '/customers/:customerId/details',
    component: require('@/views/customer/Details').default
  },
  {
    path: '/salesAdmin',
    component: require('@/views/sale/SalesAdmin').default
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
    path: '/sales/deliveries',
    component: require('@/views/sale/Deliveries').default
  },
  {
    path: '/sales/dispatcheds',
    component: require('@/views/sale/Dispatcheds').default
  },
  {
    path: '/pos',
    component: require('@/views/pos/Pos').default
  },
  {
    path: '/posAdmin',
    component: require('@/views/pos/PosAdmin').default
  },
  {
    path: '/deletedReasons',
    component: require('@/views/deletedReason/DeletedReasons').default
  },
  {
    path: '/users',
    component: require('@/views/user/Users').default
  },
  {
    path: '/users/create',
    component: require('@/views/user/Create').default
  },
  {
    path: '/users/:userId/edit',
    component: require('@/views/user/Edit').default
  },
  {
    path: '/users/:userId/credentials',
    component: require('@/views/user/Credentials').default
  },
  {
    path: '/deletedReasons/create',
    component: require('@/views/deletedReason/Create').default
  },
  {
    path: '/deletedReasons/:deletedReasonId/edit',
    component: require('@/views/deletedReason/Edit').default
  },
]

const router = new VueRouter({
  mode: 'history',
  base: process.env.BASE_URL,
  routes
})

export default router
