<template>
    <el-dialog title="在线聊天" :visible="show" :before-close="handleClose" :modal="false" size="small" v-loading="!isMessageLoad">
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
                <div class="list" v-if="reverseUsers.length > 0 && Object.keys(activeUser).length > 0">
                    <ul>
                        <li v-for="user in reverseUsers" :class="{ ActiveItem: user.id === activeUser.id }" @click="handleUserChange(user)">
                            <img class="avatar"  width="30" height="30" :alt="user.name" :src="'/storage/' + user.pic_path">
                            <p class="name">{{user.name}}</p>
                            <el-badge :value="user.un_read_messages_count" class="Message-number"></el-badge>
                        </li>
                    </ul>
                </div>
                <div class="list" v-else>
                    <ul>
                        <li><p class="name">暂时还没有最近联系人</p></li>
                    </ul>
                </div>
            </div>
            <div class="ChatMain">
                <div class="message" v-if="isMessageLoad" ref="MessagePanel" id="MessagePanel">
                    <ul v-if="messages[activeUser.id].length > 0">
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
                <div class="text">
                    <textarea placeholder="按 Ctrl + Enter 发送" v-model="newMessage" @keyup="postNewMessage"></textarea>
                </div>
            </div>
        </div>
    </el-dialog>
</template>

