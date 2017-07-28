<template>
    <div class="menu">
        <el-menu theme="dark" class="leftMenu" :router="true" default-active="activeIndex" @open="handleOpen" @close="handleClose">
            <el-submenu index="1">
                <template slot="title"><i class="el-icon-message"></i>兼职管理</template>
                <el-menu-item-group>
                    <el-menu-item index="/employer/works">我的兼职</el-menu-item>
                    <el-menu-item index="/employer/works/create">发布兼职</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
            <el-submenu index="2">
                <template slot="title"><i class="el-icon-message"></i>申请人管理</template>
                <el-menu-item-group>
                    <el-menu-item index="/employer/applicant/check">等待审核</el-menu-item>
                    <el-menu-item index="/employer/applicant/review">等待评价</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
            <el-submenu index="3">
                <template slot="title"><i class="el-icon-message"></i>薪资结算</template>
                <el-menu-item-group>
                    <el-menu-item index="/employer/works">待发工资</el-menu-item>
                    <el-menu-item index="/employer/works/creat">已发工资</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
            <el-menu-item index="2"><i class="el-icon-menu"></i>导航二</el-menu-item>
            <el-menu-item index="3"><i class="el-icon-setting"></i>导航三</el-menu-item>
        </el-menu>
    </div>
</template>

<script>
    export default {
        name:'LeftNavbar',
        data() {
            return {
                activeIndex: '/1-1',
                search:''
            };
        },
        methods: {
            handleOpen(key, keyPath) {
                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
                console.log(key, keyPath);
            }
        }
    }
</script>

<style scoped>
    .menu {
        height: 100%;
        background-color: #373d41;
    }
    .leftMenu {
        padding-top: 10px;
        background-color: #4A5064;
    }
</style>