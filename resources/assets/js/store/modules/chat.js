
import * as types from '../mutation-types'
import axios from 'axios';
import _ from 'lodash';

const state = {
    messages:[],
    userLists:[],
    activeUser:null,
}

const getters = {

}

const actions = {

    //获取用户的聊天信息
    getMessages ( {commit} ) {
        let that = this;
        return new Promise( (resolve,reject) => {
            axios.get('/api/user/chat').then( (res) => {
                if (res.data.status == 1) {
                    commit(types.STORE_USERLISTS,res.data.userLists);
                    commit(types.STORE_MESSAGES,res.data.messages);
                    if (_.isEmpty(state.activeUser)) {
                        if (res.data.userLists.length > 0) {
                            commit(types.CHANGE_ACTIVE_USER, res.data.userLists[0]);
                        }
                    }
                    resolve(res.data);
                } else {
                    that.$message.error(res.data.msg);
                    reject(res.data)
                }
            }).catch( (err) => {
                reject(err);
            })
        })
    },

    //发送新的信息
    postNewMessage ( {commit,state}, message) {
        let that = this;
        return new Promise( (resolve,reject) => {
            axios.post('/api/user/chat',{
                text: message,
                target_id: state.activeUser.id
            }).then( (res) => {
                if (res.data.status ==1) {
                    let message = res.data.message;
                    commit(types.UPDATE_MESSAGES,message);
                    resolve(res.data);
                } else {
                    that.$message.error('消息发送失败，请稍后再试');
                    reject(res.data)
                }
            }).catch( (err) => {
                console.log('PostNewMessageError',err);
                reject(err);
            })
        })

    },

}

const mutations = {
    [types.STORE_MESSAGES] (state,messages) {
        state.messages =messages;
    },

    [types.STORE_USERLISTS] (state,users) {
        state.userLists =users;
    },

    [types.CHANGE_ACTIVE_USER] (state,user) {
        state.activeUser =user;
        if (user) {
            let index = _.find(state.userLists,function (user) {
                return user.id === user.id;
            });
            if (index != undefined) {
                let lists = state.userLists.filter(function (item) {
                    return item.id != user.id;
                });
                lists.push(user);
                state.userLists = lists;
            } else {
                state.userLists.push(user);
                if (state.messages[user.id] == undefined ) {
                    state.messages[user.id] = [];
                }
            }
        }
    },

    [types.UPDATE_MESSAGES] (state,message) {
        if (state.messages[state.activeUser.id] == undefined) {
            state.messages[state.activeUser.id] = [];
            state.messages[state.activeUser.id].push(message);
        } else {
            state.messages[state.activeUser.id].push(message);
        }
    },
}

export default {
    state,
    getters,
    actions,
    mutations
}