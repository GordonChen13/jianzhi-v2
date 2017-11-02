<template>
    <div class="menu">
        <el-menu theme="dark" class="leftMenu" :router="true" default-active="activeIndex" @open="handleOpen" @close="handleClose">
            <el-submenu index="javascirpt(1);">
                <template slot="title"><i class="fa fa-briefcase fa-fw"></i>&nbsp;&nbsp;兼职管理</template>
                <el-menu-item-group>
                    <el-menu-item index="/employer/works"><i class="fa fa-list fa-fw"></i>&nbsp;&nbsp;我的兼职</el-menu-item>
                    <el-menu-item index="/employer/works/create"><i class="fa fa-pencil-square-o fa-fw"></i>&nbsp;&nbsp;发布兼职</el-menu-item>
                    <el-menu-item index="/employer/works/answer"><i class="fa fa-comments-o fa-fw"></i>&nbsp;&nbsp;咨询回复</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
            <el-submenu index="javascirpt(2);">
                <template slot="title"><i class="fa fa-users fa-fw"></i>&nbsp;&nbsp;申请人管理</template>
                <el-menu-item-group>
                    <el-menu-item index="/employer/applicant/manage"><i class="fa fa-check-square-o fa-fw"></i>&nbsp;&nbsp;等待审核</el-menu-item>
                    <el-menu-item index="/employer/applicant/review"><i class="fa fa-star-half-o fa-fw"></i>&nbsp;&nbsp;等待评价</el-menu-item>
                </el-menu-item-group>
            </el-submenu>
            <el-menu-item index="/employer/company"><i class="fa fa-building fa-fw"></i>&nbsp;&nbsp;我的公司</el-menu-item>
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
//                console.log(key, keyPath);
            },
            handleClose(key, keyPath) {
//                console.log(key, keyPath);
            }
        }
    }
</script>

<style scoped>
    .menu {
        position: fixed;
        left: 0;
        width: 200px;
        height: 100%;
        background-color: #373d41;
    }
    .leftMenu {
        padding-top: 10px;
        background-color: #4A5064;
    }
</style>