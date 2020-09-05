export const user = {
  namespaced: true,
  state: {
    user: null,
    credentials: {},
  },
  mutations: {
    SET_USER(state, data) {
      state.user = data;  
    },
    SET_CREDENTIALS(state, data) {
      state.credentials = data;  
    },
    // SET_LOGGED_IN(state, value) {
    //   state.user.loggedIn = value;
    // },
  },
  actions: {
    fetchCredentials({ commit }, credentials) {
      commit("SET_CREDENTIALS", credentials);
    },
    fetchUser({ commit }, user) {
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
    credentials() {
      return state.credentials;
    },
    isAuthorized: ({ credentials }) => (modulo) => {
      // console.log(modulo);
      if (!credentials) {
        return true;
      }
      if (modulo == undefined) {
        return true;
      }

      // console.log(credentials);

      if (credentials[modulo]) {
        return true;
      } else {
        return false;
      }
    },
  },
}