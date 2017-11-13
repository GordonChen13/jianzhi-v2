<template>
    <div class="navbar">
        <el-menu theme="dark" :default-active="activeIndex" class="navbar-inner" mode="horizontal" @select="handleSelect" :router='true'>
            <el-menu-item index="0" :route="{path:'/home'}" id="logo">
                <img src="/images/web-logo.png" class="logo" heigth="40" alt="log0">
            </el-menu-item>
            <el-menu-item index="1" class="nav-item" :route="{path:'/home'}"><i class="fa fa-home fa-fw"></i>&nbsp;首页</el-menu-item>
            <el-menu-item index="2" class="nav-item" :route="{path:'/works'}"><i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职</el-menu-item>
            <el-menu-item index="3" class="nav-item" :route="{path:'/hot'}"><i class="fa fa-fire fa-fw"></i>&nbsp;热门</el-menu-item>
            <el-input placeholder="搜索你感兴趣的兼职或者用户..." icon="search" v-model="search" @keyup.enter="handleIconClick" :on-icon-click="handleIconClick" class="search"></el-input>
            <div class="right">
                <template v-if="token">
                    <el-menu-item index="/home">
                        <el-button type="primary" class="Button-large">返回用户页面</el-button>
                    </el-menu-item>
                    <el-popover ref="NotificationPopover" placement="bottom" width="400">
                        <el-tabs v-model="activeNotification" @tab-click="handleNotificationChange">
                            <el-tab-pane name="UserNotification" ref="UserNotification">
                                <span class="Tab-title" slot="label">用户<el-badge :value="unReadUserNotifications.length" class="TabBadge-number" v-if="unReadUserNotifications.length > 0 && !isUserRead"></el-badge></span>
                                <div class="Notifications-lists" v-if="unReadUserNotifications.length > 0">
                                    <UserNotificationList class="NotificationList" v-for="notification in unReadUserNotifications" :notification="notification"></UserNotificationList>
                                </div>
                                <div class="EmptyState" v-else>
                                    <div class="EmptyState-inner">
                                        <i class="fa fa-bell EmptyState-icon"></i>
                                        <span>暂时还没新的提醒</span>
                                    </div>
                                </div>
                                <div class="Notifications-footer">
                                    <router-link to="/user/notifications" class="Notifications-link">查看全部提醒</router-link>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane name="EmployerNotification" ref="EmployerNotification">
                                <span class="Tab-title" slot="label">雇主<el-badge :value="unReadEmployerNotifications.length" class="TabBadge-number" v-if="unReadEmployerNotifications.length && !isEmployerRead"></el-badge></span>
                                <div class="Notifications-lists" v-if="unReadEmployerNotifications.length > 0">
                                    <EmployerNotificationList v-for="notification in unReadEmployerNotifications" :notification="notification"></EmployerNotificationList>
                                </div>
                                <div class="EmptyState" v-else>
                                    <div class="EmptyState-inner">
                                        <i class="fa fa-bell EmptyState-icon"></i>
                                        <span>暂时还没新的提醒</span>
                                    </div>
                                </div>
                                <div class="Notifications-footer">
                                    <router-link to="/employer/notifications" class="Notifications-link">查看全部提醒</router-link>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </el-popover>
                    <el-menu-item index="notification" :route="{}">
                        <el-button type="text" class="Icon-large" v-popover:NotificationPopover @click="userMarkAsRead"><i class="fa fa-bell"></i></el-button>
                        <div class="Badge-content" v-if="!isUserRead">
                            <el-badge :value="(unReadUserNotifications.length + unReadEmployerNotifications.length)" class="Badge-number" v-if="(unReadUserNotifications.length + unReadEmployerNotifications.length) > 0 && !isEmployerRead"></el-badge>
                        </div>
                        <div class="Badge-content" v-else-if="!isEmployerRead">
                            <el-badge :value="unReadEmployerNotifications.length" class="Badge-number" v-if="unReadEmployerNotifications.length > 0"></el-badge>
                        </div>
                    </el-menu-item>
                    <el-menu-item index="message" :route="{}">
                        <el-button type="text" class="Icon-large" @click="chatDialogShow = !chatDialogShow"><i class="fa fa-comments"></i></el-button>
                        <el-badge :value="user.un_read_messages_count" class="Badge-number"></el-badge>
                    </el-menu-item>
                    <el-submenu index="8" >
                        <template slot="title">{{name}}</template>
                        <el-menu-item index="8-1" :route="{name:'employerProfile',params:{id:user.id}}"><i class="fa fa-user fa-fw"></i>&nbsp;&nbsp;我的主页</el-menu-item>
                        <el-menu-item index="8-2" :route="{name:'userEdit',params:{id:user.id}}"><i class="fa fa-address-card fa-fw"></i>&nbsp;&nbsp;个人资料</el-menu-item>
                        <el-menu-item index="/login" v-on:click ="logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;&nbsp;退出登录</el-menu-item>
                    </el-submenu>
                    <EmployerNotifications></EmployerNotifications>
                </template>
                <template v-else>
                    <el-menu-item index="/employer/works/create">
                        <el-button type="primary" class="Button-large">发布兼职</el-button>
                    </el-menu-item>
                    <div class="LoginButtons el-menu--horizontal el-menu-item">
                        <el-button  @click="loginShow = true"><i class="fa fa-user-plus fa-fw"></i>&nbsp;注册</el-button>
                        <el-button  @click="loginShow = true"><i class="fa fa-sign-in fa-fw"></i>&nbsp;登录</el-button>
                    </div>
                </template>
            </div>
        </el-menu>
        <LoginDialog :show.sync="loginShow"></LoginDialog>
        <ChatDialog :show.sync="chatDialogShow"></ChatDialog>
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    import ChatDialog from '../common/Dialog/ChatDialog.vue';
    import * as types from '../../store/mutation-types';
    import EmployerNotifications from '../notifications/EmployerNotifications.vue';
    import UserNotificationList from '../notifications/UserNotificationList.vue';
    import EmployerNotificationList from '../notifications/EmployerNotificationList.vue';
    export default {
        name:'TopNavbar',
        components: {LoginDialog,EmployerNotifications,UserNotificationList,EmployerNotificationList,ChatDialog},
        props:{
            activeIndex:{
                default:'1'
            }
        },
        data() {
            return {
                search:'',
                loginShow: false,
                chatDialogShow:false,
                activeNotification:'UserNotification',
                unReadUserNotifications:[],
                unReadEmployerNotifications:[],
                isUserRead: false,
                isEmployerRead: false,
            };
        },
        computed:mapState({
            user:state => state.user,
            token:state => state.user.token,
            name: state => state.user.name
        }),
        methods: {
            handleSelect(key, keyPath) {

            },
            handleIconClick(ev) {
                this.$router.push({
                    name:'search',
                    query:{
                        search:this.search
                    }
                })
            },
            logout () {
                let that = this;
                this.$axios.get('/api/logout').then( (res) => {
                    if (res.data.status ==1) {
                        that.$message.success(res.data.msg);
                    } else {
                        console.log('logoutError',res.data);
                    }
                })
                this.$store.commit(types.LOGOUT);
            },
            handleNotificationChange:function (tab,event) {
                if (tab == this.$refs.UserNotification && !this.isUserRead && this.unReadUserNotifications.length > 0) {
                    let that = this;
                    this.$axios.get('/api/user/notifications/markasread').then( (res) => {
                        if (res.data.status == 1) {
                            that.isUserRead = true;
                        } else {
                            console.log('MaskAsReadError',res);
                        }
                    })
                } else if (!this.isEmployerRead && this.unReadEmployerNotifications.length > 0) {
                    let that = this;
                    this.$axios.get('/api/employer/notifications/markasread').then( (res) => {
                        if (res.data.status == 1) {
                            that.isEmployerRead = true;
                        } else {
                            console.log('MaskAsReadError',res);
                        }
                    })
                }
            },
            userMarkAsRead:function () {
                if(!this.isUserRead && this.unReadUserNotifications.length > 0) {
                    let that = this;
                    this.$axios.get('/api/user/notifications/markasread').then( (res) => {
                        if (res.data.status == 1) {
                            that.isUserRead = true;
                        } else {
                            console.log('MaskAsReadError',res);
                        }
                    })
                }
            },
            getUserUnreadNotifications:function () {
                let that = this;
                this.$axios.get('/api/user/notifications?type=unread').then( (res) => {
                    if (res.data.status == 1) {
                        that.unReadUserNotifications = res.data.notifications
                    }
                })
            },
            getEmployerUnreadNotifications:function () {
                let that = this;
                this.$axios.get('/api/employer/notifications?type=unread').then( (res) => {
                    if (res.data.status == 1) {
                        that.unReadEmployerNotifications = res.data.notifications
                    }
                })
            }
        },
        created:function () {
            this.getUserUnreadNotifications();
            this.getEmployerUnreadNotifications();
        }
    }
