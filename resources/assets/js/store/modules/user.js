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
    age: null,
    gender: null,
    city: null,
    simple_intro: null,
    school: null,
    major: null,
    introduction: null
}

const getters = {
    getToken: state => state.token
}

const actions = {
    login ({commit},userForm) {
        axios.post('api/login',{
            email: userForm.email,
            password: userForm.password
        }).then(function (response) {
            let data = response.data;
            if (data.status == 0) {
                commit(types.LOGIN_FAIL,data);
            } else {
                commit(types.LOGIN_SUCCESS,data);
                router.push('home');
            }
        })

    },
    signup ({commit},userForm) {
        axios.post('api/register',{
            email: userForm.email,
            name: userForm.name,
            password: userForm.password,
            password_confirmation: userForm.conformPassword
        }).then(function (response) {
            let data = response.data;
            if (data.status == 0) commit(types.REGISTER_FAIL,data);
            commit(types.REGISTER_SUCCESS,data);
            router.push('home');
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
        state.age = data.user.age;
        state.gender = data.user.gender;
        state.city = data.user.city;
        state.simple_intro = data.user.simple_intro;
        state.school = data.user.school;
        state.major = data.user.major;
        state.introduction = data.user.introduction;

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
        state.age = data.user.age;
        state.gender = data.user.gender;
        state.city = data.user.city;
        state.simple_intro = data.user.simple_intro;
        state.school = data.user.school;
        state.major = data.user.major;
        state.introduction = data.user.introduction;

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
        state.age = data.user.age;
        state.gender = data.user.gender;
        state.city = data.user.city;
        state.simple_intro = data.user.simple_intro;
        state.school = data.user.school;
        state.major = data.user.major;
        state.introduction = data.user.introduction;
    }
}

export default {
    state,
    getters,
    actions,
    mutations
}
