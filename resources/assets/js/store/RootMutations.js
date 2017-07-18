import * as types from './mutation-types'

const mutations = {
    [types.STORE_HOT_TAGS] (state,tags) {
        state.HotTags = tags;
        state.tags = tags;
    },
    [types.STORE_TAGS] (state,tags) {
        state.tags = tags;
    },
    [types.STORE_WORKS] (state,works) {
        state.works = works;
    }
}

export default mutations;