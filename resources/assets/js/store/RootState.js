
const state = {
    works: {
        data:[],
        current_page:1,
        next_page_url:null,
        from:null,
        to:null,
        last_page:null,
        per_page:null,
        total:null,
        path:null,
        last_page_url:null,
        pre_page_url:null,
        first_page_url:null
    },
    params: {
        query: null,
        location:[],
        city:'',
        district:'',
        tag:null,
        gender:null,
        pay_time: null,
        pay_type: null,
        day:null,
        settlement_type: null,
        has_commission: '',
        need_interview: '',
        sort:[],
        sortBy:null,
        page:0
    },
    HotTags: [],
    tags:[]
}

export default state;