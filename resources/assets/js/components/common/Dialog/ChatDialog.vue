<template>
    <el-dialog :visible="show" :before-close="handleClose" @open="init" :show-close="false"
               :modal="false" size="small" v-loading="!isMessageLoad" class="ChatDialog">
        <div class="ChatPanel">
            <div class="SideBar">
                <div class="card">
                    <header>
                        <img class="avatar" width="40" height="40" :alt="me.name" :src="'/storage/' + me.pic_path">
                        <p class="name">{{me.name}}</p>
                    </header>
                    <footer>
                        <input class="ChatSearch" v-model="query" type="text" placeholder="search user...">
                    </footer>
                </div>
                <div class="SearchResult" v-if="query != ''">
                    <div class="list" v-if="searchResult.length > 0">
                        <ul>
                            <li v-for="user in searchResult" @click="handleUserChange(user)">
                                <img class="avatar"  width="30" height="30" :alt="user.name" :src="'/storage/' + user.pic_path">
                                <p class="name">{{user.name}}</p>
                                <el-badge :value="user.un_read_messages_count" class="Message-number"></el-badge>
                            </li>
                        </ul>
                    </div>
                    <div class="list" v-else>
                        <ul><li><p class="name">找不到匹配的用户</p></li></ul>
                    </div>
                </div>
                <div class="UserLists" v-else>
                    <div class="list" v-if="userLists.length > 0">
                        <ul>
                            <li v-for="user in userLists" :class="{ ActiveItem: user.id === activeUser.id }" @click="handleUserChange(user)">
                                <img class="avatar"  width="30" height="30" :alt="user.name" :src="'/storage/' + user.pic_path">
                                <p class="name">{{user.name}}</p>
                                <el-badge :value="user.un_read_messages_count" class="Message-number"></el-badge>
                            </li>
                        </ul>
                    </div>
                    <div class="list" v-else>
                        <ul><li><p class="name">暂时还没有最近联系人</p></li></ul>
                    </div>
                </div>
            </div>
            <div class="ChatMain">
                <div class="message" v-if="isMessageLoad && activeUser != null" ref="MessagePanel" id="MessagePanel">
                    <ul v-if="messages[activeUser.id] && messages[activeUser.id].length > 0">
                        <li v-for="message in messages[activeUser.id]" id="MessageItem">
                            <p class="time">
                                <span>{{ $moment(message.created_at).format("M-D hh:mm") }}</span>
                            </p>
                            <div class="main" :class="{ self: message.user_id === me.id }">
                                <img class="avatar" width="30" height="30" :src="message.user_id === me.id ? '/storage/' + me.pic_path : '/storage/' + activeUser.pic_path" />
                                <div class="ChatText">{{ message.content }}</div>
                            </div>
                        </li>
                    </ul>
                </div>
                <div class="message Empty" v-else></div>
                <div class="text">
                    <textarea placeholder="按 Ctrl + Enter 发送" v-model="newMessage" @keyup="postNewMessage" @focus="textFocus"></textarea>
                </div>
            </div>
        </div>
    </el-dialog>
</template>

<script>
    import _ from 'lodash';
    import {mapState} from 'vuex';
    export default {
        name:'ChatDialog',
        props:{
            show:{
                type:Boolean
            },
            toUser:{
                type:Object,
                default:function () {
                    return {};
                }
            }
        },
        computed:mapState({
            messages: state => state.chat.messages,
            userLists: state => state.chat.userLists.slice().reverse(),
            activeUser: state => state.chat.activeUser
        }),
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                newMessage:null,
                searchResult:[],
                isMessageLoad:false,
                query:''
            }
        },
        methods:{
            handleClose() {
                this.$emit('update:show', false);
            },
            getMessages:function () {
                let that = this;
                this.$store.dispatch('getMessages').then(function (data) {
                    that.isMessageLoad = true;
                    if (Object.keys(that.toUser).length > 0) {
                        that.$store.commit('CHANGE_ACTIVE_USER',that.toUser);
                    }
                })
            },
            textFocus:function () {
                this.scrollToBottom();
                this.markMessageAsRead(this.activeUser);
            },
            scrollToBottom:function () {
                this.$nextTick(function () {
                    var MessagePanel = this.$el.querySelector("#MessagePanel:first-child");
                    MessagePanel.scrollTop = MessagePanel.scrollHeight - MessagePanel.clientHeight;
                });
            },
            postNewMessage:function (e) {
                if (e.ctrlKey && e.keyCode === 13 && this.newMessage.length) {
                    if (this.activeUser) {
                        let that = this;
                        this.$store.dispatch('postNewMessage',this.newMessage).then(function (data) {
                            that.newMessage = '';
                            that.scrollToBottom();
                        })
                    } else {
                        this.$message.error('请先选择聊天的好友');
                    }
                }
            },
            markMessageAsRead:function (user) {
                let that = this;
                let count = user.un_read_messages_count;
                if (count > 0) {
                    this.$axios.put('/api/user/chat/' + user.id).then( (res) => {
                        if (res.data.status == 1) {
                            user.un_read_messages_count = 0;
                            that.$store.state.user.un_read_messages_count = that.$store.state.user.un_read_messages_count - count;

                        } else {
                            that.$message.error('标记为已读出错');
                        }
                    }).catch( (err) => {
                        console.log('markMessageAsReadError',err);
                    })
                }
            },
            handleUserChange:function (user) {
                this.$store.commit('CHANGE_ACTIVE_USER',user);
                this.scrollToBottom();
                this.markMessageAsRead(user);
                this.query = '';
            },
            getSearchUsers:function (query) {
                if (localStorage.user) {
                    let that = this;
                    this.$axios.get('/api/users', {
                        params: {
                            search: query
                        }
                    }).then( (res) => {
                        if (res.data.status == 1) {
                            that.searchResult = res.data.users;
                        } else {
                            that.$message.error('搜索用户时出错');
                        }
                    })
                }
            },
            init:function () {
                this.getMessages();
            }
        },
        watch:{
            query:_.debounce(function (newVal) {
                if (newVal != '') {
                    this.getSearchUsers(newVal);
                }
            },1000),
        }
    }
