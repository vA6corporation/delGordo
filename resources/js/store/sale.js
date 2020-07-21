export const sale = {
  namespaced: true,
  state: {
    products: [],
  },
  mutations: {
    ADD_PRODUCT(state, product) {
      var index = state.products.findIndex(e => e.id == product.id);
      if (index >= 0) {
        product.counter = state.products[index].counter;
        state.products.splice(index, 1, product);
      } else {
        product.counter = product.counter || 1;
        state.products.push(product);
      }
    },
    REMOVE_PRODUCT(state, product) {
      var index = state.products.findIndex(e => e.id == product.id);
      state.products.splice(index, 1);
      // if (index >= 0) {
      //   product.counter = state.products[index].counter;
      // } else {
      //   product.counter = product.counter || 1;
      //   state.products.push(product);
      // }
    },
    PLUS_PRODUCT(state, id) {
      var product = state.products.find(e => e.id == id);
      product.counter++;
    },
    MINUS_PRODUCT(state, id) {
      var product = state.products.find(e => e.id == id);
      if (product.counter > 0) {
        product.counter--;
      }
    },
  },
  actions: {
    addProduct({ commit }, product) {
      commit("ADD_PRODUCT", product);
    },
    removeProduct({ commit }, product) {
      commit("REMOVE_PRODUCT", product);
    },
    plusProduct({ commit }, product) {
      commit("ADD_PRODUCT", product);
      commit("PLUS_PRODUCT", product.id);
    },
    minusProduct({ commit }, product) {
      commit("ADD_PRODUCT", product);
      commit("MINUS_PRODUCT", product.id);
    },
  },
  getters: {
    totalProducts(state) {
      return state.products.map(e => e.sale_price * e.counter).reduce((a, b) => a + b, 0);
    },
    products(state) {
      return state.products;
    }
  },
}