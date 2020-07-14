export const system = {
  namespaced: true,
  state: {
    start: false,
  },
  mutations: {
    SET_START(state, data) {
      state.start = data;
    },
  },
  actions: {
    fetchStart({ commit }) {
      commit("SET_START", true);
    }
  },
  getters: {
    start(state) {
      return state.start;
    },
  },
}