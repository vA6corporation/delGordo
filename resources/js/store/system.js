export const system = {
  namespaced: true,
  state: {
    start: false,
    office: null,
  },
  mutations: {
    SET_START(state, data) {
      state.start = data;
    },
    SET_OFFICE(state, data) {
      state.office = data;
    }
  },
  actions: {
    fetchStart({ commit }) {
      commit("SET_START", true);
    },
    fetchOffice({ commit }, office) {
      commit("SET_OFFICE", office);
    }
  },
  getters: {
    start(state) {
      return state.start;
    },
    office(state) {
      return state.office;
    }
  },
}