/**
 * Created by chen on 17-3-7.
 */
import Vue from 'vue'
import Vuex from 'vuex'
import actions from './RootActions'
import mutations from './RootMutations'
import state from './RootState'
import * as getters from './getters'
import user from './modules/user'
import work from './modules/work'
import chat from './modules/chat'

Vue.use(Vuex)

export default new Vuex.Store({
    state,
    actions,
    mutations,
    getters,
    modules: {
        user,
        work,
        chat
    }
})