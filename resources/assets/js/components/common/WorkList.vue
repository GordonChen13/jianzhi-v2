<template>
    <el-card class="WorkItem" :body-style="bodyStyle">
        <div class="Work">
            <div class="ContentItem">
                <div class="ContentItem-header">
                    <h2 class="ContentItem-title"><router-link :to="/work/ + work.id">{{work.title}}</router-link></h2>
                    <span class="WorkTime">{{fromNow(work.created_at)}}</span>
                </div>
                <div class="EmployerInfo">
                <span class="EmployerLink">
                    <EmployerPopover :employer="employer" pic-width="30" v-if="employer !== null"></EmployerPopover>
                </span>
                    <div class="EmployerInfo-content">
                        <div class="EmployerInfo-header">
                        <span class="EmployerLink Employer-name">
                            <EmployerPopover :employer="employer" text="true" v-if="employer !== null"></EmployerPopover>
                        </span>
                        </div>
                        <div class="EmployerInfo-detail">
                            <el-popover  placement="bottom"  trigger="hover">
                                <div class="DetailStars" slot="reference">
                                    <span class="Star-title">综合评分&nbsp;:</span>
                                    <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                </div>
                                <div class="DetailStars">
                                    <span class="Star-title">薪资待遇&nbsp;:</span>
                                    <el-rate v-model="treat_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                </div>
                                <div class="DetailStars">
                                    <span class="Star-title">描述相符&nbsp;:</span>
                                    <el-rate v-model="description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                </div>
                                <div class="DetailStars">
                                    <span class="Star-title">工资发放速度&nbsp;:</span>
                                    <el-rate v-model="pay_speed" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                </div>
                            </el-popover>
                        </div>
                    </div>
                </div>
                <div class="WorkInfo">
                    <div class="WorkMain">
                        <el-row class="MainInfo">
                            <el-col :span="17">
                                <el-row class="list-item">
                                    <el-col :span="10"><i class="fa fa-male fa-fw"></i>&nbsp;&nbsp;录用/需要人数：<span class="HiredNum">{{work.hired_num}}</span> / <span class="NeedNum">{{work.need_num}}</span></el-col>
                                    <el-col :span="14"><i class="fa fa-calendar-o fa-fw"></i>&nbsp;&nbsp;工作日期：{{work.start_date}}至{{work.end_date}}</el-col>
                                </el-row>
                                <el-row class="list-item">
                                    <el-col :span="10"><i class="fa fa-mars fa-fw"></i>&nbsp;&nbsp;性别要求：{{work.gender}}</el-col>
                                    <el-col :span="14"><i class="fa fa-clock-o fa-fw"></i>&nbsp;&nbsp;工作时间：{{work.start_time}}--{{work.end_time}}</el-col>
                                </el-row>
                                <el-row class="list-item">
                                    <el-col :span="10"><i class="fa fa-credit-card fa-fw"></i>&nbsp;&nbsp;工资支付方式：{{work.pay_type}}</el-col>
                                    <el-col :span="14"><i class="fa fa-map-marker fa-fw"></i>&nbsp;&nbsp;工作地点：{{work.city}}--{{work.district}}</el-col>
                                </el-row>
                                <el-row class="list-item">
                                    <el-col :span="10"><i class="fa fa-calendar-check-o fa-fw"></i>&nbsp;&nbsp;工资发放时间：{{work.pay_time}}</el-col>
                                    <el-col :span="14"><i class="fa fa-map-o fa-fw"></i>&nbsp;&nbsp;详细地址：{{work.address}}</el-col>
                                </el-row>
                            </el-col>
                            <el-col :span="7">
                                <div class="WorkHeader-side">
                                    <div class="WorkHeader-follow-status">
                                        <div class="NumberBoard Pay-content">
                                            <span class="PayAmount">{{work.pay_amount}}</span>&nbsp;{{work.settlement_type}}
                                        </div>
                                        <div class="NumberBoard WorkFollowStatus-counts">
                                            <div class="NumberBoard-item">
                                                <div class="NumberBoard-name">申请量</div>
                                                <div class="NumberBoard-value">{{work.applicants_count}}</div>
                                            </div>
                                            <div class="NumberBoard-divider"></div>
                                            <div class="NumberBoard-item">
                                                <div class="NumberBoard-name">收藏量</div>
                                                <div class="NumberBoard-value">{{work.favorite_user_count}}</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </el-col>
                        </el-row>
                        <el-row class="MoreButton" v-if="show === false">
                            <el-button class="Button-center" type="text" @click="show = !show "><i class="fa fa-angle-down fa-fw"></i>&nbsp;展开更多信息&nbsp;<i class="fa fa-angle-down fa-fw"></i></el-button>
                        </el-row>
                        <el-row class="DetailInfo" v-if="show === true">
                            <el-col :span="17">
                                <el-row class="list-item">
                                    <el-col :span=24><i class="fa fa-th-large fa-fw"></i>&nbsp;&nbsp;工作内容：{{work.description}}</el-col>
                                </el-row>
                                <el-row class="list-item" v-if="work.has_commission == 0">
                                    <el-col :span="10"><i class="fa fa-line-chart fa-fw"></i>&nbsp;&nbsp;提成： 无</el-col>
                                </el-row>
                                <el-row class="list-item" v-else>
                                    <el-col :span="10"><i class="fa fa-line-chart fa-fw"></i>&nbsp;&nbsp;提成： 有</el-col>
                                    <el-col :span="14"><i class="fa fa-tasks fa-fw"></i>&nbsp;&nbsp;提成方式： {{work.commission}}</el-col>
                                </el-row>
                                <el-row class="list-item">
                                    <el-col :span="24">
                                        <i class="fa fa-cutlery fa-fw"></i>&nbsp;&nbsp;工作餐：
                                        <el-checkbox-group v-model="work.lunch" class="CheckBox-group">
                                            <el-checkbox label="早餐" disabled></el-checkbox>
                                            <el-checkbox label="午餐" disabled></el-checkbox>
                                            <el-checkbox label="晚餐" disabled></el-checkbox>
                                        </el-checkbox-group>
                                    </el-col>
                                </el-row>
                                <el-row class="list-item" v-if="work.need_interview == 0">
                                    <el-col :span="10"><i class="fa fa-coffee fa-fw"></i>&nbsp;&nbsp;需要面试： 不</el-col>
                                </el-row>
                                <el-row class="list-item" v-else>
                                    <el-col :span="10"><i class="fa fa-coffee fa-fw"></i>&nbsp;&nbsp;需要面试： 是</el-col>
                                </el-row>
                                <el-row class="list-item">
                                    <el-col :span=24><i class="fa fa-th-list fa-fw"></i>&nbsp;&nbsp;工作要求：{{work.requirements}}</el-col>
                                </el-row>
                            </el-col>
                        </el-row>
                        <el-row class="MoreButton" v-if="show === true">
                            <el-button class="Button-center" type="text" @click="show = !show "><i class="fa fa-angle-up fa-fw"></i>&nbsp;收起&nbsp;<i class="fa fa-angle-up fa-fw"></i></el-button>
                        </el-row>
                    </div>
                </div>
                <div class="ContentItem-actions">
                    <el-col :span="17">
                        <div class="ShareCard">
                            <el-popover ref="SharePopover" placement="bottom" title="扫一扫分享兼职" width="100" trigger="click">
                                <VueQrcode :value="root + '/work/' + work.id" style="margin: 10px 25px 10px"></VueQrcode>
                            </el-popover>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain" v-if="questionCardShow == false" @click="questionCardShow = !questionCardShow; inviteCardShow = false;"><i class="fa fa-comments-o fa-fw Icon"></i>&nbsp;{{work.questions_count}}条咨询</el-button>
                            <el-button class="button-plain" v-else @click="questionCardShow = !questionCardShow"><i class="fa fa-comments-o fa-fw Icon"></i>&nbsp;收起咨询</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain" v-popover:SharePopover><i class="fa fa-share-square-o fa-fw Icon"></i>&nbsp;分享</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain" v-if="inviteCardShow == false" @click="getFollowing"><i class="fa fa-address-card-o fa-fw Icon"></i>&nbsp;邀请好友</el-button>
                            <el-button class="button-plain" v-else @click="inviteCardShow = false"><i class="fa fa-address-card-o fa-fw Icon"></i>&nbsp;收起邀请</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain" @click="reportDialogShow = true"><i class="fa fa-flag-o fa-fw Icon"></i>&nbsp;举报</el-button>
                        </div>
                    </el-col>
                    <el-col :span="7">
                        <div class="WorkHeader-side">
                            <div class="SelfAction" v-if="me !== null && me.id == work.employer_id">
                                <router-link :to="'/employer/works/' + work.id" style="margin-right:50px;">
                                    <el-button>查看兼职</el-button>
                                </router-link>
                            </div>
                            <div class="WorkButtonGroup" v-else>
                                <el-button v-if="work.status > 1" type="primary" :disabled="true" style="width: 88px">已结束</el-button>
                                <el-button type="danger" v-else-if="applied" @click="unApplyWork">取消申请</el-button>
                                <el-button type="primary" v-else @click="applyWork">申请兼职</el-button>
                                <el-button v-if="favorited" @click="unFavoriteWork" type="warning">取消收藏</el-button>
                                <el-button v-else @click="favoriteWork">收藏兼职</el-button>
                            </div>
                        </div>
                    </el-col>
                </div>
                <div class="QuestionCard" v-if="questionCardShow">
                    <QuestionCard :work="work"></QuestionCard>
                </div>
                <el-card class="InviteCard" v-if="inviteCardShow">
                    <el-tabs v-model="activeTab" @tab-click="handleTabChange">
                        <el-tab-pane label="我关注的用户" name="following" ref="following">
                            <el-input placeholder="输入用户昵称进行搜索" icon="search" v-model="followingSearch" :on-icon-click="handlefollowingSearch" class="SearchInput">
                            </el-input>
                            <div class="EmptyState" v-if="following.length == 0">
                                <div class="EmptyState-inner">
                                    <i class="fa fa-users EmptyState-icon"></i>
                                    <span>暂时还没有关注的人</span>
                                </div>
                            </div>
                            <div class="UserLists" v-else-if="followingSearch == ''">
                                <UserFollowList v-for="user in following" :user="user" :work="work" action="invite" class="UserList"></UserFollowList>
                            </div>
                            <div class="UserLists" v-else>
                                <div class="EmptyState" v-if="sortedFollowing.length == 0">
                                    <div class="EmptyState-inner">
                                        <i class="fa fa-users EmptyState-icon"></i>
                                        <span>找不到该昵称的用户</span>
                                    </div>
                                </div>
                                <UserFollowList v-else v-for="user in sortedFollowing" :user="user" :work="work" action="invite" class="UserList"></UserFollowList>
                            </div>
                        </el-tab-pane>
                        <el-tab-pane label="关注我的用户" name="follower" ref="follower">
                            <el-input placeholder="输入用户昵称进行搜索" icon="search" v-model="followerSearch" :on-icon-click="handlefollowerSearch" class="SearchInput">
                            </el-input>
                            <div class="EmptyState" v-if="follower.length == 0">
                                <div class="EmptyState-inner">
                                    <i class="fa fa-users EmptyState-icon"></i>
                                    <span>暂时还没有人关注你</span>
                                </div>
                            </div>
                            <div class="UserLists" v-else-if="followerSearch == ''">
                                <UserFollowList v-for="user in follower" :user="user" :work="work" action="invite" class="UserList"></UserFollowList>
                            </div>
                            <div class="UserLists" v-else>
                                <div class="EmptyState" v-if="sortedFollower.length == 0">
                                    <div class="EmptyState-inner">
                                        <i class="fa fa-users EmptyState-icon"></i>
                                        <span>找不到该昵称的用户</span>
                                    </div>
                                </div>
                                <UserFollowList v-else v-for="user in sortedFollower" :work="work" :user="user" action="invite" class="UserList"></UserFollowList>
                            </div>
                        </el-tab-pane>
                    </el-tabs>
                </el-card>
                <LoginDialog :show.sync ="loginShow"></LoginDialog>
                <el-dialog :visible.sync="reportDialogShow" size="tiny" title="举报这个兼职">
                    <span>请从下面的选项中选择举报原因：</span>
                    <el-radio-group v-model="report_type" class="ReportGroup">
                        <el-radio :label="1" class="ReportItem">发布虚假信息</el-radio>
                        <el-radio :label="2" class="ReportItem">可能涉及诈骗</el-radio>
                        <el-radio :label="3" class="ReportItem">可能涉及情色交易</el-radio>
                        <el-radio :label="4" class="ReportItem">其它</el-radio>
                    </el-radio-group>
                    <el-button type="primary" class="ReportButton" @click="handleReport">举报</el-button>
                </el-dialog>
            </div>
        </div>
    </el-card>
