export const modules = {
  namespaced: true,
  state: {
    currentModule: { label: 'Dashboard' },
    modules: [
      { label: 'Dashboard', name: 'dashboard', path: '/dashboard', icon: 'pie-chart' },
      { label: 'Punto de Venta', name: 'pos', path: '/pos', icon: 'monitor' },
      { label: 'Pos(Admin)', name: 'posAdmin', path: '/posAdmin', icon: 'monitor' },
      { label: 'Productos', name: 'products', path: '/products', icon: 'package' },
      { label: 'Categorias', name: 'categories', path: '/categories', icon: 'package' },
      { label: 'Sub Categorias', name: 'subCategories', path: '/subCategories', icon: 'package' },
      { label: 'Inventario', name: 'inventories', path: '/inventories', icon: 'clipboard' },
      { label: 'Inventario(Admin)', name: 'inventoriesAdmin', path: '/inventoriesAdmin', icon: 'clipboard' },
      { label: 'Clientes', name: 'customers', path: '/customers', icon: 'users' },
      { label: 'Ventas', name: 'sales', path: '/sales', icon: 'check' },
      { label: 'Ventas(Admin)', name: 'salesAdmin', path: '/salesAdmin', icon: 'check' },
      { label: 'Despacho', name: 'dispatchedsSale', path: '/sales/dispatcheds', icon: 'map-pin' },
      { label: 'Delivery', name: 'deliveriesSale', path: '/sales/deliveries', icon: 'map-pin' },
      { label: 'Usuarios', name: 'users', path: '/users', icon: 'users' },
      { label: 'Sucursales', name: 'offices', path: '/offices', icon: 'home' },
      // { label: 'Repartidores', name: 'deliveryman', path: '/deliveryman', icon: 'user-check' },
      { label: 'L. de envio', name: 'deliveries', path: '/deliveries', icon: 'map-pin' },
      { label: 'M. de pago', name: 'paymentMethods', path: '/paymentMethods', icon: 'map-pin' },
      { label: 'M. de anulacion', name: 'deletedReasons', path: '/deletedReasons', icon: 'star' },
    ]
  },
  mutations: {
    SET_CURRENT_MODULE(state, data) {
      state.currentModule = data;
    }
  },
  actions: {
    fetchCurrentModule({ commit }, currentModule) {
      commit("SET_CURRENT_MODULE", currentModule);
    }
  },
  getters: {
    modules(state) {
      return state.modules;
    },
    currentModule(state) {
      return state.currentModule;
    }
  }
}
