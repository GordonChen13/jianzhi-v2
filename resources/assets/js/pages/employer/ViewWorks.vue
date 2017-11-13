<template>
    <div class="Container">
        <TopNavbar></TopNavbar>
        <LeftNavbar></LeftNavbar>
        <div class="Main">
            <el-col :span="18" class="CenterPanel">
                <el-card class="WorkLists-card">
                    <div class="Work-Topbar" slot="header">
                        <div class="WorkTopbar-title">
                            <h2 class="Topbar-title">我的兼职</h2>
                        </div>
                    </div>
                    <el-tabs v-model="activeWorkTab" @tab-click="handleStatusChange">
                        <el-tab-pane label="待审核" name="待审核" ref="checking">
                            <div class="Work-lists" v-if="checkingWorks.length > 0" >
                                <el-table :data="checkingWorks" style="width: 100%" stripe max-height="500">
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
                                    <el-table-column label="兼职名称" prop="title" align="center" width="300"></el-table-column>
                                    <el-table-column label="雇佣人数" prop="hired_num" align="center"></el-table-column>
                                    <el-table-column label="需要人数" prop="need_num" align="center"></el-table-column>
                                    <el-table-column label="申请人数" prop="applicants_count" align="center"></el-table-column>
                                    <el-table-column label="收藏人数" prop="favorite_user_count" align="center"></el-table-column>
                                    <el-table-column fixed="right" label="操作" width="200" align="center">
                                        <template scope="props">
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">查看</el-button></router-link>
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">编辑</el-button></router-link>
                                            <el-button type="text" size="small" @click="deleteCheckingWork(props.row.id)">删除</el-button>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                    <span>暂时还没有待审核的兼职</span>
                                    <router-link to="/employer/works/create">，快点击这里发布兼职吧</router-link>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="审核未通过" name="审核未通过" ref="failed">
                            <div class="Work-lists" v-if="failedWorks.length > 0" >
                                <el-table :data="failedWorks" style="width: 100%" stripe max-height="500">
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
                                    <el-table-column label="兼职名称" prop="title" align="center" width="300"></el-table-column>
                                    <el-table-column label="雇佣人数" prop="hired_num" align="center"></el-table-column>
                                    <el-table-column label="需要人数" prop="need_num" align="center"></el-table-column>
                                    <el-table-column label="未通过原因" prop="check_failed_msg" width="300" align="center"></el-table-column>
                                    <el-table-column fixed="right" label="操作" width="200" align="center">
                                        <template scope="props">
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">查看</el-button></router-link>
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">编辑</el-button></router-link>
                                            <el-button type="text" size="small" @click="deleteFailedWork(props.row.id)">删除</el-button>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                    <span>暂时还没有审核未通过的兼职</span>
                                    <router-link to="/employer/works/create">，快点击这里发布兼职吧</router-link>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="进行中" name="进行中" ref="applying">
                            <div class="Work-lists" v-if="applyingWorks.length > 0" >
                                <el-table :data="applyingWorks" style="width: 100%" stripe max-height="500">
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
                                    <el-table-column label="兼职名称" prop="title" align="center" width="300"></el-table-column>
                                    <el-table-column label="雇佣人数" prop="hired_num" align="center"></el-table-column>
                                    <el-table-column label="需要人数" prop="need_num" align="center"></el-table-column>
                                    <el-table-column label="申请人数" prop="applicants_count" align="center"></el-table-column>
                                    <el-table-column label="收藏人数" prop="favorite_user_count" align="center"></el-table-column>
                                    <el-table-column fixed="right" label="操作" width="300" align="center">
                                        <template scope="props">
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">查看</el-button></router-link>
                                            <el-popover class="InvitePopover" width="600" trigger="click" @show="getFollowing">
                                                <el-button type="text" size="small" slot="reference">邀请好友</el-button>
                                                <el-tabs v-model="activeUserTab" @tab-click="handleTabChange">
                                                    <el-tab-pane label="我关注的用户" name="following" ref="following">
                                                        <el-input placeholder="输入用户昵称进行搜索" icon="search" v-model="followingSearch" class="SearchInput">
                                                        </el-input>
                                                        <div class="EmptyState" v-if="following.length == 0">
                                                            <div class="EmptyState-inner">
                                                                <i class="fa fa-users EmptyState-icon"></i>
                                                                <span>暂时还没有关注的人</span>
                                                            </div>
                                                        </div>
                                                        <div class="UserLists" v-else-if="followingSearch == ''">
                                                            <UserFollowList v-for="user in following" :user="user" :work="props.row"  action="invite" class="UserList"></UserFollowList>
                                                        </div>
                                                        <div class="UserLists" v-else>
                                                            <div class="EmptyState" v-if="sortedFollowing.length == 0">
                                                                <div class="EmptyState-inner">
                                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                                    <span>找不到该昵称的用户</span>
                                                                </div>
                                                            </div>
                                                            <UserFollowList v-else v-for="user in sortedFollowing" :user="user" :work="props.row"  action="invite" class="UserList"></UserFollowList>
                                                        </div>
                                                    </el-tab-pane>
                                                    <el-tab-pane label="关注我的用户" name="follower" ref="follower">
                                                        <el-input placeholder="输入用户昵称进行搜索" icon="search" v-model="followerSearch" class="SearchInput">
                                                        </el-input>
                                                        <div class="EmptyState" v-if="follower.length == 0">
                                                            <div class="EmptyState-inner">
                                                                <i class="fa fa-users EmptyState-icon"></i>
                                                                <span>暂时还没有人关注你</span>
                                                            </div>
                                                        </div>
                                                        <div class="UserLists" v-else-if="followerSearch == ''">
                                                            <UserFollowList v-for="user in follower" :user="user" :work="props.row"  action="invite" class="UserList"></UserFollowList>
                                                        </div>
                                                        <div class="UserLists" v-else>
                                                            <div class="EmptyState" v-if="sortedFollower.length == 0">
                                                                <div class="EmptyState-inner">
                                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                                    <span>找不到该昵称的用户</span>
                                                                </div>
                                                            </div>
                                                            <UserFollowList v-else v-for="user in sortedFollower" :work="props.row"  :user="user" action="invite" class="UserList"></UserFollowList>
                                                        </div>
                                                    </el-tab-pane>
                                                </el-tabs>
                                            </el-popover>
                                            <router-link to="/employer/applicant/manage"><el-button type="text" size="small">申请人管理</el-button></router-link>
                                            <el-button type="text" size="small" @click="endApply(props.row.id)">结束报名</el-button>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                    <span>暂时还没有进行中的兼职</span>
                                    <router-link to="/employer/works/create">，快点击这里发布兼职吧</router-link>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="待评价" name="待评价" ref="reviewing">
                            <div class="Work-lists" v-if="reviewingWorks.length > 0" >
                                <el-table :data="reviewingWorks" style="width: 100%" stripe max-height="500">
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
                                    <el-table-column label="兼职名称" prop="title" align="center" width="300"></el-table-column>
                                    <el-table-column label="雇佣人数" prop="hired_num" align="center"></el-table-column>
                                    <el-table-column label="需要人数" prop="need_num" align="center"></el-table-column>
                                    <el-table-column label="申请人数" prop="applicants_count" align="center"></el-table-column>
                                    <el-table-column label="收藏人数" prop="favorite_user_count" align="center"></el-table-column>
                                    <el-table-column fixed="right" label="操作" width="300" align="center">
                                        <template scope="props">
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">查看</el-button></router-link>
                                            <router-link to="/employer/applicant/review"><el-button type="text" size="small">申请人评价</el-button></router-link>
                                            <el-button type="text" size="small" @click="finishWork(props.row.id)">结束兼职</el-button>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                    <span>暂时还没有待评价的兼职</span>
                                    <router-link to="/employer/works/create">，快点击这里发布兼职吧</router-link>
                                </div>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="已结束" name="已结束" ref="finished">
                            <div class="Work-lists" v-if="finishedWorks.length > 0" >
                                <el-table :data="finishedWorks" style="width: 100%" stripe max-height="500" :default-sort = "{prop: 'created_at', order: 'descending'}">
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
                                    <el-table-column label="发布日期" prop="created_at" align="center" width="200" sortable></el-table-column>
                                    <el-table-column label="兼职名称" prop="title" align="center" width="300"></el-table-column>
                                    <el-table-column label="需要人数" prop="need_num" align="center"></el-table-column>
                                    <el-table-column label="申请人数" prop="applicants_count" align="center"></el-table-column>
                                    <el-table-column label="收藏人数" prop="favorite_user_count" align="center"></el-table-column>
                                    <el-table-column fixed="right" label="操作" width="200" align="center">
                                        <template scope="props">
                                            <router-link :to="{name:'work',params:{id:props.row.id}}"><el-button type="text" size="small">查看</el-button></router-link>
                                        </template>
                                    </el-table-column>
                                </el-table>
                            </div>
                            <div class="EmptyState" v-else>
                                <div class="EmptyState-inner">
                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                    <span>暂时还没有已结束的兼职</span>
                                    <router-link to="/employer/works/create">，快点击这里发布兼职吧</router-link>
                                </div>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
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
    import _ from 'lodash';
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import UserFollowList from '../../components/common/Follow/UserFollowList.vue';
    import LeftNavbar from '../../components/employer/LeftNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import ToDos from '../../components/employer/ToDos.vue';
    import {mapState} from 'vuex';
    export default {
        name:'CreatWork',
        components:{TopNavbar,LeftNavbar,NoticeBoard,ToDos,UserFollowList},
        data() {
            return {
                user:JSON.parse(localStorage.user),
                works:[],
                checkingWorks:[],
                failedWorks:[],
                applyingWorks:[],
                reviewingWorks:[],
                finishedWorks:[],
                activeWorkTab:'进行中',
                inviteDialogShow:false,
                activeUserTab:'following',
                following:[],
                sortedFollowing:[],
                followingLoading:true,
                followingSearch:'',
                follower:[],
                sortedFollower:[],
                followerLoading:true,
                followerSearch:''

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
                    }
                })
                .catch(function (error) {
                    console.log("error",error);
                });
            },
            handleStatusChange:function (tab,event) {
                if ( tab == this.$refs.checking) {
                    if (this.checkingWorks.length == 0) {
                        this.getCheckingWorks();
                    }
                } else if ( tab == this.$refs.failed) {
                    if (this.failedWorks.length == 0) {
                        this.getFailedWorks();
                    }
                } else if ( tab == this.$refs.applying) {
                    if (this.applyingWorks.length == 0) {
                        this.getApplyingWorks();
                    }
                } else if ( tab == this.$refs.reviewing) {
                    if (this.reviewingWorks.length == 0) {
                        this.getReviewingWorks();
                    }
                } else  {
                    if (this.finishedWorks.length == 0) {
                        this.getFinishedWorks();
                    }
                }
            },
            getCheckingWorks:function () {
                let that = this;
                that.$axios.get('/api/employer/works?status=0').then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.checkingWorks = response.data.works;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getFailedWorks:function () {
                let that = this;
                this.$axios.get('/api/employer/works?status=-1').then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.failedWorks = response.data.works;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getApplyingWorks:function () {
                let that = this;
                that.$axios.get('/api/employer/works?status=1').then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.applyingWorks = response.data.works;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getReviewingWorks:function () {
                let that = this;
                this.$axios.get('/api/employer/works?status=2').then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.reviewingWorks = response.data.works;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getFinishedWorks:function () {
                let that = this;
                this.$axios.get('/api/employer/works?status=3').then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.finishedWorks = response.data.works;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            deleteCheckingWork:function (id) {
                let that = this;
                this.$confirm('此操作将永久删除该兼职, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/api/employer/works/' + id).then( response => {
                        return new Promise( (resolve,reject) => {
                            if (response.data.status ==1) {
                                resolve(response.data);
                                that.$message.success(response.data.msg);
                                let index = that.$_.findIndex(that.checkingWorks,function (item) {
                                    return item.id == id;
                                })
                                that.checkingWorks.splice(index,1);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            deleteFailedWork:function (id) {
                let that = this;
                this.$confirm('此操作将永久删除该兼职, 是否继续?', '提示', {
                    confirmButtonText: '确定',
                    cancelButtonText: '取消',
                    type: 'warning'
                }).then(() => {
                    this.$axios.delete('/api/employer/works/' + id).then( response => {
                        return new Promise( (resolve,reject) => {
                            if (response.data.status ==1) {
                                resolve(response.data);
                                that.$message.success(response.data.msg);
                                let index = that.$_.findIndex(that.failedWorks,function (item) {
                                    return item.id == id;
                                })
                                that.failedWorks.splice(index,1);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }).catch(() => {
                    this.$message({
                        type: 'info',
                        message: '已取消删除'
                    });
                });
            },
            endApply:function (id) {
                let that = this;
                this.$axios.put('/api/employer/works/' + id,{
                    status:2
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.$message.success(response.data.msg);
                            let index = that.$_.findIndex(that.applyingWorks,function (item) {
                                return item.id == id;
                            })
                            that.applyingWorks.splice(index,1);
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            finishWork:function (id) {
                let that = this;
                this.$axios.put('/api/employer/works/' + id,{
                    status:3
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.$message.success(response.data.msg);
                            let index = that.$_.findIndex(that.reviewingWorks,function (item) {
                                return item.id == id;
                            })
                            that.reviewingWorks.splice(index,1);
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getFollowing:function () {
                let that = this;
                if (localStorage.user) {
                    axios.get('/api/user/followings?status=21').then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 0) {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            } else {
                                resolve(response.data);
                                that.following = response.data.users;
                                console.log(that.following)
                            }
                        })
                    })
                }
            },
            getFollower:function () {
                let that = this;
                if (localStorage.user) {
                    axios.get('/api/user/followers?status=12').then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 0) {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            } else {
                                resolve(response.data);
                                that.follower = response.data.users;
                            }
                        })
                    })
                }
            },
            handleTabChange:function (tab,event) {
                if (tab == this.$refs.following) {
                    if ((this.following).length == 0) {
                        this.getFollowing();
                    }
                } else {
                    if ((this.follower).length == 0) {
                        this.getFollower();
                    }
                }
            },
            getSearchFollowing:function(query) {
            let that = this;
            if (localStorage.user) {
                this.$axios.get('/api/users', {
                    params: {
                        search: query
                    }
                }).then(response => {
                    return new Promise((resolve, reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.sortedFollowing = response.data.users;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            }
            },
            getSearchFollower:function(query) {
                let that = this;
                if (localStorage.user) {
                    this.$axios.get('/api/users', {
                        params: {
                            search: query
                        }
                    }).then(response => {
                        return new Promise((resolve, reject) => {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.sortedFollower = response.data.users;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            }
        },
        watch:{
            followingSearch:_.debounce(function (newVal) {
                this.getSearchFollowing(newVal);
            },1000),
            followerSearch:_.debounce(function (newVal) {
                this.getSearchFollower(newVal);
            },1000)
        },
        created: function () {
            this.getApplyingWorks();
        }
    }
</script>

<style scoped>
    .Main {
        margin-top: 60px;
        margin-left:200px;
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
    .Work-lists {
        margin-top: 20px;
    }
    .EmptyState {
        padding: 50px 0;
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
    .el-table .el-button {
        margin-right:5px;
        font-size:15px;
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