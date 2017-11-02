<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <el-col :span="16" class="left-panel">
                <el-card class="ResultCard" :body-style="{padding:'0px 20px 0px 20px'}">
                    <div class="Card-title" slot="header">
                        <span>搜索结果</span>
                    </div>
                    <el-tabs v-model="activeTab" @tab-click="handleTabChange">
                        <el-tab-pane label="兼职" name="works" ref="works">
                            <div class="WorkLists" v-if="works.length > 0">
                                <WorkList class="WorkItem" v-for="work in works" :work="work" :body-style="{ padding: '10px 0px'}"></WorkList>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                    <span>找不到相关的兼职</span>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="用户" name="users" ref="users">
                            <div class="UserLists" v-if="users.length > 0">
                                <UserList class="UserItem" v-for="user in users" :user="user"></UserList>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-user EmptyState-icon"></i>
                                    <span>找不到相关的用户</span>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="雇主" name="employers" ref="employers">
                            <div class="EmployerLists" v-if="employers.length > 0">
                                <EmployerList class="EmployerItem" v-for="employer in employers" :employer="employer"></EmployerList>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-user EmptyState-icon"></i>
                                    <span>找不到相关的雇主</span>
                                </div>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </el-card>
            </el-col>
            <el-col :span="8" class="right-panel">
                <SideNav class="SideNav"></SideNav>
                <RecommendWorkCard></RecommendWorkCard>
            </el-col>
        </div>
        <CornerButtons></CornerButtons>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import SideNav from '../../components/user/SideNav.vue';
    import WorkList from '../../components/common/WorkList.vue';
    import UserList from '../../components/common/Follow/UserFollowList.vue';
    import EmployerList from '../../components/common/Follow/EmployerFollowList.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import RecommendWorkCard from '../../components/common/Card/RecommendWorkCard.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'Search',
        components:{Navbar,SideNav,WorkList,UserList,EmployerList,CornerButtons,RecommendWorkCard},
        data() {
            return {
                activeTab:'works',
                works:[],
                users:[],
                employers:[],
                search:this.$route.query.search
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {
            handleTabChange:function (tab,event) {
                if (tab == this.$refs.works) {
                    this.getSearchWorks();
                } else if (tab == this.$refs.users) {
                    this.getSearchUsers();
                } else {
                    this.getSearchEmployers();
                }
            },
            getSearchWorks:function () {
                let that = this;
                this.$axios.get('/api/works?search=' + this.search).then( (res) => {
                    if (res.data.status == 1) {
                        that.works = res.data.works;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            getSearchUsers:function () {
                let that = this;
                this.$axios.get('/api/users?search=' + this.search).then( (res) => {
                    if (res.data.status == 1) {
                        that.users = res.data.users;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            getSearchEmployers:function () {
                let that = this;
                this.$axios.get('/api/employers?search=' + this.search).then( (res) => {
                    if (res.data.status == 1) {
                        that.employers = res.data.employers;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            }
        },
        watch: {
            '$route': function (route) {
                this.search = route.query.search;
                this.getSearchUsers();
                this.getSearchWorks();
                this.getSearchEmployers();
            }
        },
        created:function () {
            this.getSearchWorks();
        }

    }
</script>

<style scoped>
    .Main {
        margin:60px auto;
        width: 1210px;
        height:100%;
    }
    .left-panel {
        margin-top:10px;
    }
    .right-panel {
        float: right;
        margin-top:10px;
        padding-left:10px;
    }
    .Card-title {
        font-weight: 700;
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
    .WorkLists {
        min-height:362px;
    }
    .UserLists {
        min-height:362px;
    }
    .EmployerLists {
        min-height:362px;
    }
    .WorkItem {
        border: none;
        padding-bottom:20px;
        margin-bottom:20px;
    }
</style>
