export const user = {
  namespaced: true,
  state: {
    user: null,
  },
  mutations: {
    SET_USER(state, data) {
      state.user = data;  
    },
    // SET_LOGGED_IN(state, value) {
    //   state.user.loggedIn = value;
    // },
  },
  actions: {
    fetchUser({ commit }, user) {
      // commit("SET_LOGGED_IN", user !== null);
      if (user) {
        commit("SET_USER", user);
      } else {
        commit("SET_USER", null);
      }
    }
  },
  getters: {
    user(state) {
      return state.user;
    },
    // loggedIn(state) {
    //   return state.user.loggedIn;
    // }
  },
}