<script>
    import _ from 'lodash';
    export default {
        name:'ChatDialog',
        props:{
            show:{
                type:Boolean
            },
            toUser:{
                type:Object
            }
        },
        computed:{
            reverseUsers() {
                if (this.query) {
                    return this.searchResult;
                } else {
                    return this.userLists.slice().reverse();
                }
            }
        },
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                messages:[],
                newMessage:null,
                userLists:[],
                searchResult:[],
                activeUser:null,
                isMessageLoad:false,
                query:null
            }
        },
        methods:{
            handleClose() {
                this.$emit('update:show', false);
            },
            getMessages:function () {
                let that = this;
                this.$axios.get('/api/user/chat').then( (res) => {
                    if (res.data.status == 1) {
                        that.messages = res.data.messages;
                        that.userLists = res.data.userLists;
                        if (that.$_.isEmpty(that.toUser)) {
                            that.activeUser = that.userLists[0];
                        } else {
                            that.activeUser = that.toUser;
                        }
                        that.isMessageLoad = true;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            postNewMessage:function (e) {
                if (e.ctrlKey && e.keyCode === 13 && this.newMessage.length) {
                    let that = this;
                    this.$axios.post('/api/user/chat',{
                        text: that.newMessage,
                        target_id: that.activeUser.id
                    }).then( (res) => {
                        if (res.data.status ==1) {
                            that.newMessage = '';
                            let message = res.data.message;
                            let index = that.$_.find(that.userLists,function (user) {
                                return user.id === that.activeUser.id;
                            });
                            if (index !== undefined) {
                                that.messages[that.activeUser.id].push(message);
                            } else {
                                that.messages[that.activeUser.id] = [];
                                that.messages[that.activeUser.id].push(message);
                            }
                            that.$nextTick(function () {
                                var MessagePanel = that.$el.querySelector("#MessagePanel:first-child");
                                MessagePanel.scrollTop = MessagePanel.scrollHeight - MessagePanel.clientHeight;
                            })
                        } else {
                            that.$message.error('消息发送失败，请稍后再试');
                        }
                    }).catch( (err) => {
                        console.log('PostNewMessageError',err);
                    })
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
            NewMessageEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewMessage',(payload) => {
                    let user = that.$_.find(that.userLists,function (user) {
                        return user.id === payload.from.id;
                    });
                    if (user !== undefined) {
                        that.messages[payload.from.id].push(payload.message);
                        user.un_read_messages_count += 1;
                    } else {
                        that.messages[payload.from.id] = [];
                        that.messages[payload.from.id].push(payload.message);
                    }
                    that.$notify.info({
                        title:  payload.from.name +  '   给你发了新的信息',
                        message:'[信息内容:]   '  + payload.message.content,
                        duration:0,
                        onClick: () => {
                            that.$emit('update:show', true);
                        }
                    })
                    that.$store.state.user.un_read_messages_count = that.$store.state.user.un_read_messages_count + 1;
                })
            },
            handleUserChange:function (user) {
                this.activeUser = user;
                this.$nextTick(function () {
                    var MessagePanel = this.$el.querySelector("#MessagePanel:first-child");
                    MessagePanel.scrollTop = MessagePanel.scrollHeight - MessagePanel.clientHeight;
                });
                this.markMessageAsRead(user);
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
            }
        },
        watch:{
            activeUser:function (newVal) {
                this.query = null;
                let index = this.$_.find(this.userLists,function (user) {
                    return user.id === newVal.id;
                });
                if (index !== undefined) {
                    this.userLists.splice(index,1);
                    this.userLists.push(newVal);
                } else {
                    this.userLists.push(newVal);
                    if (this.messages[newVal.id] == undefined ) {
                        this.messages[newVal.id] = [];
                    }
                }
            },
            query:_.debounce(function (newVal) {
                if (newVal) {
                    this.getSearchUsers(newVal);
                }
            },1000),
        },
        /*created:function () {
            this.getMessages();
            this.NewMessageEvent();
        },*/
    }
</script>

<style scoped>
    .ChatPanel {
        width: 100%;
        margin-top: -10px;
        height: 600px;
        overflow: hidden;
        border-radius: 3px;
    }
    .SideBar {
        height: 100%;
        float: left;
        width: 200px;
        color: #f4f4f4;
        background-color: #2e3238;
    }
    .ChatMain {
        height: 100%;
        position: relative;
        overflow: hidden;
        background-color: #eee;
    }
    .card {
        padding: 12px;
        border-bottom: solid 1px #24272C;
    }
    .card header {
        display: flex;
    }
    .card footer {
        display: flex;
        margin-top: 10px;
    }
    .card .avatar .name {
        vertical-align: middle;
    }
    .card .avatar {
        border-radius: 2px;
    }
    .card .name {
        margin: 8px 0px 0px 15px;
        font-size: 16px;
    }
    .card .ChatSearch {
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
    .list {
        margin-top: 10px;
    }
    .list ul {
        list-style-type: none;
        padding-left: 0px;
    }
    .list li {
        padding: 12px 15px;
        border-bottom: 1px solid #292C33;
        cursor: pointer;
        transition: background-color .1s;
    }
    .list li:hover {
         background-color: rgba(255, 255, 255, 0.03);
     }
    .list li .active {
         background-color: rgba(255, 255, 255, 0.1);
     }
    .ActiveItem {
        background-color: rgba(255, 255, 255, 0.1);
    }
    .list .avatar, .name {
        vertical-align: middle;
    }
    .list .avatar {
        border-radius: 2px;
    }
    .list .name {
        display: inline-block;
        margin: 0 0 0 15px;
    }
    .list .Message-number {
        float: right;
        margin-top: 6px;
        margin-right: 10px;
    }
    .message {
        padding: 10px 15px;
        overflow-y: scroll;
        height: 450px;
    }
    .message ul {
        list-style-type: none;
        padding-left: 10px;
        padding-right: 10px;
    }
    .message li {
        margin-bottom: 15px;
    }
    .message .time {
        margin: 7px 0;
        text-align: center;
    }
    .message .time span {
        display: inline-block;
        padding: 0 18px;
        font-size: 12px;
        color: #fff;
        border-radius: 2px;
        background-color: #dcdcdc;
    }
    .message .avatar {
        float: left;
        margin: 0 10px 0 0;
        border-radius: 3px;
    }
    .message .ChatText {
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
    .message .ChatText:before {
         content: " ";
         position: absolute;
         top: 9px;
         right: 100%;
         border: 6px solid transparent;
         border-right-color: #fafafa;
    }
    .message .self {
        text-align: right;
    }
    .message .self .avatar {
        float: right;
        margin: 0 0 0 10px;
    }
    .message .self .ChatText {
        background-color: #b2e281;
    }
    .message .self .ChatText:before {
         right: inherit;
         left: 100%;
         border-right-color: transparent;
         border-left-color: #b2e281;
    }
    .text {
        height: 128px;
        border: solid 1px #ddd;
    }
    .text textarea {
        padding: 10px;
        height: 100%;
        width: 100%;
        border: none;
        outline: none;
        font-family: "Micrsofot Yahei";
        resize: none;
    }
</style>