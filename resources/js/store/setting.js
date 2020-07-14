export const setting = {
  namespaced: true,
  state: {
    setting: {},
  },
  mutations: {
    SET_SETTING(state, data) {
      state.setting = Object.assign({
        showSidebar: true,
      }, data);  
    },
  },
  actions: {
    fetchSetting({ commit }, setting) {
      commit("SET_SETTING", setting);
    }
  },
  getters: {
    setting(state) {
      return state.setting;
    },
  },
}