</template>

<script>
    import {ROOT} from '../../util/config';
    import {mapState} from 'vuex';
    import _ from 'lodash';
    import {dateFromNow} from '../../util/format';
    import axios from 'axios';
    import VueQrcode from 'vue-qrcode';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    import EmployerPopover from '../common/Popover/EmployerPopover.vue';
    import QuestionCard from '../common/Card/QuestionCard.vue';
    import UserFollowList from '../common/Follow/UserFollowList.vue';
    export default {
        name:'WorkList',
        components:{EmployerPopover,QuestionCard,VueQrcode,LoginDialog,UserFollowList},
        props:{
            work: {
                type: Object,
                required: true
            },
            bodyStyle: {
                type: Object,
                default: function () {
                    return { padding: '20px' }
                }
            }
        },
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                show: false,
                loginShow: false,
                root: [ROOT],
                applied:false,
                favorited:false,
                treat_star: 4.2,
                pay_speed: 4,
                description_match: 4.6,
                total_star: 4.4,
                employer:null,
                questionCardShow: false,
                activeTab:"following",
                inviteCardShow:false,
                reportDialogShow:false,
                following:[],
                sortedFollowing:[],
                followingLoading:true,
                followingSearch:'',
                follower:[],
                sortedFollower:[],
                followerLoading:true,
                followerSearch:'',
                report_type:1
            }
        },
        methods: {
            fromNow: function (date) {
                return dateFromNow(date);
            },
            getEmployer: function () {
                let that = this;
                axios.get('/api/employers/'+ that.work.employer_id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            that.employer = response.data.employer;
                            resolve(response.data)
                        } else {
                            this.$message.error(response.data.msg);
                            reject(response.data);
                        }
                    })
                });
            },
            getQuestions: function () {
                let that = this;
                axios.get('/api/questions?work_id=' + that.work.id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.questions = response.data.questions;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            applyWork:function () {
                this.checkLogin();
                if (localStorage.user) {
                    let that = this;
                    axios.post('/api/user/works',{
                        work_id: that.work.id
                    }).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.applied = true;
                                that.work.apply_user_count = that.work.apply_user_count + 1;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            unApplyWork:function () {
                this.checkLogin();
                if (localStorage.user) {
                    let that = this;
                    axios.delete('/api/user/works/' + that.work.id).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.applied = false;
                                that.work.apply_user_count = that.work.apply_user_count - 1;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            checkApplied:function () {
                let that = this;
                if (localStorage.user) {
                    axios.get('/api/user/appliedstatus?work_id=' + that.work.id).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.applied = response.data.applied;
                            } else {
                                reject(response.data);
                                that.applied = false;
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            favoriteWork:function () {
                this.checkLogin();
                if (localStorage.user) {
                    let that = this;
                    axios.post('/api/user/favoriteworks',{
                        work_id: that.work.id
                    }).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.favorited = true;
                                that.work.favorite_user_count = that.work.favorite_user_count + 1;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            unFavoriteWork:function () {
                this.checkLogin();
                if (localStorage.user) {
                    let that = this;
                    axios.delete('/api/user/favoriteworks/' + that.work.id).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.favorited = false;
                                that.work.favorite_user_count = that.work.favorite_user_count - 1;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            checkFavorite:function () {
                let that = this;
                if (localStorage.user) {
                    axios.get('/api/user/favoritestatus?work_id=' + that.work.id).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.favorited = response.data.favorited;
                            } else {
                                reject(response.data);
                                that.favorited = false;
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            getFollowing:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.get('/api/user/followings?status=11').then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 0) {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            } else {
                                resolve(response.data);
                                that.following = response.data.users;
                                that.inviteCardShow = true;
                                that.questionCardShow = false;
                            }
                        })
                    })
                }
            },
            getFollower:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.get('/api/user/followers?status=11').then(function (response) {
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
            handlefollowingSearch:function () {
                console.log('kljsd');
            },
            handlefollowerSearch:function () {
                console.log('kljsd');
            },
            handleReport:function () {
                this.checkLogin();
                let that = this;
                axios.post('/api/user/report',{
                    work_id: that.work.id,
                    report_type:that.report_type
                }).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.reportDialogShow = false;
                            that.$message.success(response.data.msg);
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            init:function () {
                this.getEmployer();
                this.checkApplied();
                this.checkFavorite();
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
            work:function (newVal) {
                this.init();
            },
            followingSearch:_.debounce(function (newVal) {
                this.getSearchFollowing(newVal);
            },1000),
            followerSearch:_.debounce(function (newVal) {
                this.getSearchFollower(newVal);
            },1000)
        },
        created: function () {
            this.getEmployer();
            this.checkApplied();
            this.checkFavorite();
        }
    }
</script>

<style scoped>
    .WorkItem {
        margin-bottom:10px;
    }
    .Work {
        display: block;
    }
    .EmployerInfo {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .EmployerInfo-content {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        padding-top:10px;
        margin-left: 14px;
        overflow: hidden;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: left;
        -ms-flex-align: left;
        align-items: left;
    }
    .EmployerInfo-header {
        font-size: 15px;
        line-height: 1.1;
        -ms-flex-negative: 0;
        flex-shrink: 0;
    }
    .EmployerInfo-detail {
        overflow: hidden;
        display: block;
    }
    .DetailStars {
        display: flex;
        margin-left:20px;
        margin-right:20px;
        margin-bottom:10px;
    }
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
        font-size:15px;
    }
    .ContentItem {
        margin-bottom:10px;
    }
    .ContentItem-header {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }
    .ContentItem-title {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.6;
        color: #1e1e1e;
        margin-top: -4px;
        margin-bottom: 5px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .WorkTime {
        color: #8590a6;

    }
    .WorkInfo {
        margin-top:10px;
        margin-bottom:10px;
    }
    .list-item {
        font-size: 15px;
        margin-top: 5px;
    }
    .HiredNum {
        color: #ff4949;
        font-weight: 500;
    }
    .NeedNum {
        color: #20a0ff;
        font-weight: 500;
    }
    .MoreButton {
        text-align: center;
    }
    .Button-center {
        margin-left:auto;
        color: #8590a6;
        text-align: center;
        cursor: pointer;
    }
    .CheckBox-group {
        display: inline-block;
    }
    .ContentItem-actions {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        height: 30px;
        padding: 0 20px;
        margin: -10px -20px -8px;
        color: #555;
        background: #fff;
        clear: both;
        font-size: 14px;
    }
    .Work-button-group {
        margin-right:20px;
        display: inline;
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
    .WorkHeader-side {
        padding-right: 10px;
        text-align: right;
        margin-top:10px;
    }
    .WorkButtonGroup {
        display: inline-block;
        margin: 0 -8px;
    }
    .WorkHeader-follow-status {
        dispaly:block;
    }
    .NumberBoard {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        text-align: center;
    }
    .WorkFollowStatus-counts {
        width: 200px;
        margin-left: auto;
    }
    .NumberBoard-item {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .NumberBoard-name {
        line-height: 14px;
        font-size: 14px;
        color: #8590a6;
    }
    .NumberBoard-value {
        margin-top: 6px;
        line-height: 18px;
        font-size: 18px;
        font-weight: 500;
        color: #262626;
    }
    .NumberBoard-divider {
        -ms-flex-item-align: stretch;
        -ms-grid-row-align: stretch;
        align-self: stretch;
        border-right: 1px solid #f0f2f7;
    }
    .Pay-content {
        width: 200px;
        margin-left: auto;
        display: block;
        margin-bottom: 30px;
    }
    .PayAmount {
        font-size: 24px;
        font-weight: 800;
        color: #FF7900;
    }
    .QuestionCard {
        margin-top: 30px;
    }
    .InviteCard {
        margin-top:30px;
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
    .UserLists {
        max-height: 500px;
        overflow-y: scroll;
        overflow-x: hidden;
    }
    .SearchInput {
        width:92%;
        margin-left:20px;
    }
    .UserList:not(:last-child) {
        border-bottom: 1px solid #f0f2f7;
    }
    .ReportGroup {
        display: flex;
        flex-direction: column;
        margin-top:20px;
        justify-content: center;
        margin-bottom:10px;
    }
    .ReportItem {
        margin-bottom:10px;
    }
    .ReportButton {
        width: 200px;
        display: block;
        margin: auto;
    }
    .ReportItem:first-child {
        margin-left:16px
    }
</style>