</script>

<style>
    .ChatPanel {
        width: 100%;
        margin-top: -10px;
        height: 600px;
        overflow: hidden;
        border-radius: 3px;
    }
    .ChatDialog .el-dialog__body {
        padding: 0px 0px;
    }
    .ChatDialog .el-dialog__header {
        padding: 0px 0px;
    }
    .ChatPanel .SideBar {
        height: 100%;
        float: left;
        width: 200px;
        color: #f4f4f4;
        background-color: #2e3238;
    }
    .ChatPanel .ChatMain {
        height: 100%;
        position: relative;
        overflow: hidden;
        background-color: #eee;
    }
    .ChatPanel .card {
        padding: 12px;
        border-bottom: solid 1px #24272C;
    }
    .ChatPanel .card header {
        display: flex;
    }
    .ChatPanel .card footer {
        display: flex;
        margin-top: 10px;
    }
    .ChatPanel .card .avatar .name {
        vertical-align: middle;
    }
    .ChatPanel .card .avatar {
        border-radius: 2px;
    }
    .ChatPanel .card .name {
        margin: 8px 0px 0px 15px;
        font-size: 16px;
    }
    .ChatPanel .card .ChatSearch {
        padding: 0 10px;
        width: 100%;
        font-size: 12px;
        color: #fff;
        height: 30px;
        line-height: 30px;
        border: solid 1px #3a3a3a;
        border-radius: 4px;
        outline: none;
        background-color: #26292E;
    }
    .ChatPanel .list {
        margin-top: 10px;
    }
    .ChatPanel .list ul {
        list-style-type: none;
        padding-left: 0px;
    }
    .ChatPanel .list li {
        padding: 12px 15px;
        border-bottom: 1px solid #292C33;
        cursor: pointer;
        transition: background-color .1s;
    }
    .ChatPanel .list li:hover {
         background-color: rgba(255, 255, 255, 0.03);
     }
    .ChatPanel .list li .active {
         background-color: rgba(255, 255, 255, 0.1);
     }
    .ChatPanel .ActiveItem {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .ChatPanel .list .avatar, .name {
        vertical-align: middle;
    }
    .ChatPanel .list .avatar {
        border-radius: 2px;
    }
    .ChatPanel .list .name {
        display: inline-block;
        margin: 0 0 0 15px;
    }
    .ChatPanel .list .Message-number {
        float: right;
        margin-top: 6px;
        margin-right: 10px;
    }
    .ChatPanel .message {
        padding: 10px 15px;
        overflow-y: scroll;
        height: 450px;
        border: solid 2px #ddd;
    }
    .ChatPanel .message ul {
        list-style-type: none;
        padding-left: 10px;
        padding-right: 10px;
    }
    .ChatPanel .message li {
        margin-bottom: 15px;
    }
    .ChatPanel .message .time {
        margin: 7px 0;
        text-align: center;
    }
    .ChatPanel .message .time span {
        display: inline-block;
        padding: 0 18px;
        font-size: 12px;
        color: #fff;
        border-radius: 2px;
        background-color: #dcdcdc;
    }
    .ChatPanel .message .avatar {
        float: left;
        margin: 0 10px 0 0;
        border-radius: 3px;
    }
    .ChatPanel .message .ChatText {
        display: inline-block;
        position: relative;
        padding: 0 10px;
        min-height: 30px;
        line-height: 2.5;
        font-size: 12px;
        text-align: left;
        word-break: break-all;
        background-color: #fafafa;
        border-radius: 4px;
    }
    .ChatPanel .message .ChatText:before {
         content: " ";
         position: absolute;
         top: 9px;
         right: 100%;
         border: 6px solid transparent;
         border-right-color: #fafafa;
    }
    .ChatPanel .message .self {
        text-align: right;
    }
    .ChatPanel .message .self .avatar {
        float: right;
        margin: 0 0 0 10px;
    }
    .ChatPanel .message .self .ChatText {
        background-color: #b2e281;
    }
    .ChatPanel .message .self .ChatText:before {
         right: inherit;
         left: 100%;
         border-right-color: transparent;
         border-left-color: #b2e281;
    }
    .ChatPanel .text {
        height: 128px;
        border: solid 1px #ddd;
    }
    .ChatPanel .text textarea {
        padding: 10px;
        height: 100%;
        width: 100%;
        border: none;
        outline: none;
        font-family: "Micrsofot Yahei";
        resize: none;
    }
</style>