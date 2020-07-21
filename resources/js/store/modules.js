export const modules = {
  namespaced: true,
  state: {
    currentModule: { label: 'Dashboard' },
    modules: [
      { label: 'Dashboard', name: 'dashboard', path: '/dashboard', icon: 'pie-chart' },
      { label: 'Productos', name: 'products', path: '/products', icon: 'package' },
      { label: 'Categorias', name: 'categories', path: '/categories', icon: 'package' },
      { label: 'Sub Categorias', name: 'subCategories', path: '/subCategories', icon: 'package' },
      { label: 'Inventario', name: 'inventories', path: '/inventories', icon: 'package' },
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
