<template>
    <div class="navbar">
        <el-menu :default-active="activeIndex" class="navbar-inner" mode="horizontal" @select="handleSelect" :router='true'>
            <el-menu-item index="0" :route="{path:'/home'}" id="logo">
                <img src="/images/web-logo.png" class="logo" heigth="40" alt="log0">
            </el-menu-item>
            <el-menu-item index="1" class="nav-item" :route="{path:'/home'}"><i class="fa fa-home fa-fw"></i>&nbsp;首页</el-menu-item>
            <el-menu-item index="2" class="nav-item" :route="{path:'/works'}"><i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职</el-menu-item>
            <el-menu-item index="3" class="nav-item" :route="{path:'/export'}"><i class="fa fa-fire fa-fw"></i>&nbsp;流行</el-menu-item>
            <el-input placeholder="搜索你感兴趣的兼职..." icon="search" v-model="search" :on-icon-click="handleIconClick" class="search"></el-input>
            <div class="right">
                <template v-if="token">
                    <el-menu-item index="/employer/works/create" style="width: 200px">
                        <el-button type="primary" class="Button-large">发布兼职</el-button>
                    </el-menu-item>
                    <el-popover ref="NotificationPopover" placement="bottom" width="400">
                        <el-tabs v-model="activeNotification" @tab-click="handleNotificationChange">
                            <el-tab-pane name="UserNotification">
                                <span class="Tab-title" slot="label">用户<el-badge :value="unReadUserNotifications.length" class="TabBadge-number" v-if="unReadUserNotifications.length > 0"></el-badge></span>
                                <div class="Notifications-lists" v-if="unReadUserNotifications.length > 0">
                                    <div class="NotificationList" v-for="notification in unReadUserNotifications">
                                        {{notification.actionFrom.name}}--{{notification.type}}--{{notification.actionTo.title}}
                                    </div>
                                </div>
                                <div class="EmptyState" v-else>
                                    <div class="EmptyState-inner">
                                        <i class="fa fa-bell EmptyState-icon"></i>
                                        <span>暂时还没新的消息</span>
                                    </div>
                                </div>
                            </el-tab-pane>
                            <el-tab-pane name="EmployerNotification">
                                <span class="Tab-title" slot="label">雇主<el-badge :value="unReadEmployerNotifications.length" class="TabBadge-number" v-if="unReadEmployerNotifications.length"></el-badge></span>
                                <div class="Notifications-lists" v-if="unReadEmployerNotifications.length > 0">

                                </div>
                                <div class="EmptyState" v-else>
                                    <div class="EmptyState-inner">
                                        <i class="fa fa-bell EmptyState-icon"></i>
                                        <span>暂时还没新的消息</span>
                                    </div>
                                </div>
                            </el-tab-pane>
                        </el-tabs>
                    </el-popover>
                    <el-menu-item index="notification" :route="{}">
                        <el-button type="text" class="Icon-large" v-popover:NotificationPopover><i class="fa fa-bell"></i></el-button>
                        <el-badge :value="(unReadUserNotifications.length + unReadEmployerNotifications.length)" class="Badge-number" v-if="(unReadUserNotifications.length + unReadEmployerNotifications.length) > 0"></el-badge>
                    </el-menu-item>
                    <el-menu-item index="message" :route="{}">
                        <el-button type="text" class="Icon-large"><i class="fa fa-comments"></i></el-button>
                        <el-badge :value="6" class="Badge-number"></el-badge>
                    </el-menu-item>
                    <el-submenu index="/user" >
                        <template slot="title">{{name}}</template>
                        <el-menu-item index="2-1"><i class="fa fa-briefcase fa-fw"></i>&nbsp;&nbsp;我的兼职</el-menu-item>
                        <el-menu-item index="2-2"><i class="fa fa-address-book fa-fw"></i>&nbsp;&nbsp;我的好友</el-menu-item>
                        <el-menu-item index="2-3"><i class="fa fa-address-card fa-fw"></i>&nbsp;&nbsp;个人资料</el-menu-item>
                        <el-menu-item index="/login" v-on:click ="logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;&nbsp;退出登录</el-menu-item>
                    </el-submenu>
                    <UserNotifications></UserNotifications>
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
    </div>
</template>

<script>
    import {mapState} from 'vuex';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    import * as types from '../../store/mutation-types';
    import UserNotifications from '../notifications/UserNotifications.vue';
    import EmployerNotifications from '../notifications/EmployerNotifications.vue';
    export default {
        name:'Navbar',
        components: {LoginDialog,UserNotifications,EmployerNotifications},
        props:{
            activeIndex:{
                default:'1'
            }
        },
        data() {
            return {
                search:'',
                loginShow: false,
                activeNotification:'UserNotification',
                unReadUserNotifications:[],
                unReadEmployerNotifications:[],
            };
        },
        computed:mapState({
            token:state => state.user.token,
            name: state => state.user.name
        }),
        methods: {
            handleSelect(key, keyPath) {

            },
            handleIconClick(ev) {

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
            handleNotificationChange:function () {

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
    background-color: #fff;
    width: 100%;
    border-bottom: 1px solid rgba(30,35,42,.06);
    box-shadow: 0 1px 3px 0 rgba(0,34,77,.05);
    }
    .navbar-inner {
        width: 1210px;
        margin:0 auto;
        font-size:50px;
        font-weight: 800;
        background-color: #fff;
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
        width: 200px;
        margin:10px 0px 0px 15px;
        float:left;
        overflow: hidden;
    }
    .right {
        float:right;
    }
    .Button-large {
        font-size: 15px;
    }
    .Icon-large {
        font-size: 20px;
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
        max-height: 400px;
        min-height: 272px;
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
</style>