export const modules = {
  namespaced: true,
  state: {
    currentModule: { label: 'Dashboard' },
    modules: [
      { label: 'Dashboard', name: 'dashboard', path: '/dashboard', icon: 'pie-chart' },
      { label: 'Punto de Venta', name: 'pos', path: '/pos', icon: 'monitor' },
      { label: 'Productos', name: 'products', path: '/products', icon: 'package' },
      { label: 'Categorias', name: 'categories', path: '/categories', icon: 'package' },
      { label: 'Sub Categorias', name: 'subCategories', path: '/subCategories', icon: 'package' },
      { label: 'Inventario', name: 'inventories', path: '/inventories', icon: 'clipboard' },
      { label: 'Clientes', name: 'customers', path: '/customers', icon: 'users' },
      { label: 'Ventas', name: 'sales', path: '/sales', icon: 'check' },
      { label: 'Delivery', name: 'deliveries', path: '/deliveries', icon: 'map-pin' },
      { label: 'Metodos de pago', name: 'paymentMethods', path: '/paymentMethods', icon: 'map-pin' },
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
