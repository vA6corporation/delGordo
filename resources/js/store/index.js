import Vue from 'vue'
import Vuex from 'vuex'

import { user } from './user'
import { sale } from './sale'
import { setting } from './setting'
import { modules } from './modules'
import { system } from './system'

Vue.use(Vuex)

export default new Vuex.Store({
  state: {
  },
  mutations: {
  },
  actions: {
  },
  modules: {
    sale,
    user,
    setting,
    modules,
    system,
  }
})