</script>

<style scoped>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 100;
        background-color: #373d41;
        width: 100%;
        border-bottom: 1px solid rgba(30,35,42,.06);
        box-shadow: 0 1px 3px 0 rgba(0,34,77,.05);
    }
    .navbar-inner {
        margin:0 auto;
        font-size:50px;
        font-weight: 800;
    }
    .nav-item {
        font-size: 16px;
    }
    .logo {
        width:100px;
        margin-top:6px;
    }
    #logo:hover,#search:hover {
        border-bottom: transparent;
    }
    .search {
        width: 300px;
        margin:12px 0px 0px 15px;
        float:left;
        overflow: hidden;
    }
    .right {
        margin-right: 20px;
        float:right;
    }
    .Button-large {
        font-size: 15px;
    }
    .Icon-large {
        font-size: 23px;
        height: 60px;
    }
    .Badge-number {
        position: absolute;
        top: 6px;
        right: 10px;
    }
    .Tab-title {
        font-size:15px;
        font-weight:500;
        padding-left:60px;
        padding-right:60px;
    }
    .TabBadge-number {
        position: absolute;
    }
    .Notifications-lists {
        height: 272px;
        overflow-y: scroll;
    }
    .EmptyState {
        padding: 70px 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        height: 30%;
    }
    .EmptyState-inner {
        font-size: 15px;
        color: #8590a6;
        text-align: center;
    }
    .EmptyState-icon {
        display: block;
        margin: 0 auto;
        font-size: 80px;
        padding-bottom:30px;
        color: rgba(133, 144, 166, 0.3);
    }
    .Notifications-footer {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        width: 100%;
        height: 40px;
        padding: 0 16px;
        line-height: 40px;
        background: #fff;
        border-top: 1px solid #ebeef5;
        -webkit-box-sizing: border-box;
        box-sizing: border-box;
    }
    .Notifications-link {
        font-size: 15px;
        margin-left: auto;
        margin-right: 10px;
        color: #8590a6;
        text-align: center;
        cursor: pointer;
        background: none;
    }
</style>