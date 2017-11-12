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
    },
    [types.STORE_MORE_WORKS] (state,works) {
        state.works.current_page = works.current_page,
        state.works.next_page_url = works.next_page_url,
        state.works.from = works.from,
        state.works.to = works.to,
        state.works.last_page = works.last_page,
        state.works.per_page = works.per_page,
        state.works.total = works.total,
        state.works.path = works.path,
        state.works.last_page_url = works.last_page_url,
        state.works.pre_page_url = works.pre_page_url,
        state.works.first_page_url = works.first_page_url,
        state.works.data.push(...works.data);
    }
}

export default mutations;