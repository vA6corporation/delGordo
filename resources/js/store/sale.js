export const sale = {
  namespaced: true,
  state: {
    options: {},
    products: [],
  },
  mutations: {
    SET_SALE(state, sale) {
      state.options = sale;
    },
    ADD_PRODUCT(state, product) {
      var index = state.products.findIndex(e => e.id == product.id);
      if (index >= 0) {
        // product.counter = state.products[index].counter + (product.unit_code == 'NIU' ? 1 : 0.5);
        // state.products.splice(index, 1, Object.assign({}, product));
      } else {
        product.counter = product.counter || (product.unit_code == 'NIU' ? 1 : 0.5);
        state.products.push(Object.assign({}, product));
      }
    },
    // ADD_PRODUCT_STORE(state, product) {
    //   var index = state.products.findIndex(e => e.id == product.id);
    //   if (index >= 0) {
    //     // product.counter = state.products[index].counter + (product.unit_code == 'NIU' ? 1 : 0.5);
    //     // state.products.splice(index, 1, Object.assign({}, product));
    //   } else {
    //     // product.counter = product.counter || (product.unit_code == 'NIU' ? 1 : 0.5);
    //     state.products.push(Object.assign({}, product));
    //   }
    // },
    PLUS_PRODUCT(state, product) {
      console.log(product);
      var index = state.products.findIndex(e => e.id == product.id);
      if (index >= 0) {
        product.counter = state.products[index].counter + (product.unit_code == 'NIU' ? 1 : 0.5);
        state.products.splice(index, 1, Object.assign({}, product));
      } else {
        // product.counter = product.counter || (product.unit_code == 'NIU' ? 1 : 0.5);
        // state.products.push(Object.assign({}, product));
      }
    },
    MINUS_PRODUCT(state, product) {
      var index = state.products.findIndex(e => e.id == product.id);
      if (index >= 0 && state.products[index].counter > (product.unit_code == 'NIU' ? 1 : 0.5)) {
        product.counter = state.products[index].counter - (product.unit_code == 'NIU' ? 1 : 0.5);
        state.products.splice(index, 1, Object.assign({}, product));
      }
    },
    REMOVE_PRODUCT(state, product) {
      var index = state.products.findIndex(e => e.id == product.id);
      state.products.splice(index, 1);
    },
    REMOVE_ALL_PRODUCTS(state) {
      state.products = [];
    },
    REMOVE_PRODUCT(state, product) {
      var index = state.products.findIndex(e => e.id == product.id);
      state.products.splice(index, 1);
    },
  },
  actions: {
    setSale({ commit }, sale) {
      commit("SET_SALE", sale);
    },
    addProduct({ commit }, product) {
      commit("ADD_PRODUCT", product);
    },
    addProductStore({ commit }, product) {
      commit("ADD_PRODUCT_STORE", product);
    },
    plusProduct({ commit }, product) {
      commit("PLUS_PRODUCT", product);
    },
    minusProduct({ commit }, product) {
      commit("MINUS_PRODUCT", product);
    },
    removeProduct({ commit }, product) {
      commit("REMOVE_PRODUCT", product);
    },
    removeAllProducts({ commit }) {
      commit("REMOVE_ALL_PRODUCTS");
    },
    removeProduct({ commit }, product) {
      commit("REMOVE_PRODUCT", product);
    },
  },
  getters: {
    totalProducts(state) {
      return state.products.map(product => { 
        if (product.picked.length) {
          return product.picked.map(e => e.weight).reduce((a, b) => a + b, 0) * product.sale_price;
        }
        var total = 0;
        var totalCollection = [];
        for (const inventory of product.inventory) {
          if (product.counter >= (total + inventory.weight)) {
            total += inventory.weight;
            totalCollection.push(inventory);
          }
        }
        if (!totalCollection.length && product.inventory.length) {
          totalCollection.push(product.inventory.slice(-1)[0]);
        }
        return totalCollection.map(e => e.weight).reduce((a, b) => a + b, 0) * product.sale_price;
      }).reduce((a, b) => a + b, 0);
    },
    products(state) {
      return state.products;
    },
    getSale(state) {
      return state.options;
    }
  },
}