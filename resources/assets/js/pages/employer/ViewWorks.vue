<template>
    <div class="Container">
        <TopNavbar></TopNavbar>
        <div class="Main">
            <el-col :span="3" class="LeftPanel">
                <LeftNavbar></LeftNavbar>
            </el-col>
            <el-col :span="15" class="CenterPanel">
                <el-card class="WorkLists-card">
                    <div class="Work-Topbar" slot="header">
                        <div class="WorkTopbar-title">
                            <h2 class="Topbar-title">我的兼职</h2>
                        </div>
                        <div class="Topbar-options">
                            <el-button class="button-plain">待审核</el-button>
                            <el-button class="button-plain">进行中</el-button>
                            <el-button class="button-plain">待评价</el-button>
                            <el-button class="button-plain">已结束</el-button>
                        </div>
                    </div>
                    <div v-cloak v-if="works.length == 0"><h2>暂时还没有兼职，快去<a href="#/employer/works/create">发布</a>一个吧</h2></div>
                    <div class="Work-lists" v-else >
                        <el-table :data="works" style="width: 100%" stripe max-height="500">
                            <el-table-column type="expand">
                                <template scope="props">
                                    <el-form label-position="left" inline class="work-table-expand">
                                        <el-form-item label="工作日期"><span>{{ props.row.start_date }}--{{ props.row.end_date}}</span></el-form-item>
                                        <el-form-item label="工作时间"><span>{{ props.row.start_time }}--{{ props.row.end_time}}</span></el-form-item>
                                        <el-form-item label="工作地点"><span>{{ props.row.city }}--{{  props.row.district}}</span></el-form-item>
                                        <el-form-item label="详细地址"><span>{{ props.row.address }}</span></el-form-item>
                                        <el-form-item label="薪资待遇"><span>{{ props.row.pay_amount }}&nbsp;{{ props.row.settlement_type}}</span></el-form-item>
                                        <el-form-item label="性别要求"><span>{{ props.row.gender }}</span></el-form-item>
                                        <el-form-item label="工作要求"><span>{{ props.row.requirements }}</span></el-form-item>
                                    </el-form>
                                </template>
                            </el-table-column>
                            <el-table-column label="兼职 ID" prop="id" align="center"></el-table-column>
                            <el-table-column label="兼职名称" prop="title" align="center"></el-table-column>
                            <el-table-column label="雇佣人数" prop="hired_num" align="center"></el-table-column>
                            <el-table-column label="需要人数" prop="need_num" align="center"></el-table-column>
                            <el-table-column label="浏览人数" prop="page_view" align="center"></el-table-column>
                            <el-table-column label="收藏人数" prop="status" align="center"></el-table-column>
                            <el-table-column fixed="right" label="操作" width="100">
                                <template scope="props">
                                    <router-link :to="'works/'+ props.row.id"><el-button @click="132" type="text" size="small">查看</el-button></router-link>
                                    <router-link :to="'works/'+ props.row.id +'/edit'"><el-button type="text" size="small">编辑</el-button></router-link>
                                </template>
                            </el-table-column>
                        </el-table>
                        <!--<div class="WorkItem">-->
                            <!--<div class="Work-title">{{work.title}}</div>-->
                            <!--<div class="Work-info"></div>-->
                        <!--</div>-->
                    </div>
                </el-card>
            </el-col>
            <el-col :span="6" class="RightPanel">
                <el-row>
                    <ToDos></ToDos>
                </el-row>
                <el-row>
                    <NoticeBoard></NoticeBoard>
                </el-row>
            </el-col>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import LeftNavbar from '../../components/employer/LeftNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import ToDos from '../../components/employer/ToDos.vue';
    import {mapState} from 'vuex';
    export default {
        name:'CreatWork',
        components:{TopNavbar,LeftNavbar,NoticeBoard,ToDos},
        data() {
            return {
                user:JSON.parse(localStorage.user),
                works:[]
            }
        },
        methods: {
            getMyWorks (status) {
                let that = this;
                axios({
                    url: '/api/employer/'+this.user.id+'/works',
                    method: 'get',
                    params: {
                        status:status
                    }
                }).then(function (response) {
                    let data = response.data;
                    if (data.status == 0) {
                        console.log(["msg",data.msg]);
                    } else {
                        that.works = data.works;
                        console.log(["works",data.works]);
                    }
                })
                .catch(function (error) {
                    console.log("error",error);
                });
            }
        },
        created: function () {
            this.getMyWorks(0);
        }
    }
</script>

<style scoped>
    .Main {
        margin-top: 60px;
        background-color: #F5F5F6;
    }
    .button-plain {
        height: auto;
        padding: 0;
        line-height: inherit;
        background-color: transparent;
        border: none;
        border-radius: 0;
        color: #748aa2;
    }
    .WorkLists-card {
        margin-top: 20px;
        margin-left:20px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        overflow: hidden;
        box-sizing: border-box;
    }
    .Work-Topbar {
        background: #fff;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding-bottom:0px;
        border-bottom:none;
    }
    .WorkTopbar-title {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .Topbar-title {
        display: inline-block;
        font-size: 15px;
        font-weight: 700;
        color: #1e1e1e;
        margin:0;
    }
    .Topbar-options {
        float: right;
        margin-right:20px;
    }
    .work-table-expand {
        font-size: 0;
    }
    .work-table-expand label {
        width: 90px;
        color: #99a9bf;
    }
    .work-table-expand .el-form-item {
        margin-right: 0;
        margin-bottom: 0;
        width: 50%;
    }
     .el-form--inline .el-form-item__label {
        float: left;
    }
    .WorkItem {
        border-top: 1px solid #f0f2f7;
        position: relative;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        padding: 12px 16px 10px;
        font-size: 15px;
    }
    .WorkItem:first-child {
        border-top: none;
    }
    [v-cloak] {
        display:none;
    }
</style>