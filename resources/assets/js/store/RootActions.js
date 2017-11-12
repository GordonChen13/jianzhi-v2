import * as types from './mutation-types';
import axios from 'axios';

const actions = {
    GetWorks ( {commit},params) {
        axios({
            url: '/api/works',
            method: 'get',
            params: {
                search: params.search,
                orderBy: params.sort[0],
                order:params.sort[1],
                tag:params.tag,
                city: params.location[0],
                district: params.location[1],
                gender: params.gender,
                pay_time: params.pay_time,
                pay_type: params.pay_type,
                day:params.day,
                settlement_type: params.settlement_type,
                has_commission: params.has_commission,
                need_interview:params.need_interview,
                page:params.page
            }
        }).then(function (response) {
            let data = response.data;
            console.log(response);
            if (data.status == 0) {
                alert(data.msg);
            } else {
                commit(types.STORE_WORKS,response.data.works);
            }
        }).catch(function (error) {
            console.log(error);
        });
    },
    GetMoreWorks ( {commit},params) {
        axios({
            url: '/api/works',
            method: 'get',
            params: {
                search: params.search,
                orderBy: params.sort[0],
                order:params.sort[1],
                tag:params.tag,
                city: params.location[0],
                district: params.location[1],
                gender: params.gender,
                pay_time: params.pay_time,
                pay_type: params.pay_type,
                day:params.day,
                settlement_type: params.settlement_type,
                has_commission: params.has_commission,
                need_interview:params.need_interview,
                page:params.page
            }
        }).then(function (response) {
            let data = response.data;
            if (data.status == 0) {
                alert(data.msg);
            } else {
                commit(types.STORE_MORE_WORKS,response.data.works);
            }
        }).catch(function (error) {
            console.log(error);
        });
    },
    GetTags ( {commit},query) {
        axios.get('/api/tags?search='+query).then(function (response) {
            commit(types.STORE_TAGS,response.data.tags);
        });
    },
    GetHotTags ( {commit} ) {
        axios({
            url:'/api/tags',
            params: {
                orderBy:'hot'
            }
        }).then(function (response) {
            commit(types.STORE_HOT_TAGS,response.data.tags);
        });
    }


}

export default actions;