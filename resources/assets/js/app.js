import Vue from 'vue'
import Vuex from 'vuex'
import ElementUI from 'element-ui'
import 'element-ui/lib/theme-default/index.css'
import 'font-awesome/css/font-awesome.css'
import moment from 'moment';
import axios from 'axios'
import lodash from 'lodash';
import * as types from './store/mutation-types'
import * as format from './format'
// import App from './App.vue'
import store from './store/index'; // vuex 数据存储所需对象
import routes from './routes';   // 路由配置文件
import VueRouter from 'vue-router'

Object.defineProperty(Vue.prototype, '$axios', { value: axios });
Object.defineProperty(Vue.prototype, '$_', { value: lodash });
Object.defineProperty(Vue.prototype, '$moment', { value: moment });

Vue.use(VueRouter)
Vue.use(Vuex)
Vue.use(ElementUI)

if (store.state.user.token) {

}

// 实例化路由
const router = new VueRouter({
    routes
});

function refreshToken() {
    if (localStorage.token) {
        axios.get('api/refreshtoken', {
            headers: {'Authorization': 'Bearer' + localStorage.token}
        }).then(function (response) {
            let data = response.data;
            if (data.status == 1) {
                store.commit(types.LOGIN_SUCCESS, data);
                axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
            }  else {
            router.push({path: '/login'});}
        })
    }
}

// 实例化 Vue
var vm = new Vue({
    store,
    router,
    mounted:refreshToken()
}).$mount('#app');
