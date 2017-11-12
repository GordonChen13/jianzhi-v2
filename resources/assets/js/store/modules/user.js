/**
 * Created by chen on 17-3-14.
 */

// import Vue from 'vue';
import * as types from '../mutation-types'
import axios from 'axios';
import routes from '../../routes';
import VueRouter from 'vue-router'

const router = new VueRouter({
    routes
});

const state = {
    id: null,
    showAlert: null,
    token: null,
    msg: null,
    name: null,
    email: null,
    mobile: null,
    pic_path: null,
    cover_path: null,
    age: null,
    gender: null,
    city: null,
    simple_intro: null,
    school: null,
    major: null,
    introduction: null,
    un_read_messages_count: null
}

const getters = {
    getToken: state => state.token
}

const actions = {
    login ({commit},userForm) {
        return new Promise(function (resolve, reject) {
            axios.post('api/login',{
                email: userForm.email,
                password: userForm.password
            }).then(function (response) {
                let data = response.data;
                if (data.status == 0) {
                    commit(types.LOGIN_FAIL,data);
                    reject(data);
                } else {
                    commit(types.LOGIN_SUCCESS,data);
                    axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
                    resolve(data);
                }
            })

        })

    },
    signup ({commit},userForm) {
        return new Promise(function (resolve, reject) {
            axios.post('api/register',{
                email: userForm.email,
                name: userForm.name,
                password: userForm.password,
                password_confirmation: userForm.conformPassword
            }).then(function (response) {
                let data = response.data;
                if (data.status == 0) {
                    commit(types.REGISTER_FAIL,data);
                    reject(data);
                } else {
                    commit(types.REGISTER_SUCCESS, data);
                    resolve(data);
                    axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
                }
            })
        })

    }
}

const mutations = {
    [types.LOGIN_FAIL](state,data){
        state.showAlert =1;
        state.msg = data.msg;
    },
    [types.LOGIN_SUCCESS](state,data){
        state.status = data.status;
        state.token = data.token;
        state.id = data.user.id;
        state.name = data.user.name;
        state.email = data.user.email;
        state.mobile = data.user.mobile;
        state.pic_path = data.user.pic_path;
        state.cover_path = data.user.cover_path;
        state.age = data.user.age;
        state.gender = data.user.gender;
        state.city = data.user.city;
        state.simple_intro = data.user.simple_intro;
        state.school = data.user.school;
        state.major = data.user.major;
        state.introduction = data.user.introduction;
        state.un_read_messages_count = data.user.un_read_messages_count;

        localStorage.token = data.token;
        localStorage.user = JSON.stringify(data.user);
    },
    [types.REGISTER_SUCCESS](state,data){
        state.status = data.status;
        state.token = data.token;
        state.name = data.user.name;
        state.email = data.user.email;
        state.mobile = data.user.mobile;
        state.pic_path = data.user.pic_path;
        state.cover_path = data.user.cover_path;
        state.age = data.user.age;
        state.gender = data.user.gender;
        state.city = data.user.city;
        state.simple_intro = data.user.simple_intro;
        state.school = data.user.school;
        state.major = data.user.major;
        state.introduction = data.user.introduction;
        state.un_read_messages_count = data.user.un_read_messages_count;

        localStorage.token = data.token;
        localStorage.user = JSON.stringify(data.user);
    },
    [types.REGISTER_FAIL](state,data){
        state.showAlert =1;
        state.msg = data.msg;
    },
    [types.LOAD_USER_FROM_LOCAL](state,data) {
        state.status = data.status;
        state.token = data.token;
        state.name = data.user.name;
        state.email = data.user.email;
        state.mobile = data.user.mobile;
        state.pic_path = data.user.pic_path;
        state.cover_path = data.user.cover_path;
        state.age = data.user.age;
        state.gender = data.user.gender;
        state.city = data.user.city;
        state.simple_intro = data.user.simple_intro;
        state.school = data.user.school;
        state.major = data.user.major;
        state.introduction = data.user.introduction;
    },
    [types.LOGOUT](state){
        state.id = null,
        state.showAlert = null,
        state.token = null,
        state.msg = null,
        state.name = null,
        state.email = null,
        state.mobile = null,
        state.pic_path = null,
        state.cover_path = null,
        state.age = null,
        state.gender = null,
        state.city = null,
        state.simple_intro = null,
        state.school = null,
        state.major = null,
        state.introduction = null

        localStorage.removeItem("token");
        localStorage.removeItem("user");
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
