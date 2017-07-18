<template>
    <div class="navbar">

        <el-menu theme="dark" :default-active="activeIndex" class="navbar-inner" mode="horizontal" @select="handleSelect" :router='true'>
            <el-menu-item index="/home" class="navbar-logo" id="logo"></el-menu-item>
            <div class="right">
                <el-input placeholder="搜索你感兴趣的兼职..." icon="search" v-model="search" :on-icon-click="handleIconClick" class="search"></el-input>
                <el-menu-item index="/home">首页</el-menu-item>
                <el-menu-item index="/jobs">兼职</el-menu-item>
                <el-menu-item index="/message">消息</el-menu-item>
                <template v-if="token">
                    <el-menu-item index="/employer">
                        <el-button type="primary" size="small">发布兼职</el-button>
                    </el-menu-item>
                    <el-submenu index="/user" >
                        <template slot="title">{{name}}</template>
                        <el-menu-item index="2-1">我的兼职</el-menu-item>
                        <el-menu-item index="2-2">我的好友</el-menu-item>
                        <el-menu-item index="2-3">个人资料</el-menu-item>
                        <el-menu-item index="/login" v-on:click ="logout">退出登录</el-menu-item>
                    </el-submenu>
                </template>
                <template v-else>
                    <el-menu-item index="/employer">
                        <el-button type="primary" size="small">发布兼职</el-button>
                    </el-menu-item>
                    <el-menu-item index="/login">
                        <el-button  size="small">注册</el-button>
                    </el-menu-item>
                    <el-menu-item index="/login">
                        <el-button  size="small">登录</el-button>
                    </el-menu-item>
                </template>
            </div>
        </el-menu>
    </div>
</template>

<script scoped>
    import {mapState} from 'vuex';
    export default {
        name:'TopNavbar',
        data() {
            return {
                activeIndex: '/home',
                search:'',
                loginDialog: false
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
                localStorage.removeItem("token");
                localStorage.removeItem("user");
            }
        }
    }
</script>

<style>
    .navbar {
        position: fixed;
        top: 0;
        left: 0;
        z-index: 20;
        width: 100%;
    }
    .navbar-inner {
        margin:0 auto;
        font-size:50px;
        font-weight: 800;
        background-color: #373d41;
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