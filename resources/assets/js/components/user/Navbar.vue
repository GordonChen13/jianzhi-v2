<template>
    <div class="navbar">

        <el-menu :default-active="activeIndex" class="navbar-inner" mode="horizontal" @select="handleSelect" :router='true'>
            <el-menu-item index="/home" class="navbar-logo" id="logo"></el-menu-item>
            <el-input placeholder="搜索你感兴趣的兼职..." icon="search" v-model="search" :on-icon-click="handleIconClick" class="search"></el-input>
            <el-menu-item index="/home"><i class="fa fa-home fa-fw"></i>&nbsp;首页</el-menu-item>
            <el-menu-item index="/works"><i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职</el-menu-item>
            <el-menu-item index="/message"><i class="fa fa-comments fa-fw"></i>&nbsp;消息</el-menu-item>
            <div class="right">
                <template v-if="token">
                    <el-menu-item index="/employer/works/create">
                        <el-button type="primary" size="small">发布兼职</el-button>
                    </el-menu-item>
                    <el-submenu index="/user" >
                        <template slot="title">{{name}}</template>
                        <el-menu-item index="2-1"><i class="fa fa-briefcase fa-fw"></i>&nbsp;&nbsp;我的兼职</el-menu-item>
                        <el-menu-item index="2-2"><i class="fa fa-address-book fa-fw"></i>&nbsp;&nbsp;我的好友</el-menu-item>
                        <el-menu-item index="2-3"><i class="fa fa-address-card fa-fw"></i>&nbsp;&nbsp;个人资料</el-menu-item>
                        <el-menu-item index="/login" v-on:click ="logout"><i class="fa fa-sign-out fa-fw"></i>&nbsp;&nbsp;退出登录</el-menu-item>
                    </el-submenu>
                </template>
                <template v-else>
                    <el-menu-item index="/employer/works/create">
                        <el-button type="primary" size="small">发布兼职</el-button>
                    </el-menu-item>
                    <div class="LoginButtons el-menu--horizontal el-menu-item">
                        <el-button  size="small" @click="loginShow = true"><i class="fa fa-user-plus fa-fw"></i>&nbsp;注册</el-button>
                        <el-button  size="small" @click="loginShow = true"><i class="fa fa-sign-in fa-fw"></i>&nbsp;登录</el-button>
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
    export default {
        name:'Navbar',
        components: {LoginDialog},
        data() {
            return {
                activeIndex: '/home',
                search:'',
                loginShow: false
            };
        },
        computed:mapState({
            token:state => state.user.token,
            name: state => state.user.name
        }),
        methods: {
            handleSelect(key, keyPath) {
                console.log(key, keyPath);
            },
            handleIconClick(ev) {
                console.log(ev);
            },
            logout () {
                this.$store.commit(types.LOGOUT);
            }
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
        width: 960px;
        margin:0 auto;
        font-size:50px;
        font-weight: 800;
        background-color: #fff;
    }
    .navbar-logo {
        float: left;
        width: 161px;
        height: 100%;
        background-image: url('../../../image/logo.png');
        background-repeat: no-repeat;
        background-position: 0 4px;
        text-indent: -9999px;

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
</style>