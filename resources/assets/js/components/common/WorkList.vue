<template>
        <el-card class="WorkItem" :body-style="bodyStyle" v-if="employer && size == 'medium'">
            <div class="Work">
                <div class="ContentItem">
                    <div class="ContentItem-header">
                        <h2 class="ContentItem-title"><router-link :to="/work/ + work.id">{{work.title}}</router-link></h2>
                        <span class="WorkTime" v-if="timeShow">{{fromNow(work.created_at)}}</span>
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
                                <div class="NoReview DetailStars" v-if="employer.reviews_count == 0">
                                    <span class="Star-title" style="margin-left: 20px;">综合评分&nbsp;:</span><span style="font-size: 15px;color:999">暂无评分</span>
                                </div>
                                <el-popover  placement="bottom"  trigger="hover" v-else>
                                    <div class="DetailStars" slot="reference">
                                        <span class="Star-title" style="margin-left: 20px;">综合评分&nbsp;:</span>
                                        <el-rate v-model="employer.total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                    <div class="ReviewCount">
                                        <router-link :to="'/employer/' + employer.id + '/reviews'">
                                            <span class="ReviewCount-text">来自&nbsp;{{employer.reviews_count}}&nbsp;份评价</span>
                                        </router-link>
                                    </div>
                                    <div class="DetailStars">
                                        <span class="Star-title">薪资待遇&nbsp;:</span>
                                        <el-rate v-model="employer.treat_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                    <div class="DetailStars">
                                        <span class="Star-title">描述相符&nbsp;:</span>
                                        <el-rate v-model="employer.description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                    <div class="DetailStars">
                                        <span class="Star-title">工资发放速度&nbsp;:</span>
                                        <el-rate v-model="employer.pay_speed" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
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
                                        <el-col :span="10"><i class="fa fa-male fa-fw"></i>&nbsp;&nbsp;已录/需要人数：<span class="HiredNum">{{work.hired_num}}</span> / <span class="NeedNum">{{work.need_num}}</span></el-col>
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
                                <el-button class="Button-center" type="text" @click="showMoreInfo"><i class="fa fa-angle-down fa-fw"></i>&nbsp;展开更多信息&nbsp;<i class="fa fa-angle-down fa-fw"></i></el-button>
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
                            <div class="SharePopover">
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
                                    <router-link :to="'/work/' + work.id" style="margin-right:50px;">
                                        <el-button>查看兼职</el-button>
                                    </router-link>
                                </div>
                                <div class="WorkButtonGroup" v-else>
                                    <div class="DoneWork" v-if="work.status > 1">
                                        <div class="AppliedWork" v-if="applied">
                                            <el-button type="primary" :disabled="true" style="width: 88px" v-if="isReviewed">已评价</el-button>
                                            <el-button type="primary" @click="reviewDialogShow = true" v-else>评价兼职</el-button>
                                        </div>
                                        <el-button type="primary" :disabled="true" style="width: 88px" v-else>已结束</el-button>
                                    </div>
                                    <div class="WorkingWork" v-else>
                                        <el-button type="danger" v-if="applied" @click="unApplyWork">取消申请</el-button>
                                        <el-button type="primary" v-else @click="applyWork">申请兼职</el-button>
                                    </div>
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
                    <el-dialog title="对该兼职的评价" :visible.sync="reviewDialogShow" class="ReviewDialog">
                        <el-form :model="reviewForm" :rules="reviewRules" ref="reviewForm" label-width="120px">
                            <el-row>
                                <el-col :span="8">
                                    <el-form-item label="薪资待遇" prop="treat_star">
                                        <el-rate class="Form-rate" v-model="reviewForm.treat_star" show-text></el-rate>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="描述相符" prop="description_match">
                                        <el-rate class="Form-rate" v-model="reviewForm.description_match" show-text></el-rate>
                                    </el-form-item>
                                </el-col>
                                <el-col :span="8">
                                    <el-form-item label="工资发放速度" prop="pay_speed">
                                        <el-rate class="Form-rate" v-model="reviewForm.pay_speed" show-text></el-rate>
                                    </el-form-item>
                                </el-col>
                            </el-row>
                            <el-form-item label="标签印象" prop="text">
                                <el-select v-model="reviewForm.keywords" multiple placeholder="请输入关键字搜索标签"multiple filterable remote
                                           :remote-method="getReviewKeywords" :loading="keywordsLoading" style="width: 400px">
                                    <el-option v-for="item in searchedKeywords" :key="item.id" :label="item.value" :value="item.id"></el-option>
                                </el-select>
                            </el-form-item>
                            <el-form-item label="评价内容" prop="text">
                                <el-input v-model="reviewForm.text" type="textarea" :rows="3" placeholder="请在这里输入详细的评价内容" style="width: 80%"></el-input>
                            </el-form-item>
                            <el-form-item label="感谢雇主" prop="isThanks">
                                <el-button type="text" v-if="reviewForm.isThanks" @click="reviewForm.isThanks = !reviewForm.isThanks"><i class="fa fa-heart ThanksIcon" style="color: rgba(192, 11, 25, 0.55)"></i></el-button>
                                <el-button type="text" v-else @click="reviewForm.isThanks = !reviewForm.isThanks"><i class="fa fa-heart-o ThanksIcon"></i></el-button>
                            </el-form-item>
                            <el-form-item label="兼职照片">
                                <el-upload ref="pictureUpload" action="/api/employer/review/picture" list-type="picture-card" :on-preview="handlePictureCardPreview"
                                           :headers="uploadConfig.headers" name="picture" :data="{work_id:work.id,employer_id:employer.id}"
                                           :multiple="true" :on-success="handleUploadSuccess" :on-error="handleUploadError" accept=".jpg,.png"
                                           :before-upload="beforePictureUpload":on-remove="handlePictureRemove" :auto-upload="false">
                                    <i class="el-icon-plus" style="margin-top:60px;"></i>
                                </el-upload>
                            </el-form-item>
                            <el-form-item>
                                <el-button type="primary" @click="submitForm('reviewForm')">提交评价</el-button>
                                <el-button @click="resetForm('reviewForm')">重置</el-button>
                            </el-form-item>
                        </el-form>
                    </el-dialog>
                    <el-dialog v-model="pictureDialogVisible">
                        <img width="100%" :src="dialogImageUrl" alt="">
                    </el-dialog>
                </div>
            </div>
        </el-card>
        <div class="QuestionItem SimilarWorksItem" v-else-if="size == 'tiny'">
            <el-row class="SimilarWork-title"><router-link :to="{name:'work',params:{id:work.id}}">{{work.title}}</router-link></el-row>
            <el-row class="SimilarWork-info">
                <el-col :span="12" class="SimilarWork-localtion">{{work.city}}--{{work.district}}</el-col>
                <el-col :span="12"><span  class="SimilarWork-PayTime">{{work.pay_time}}</span></el-col>
            </el-row>
            <el-row class="SimilarWork-pay">
                <el-col :span="12"><span class="pay-amount">{{work.pay_amount}}</span><span class="settlement-type">{{work.settlement_type}}</span></el-col>
                <el-col :span="12"><span  class="SimilarWork-PayTime">{{work.pay_type}}</span></el-col>
            </el-row>
        </div>
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
            },
            timeShow: {
                default:true
            },
            size:{
                type:String,
                default:'medium'
            }
        },
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                show: false,
                loginShow: false,
                root: [ROOT],
                applied:false,
                isReviewed:false,
                favorited:false,
                employer:null,
                searchedKeywords:[],
                keywordsLoading:null,
                questionCardShow: false,
                activeTab:"following",
                inviteCardShow:false,
                reportDialogShow:false,
                reviewDialogShow:false,
                following:[],
                sortedFollowing:[],
                followingLoading:true,
                followingSearch:'',
                follower:[],
                sortedFollower:[],
                followerLoading:true,
                followerSearch:'',
                report_type:1,
                uploadConfig: {
                    headers:{
                        Authorization: 'bearer' + localStorage.token
                    }
                },
                fileList:[],
                reviewForm:{
                    treat_star: null,
                    pay_speed: null,
                    description_match: null,
                    text:null,
                    keywords:[],
                    isThanks:false,
                    work_id: this.work ? this.work.id : null,
                    employer_id: this.employer ? this.employer.id : null
                },
                reviewRules:{
                    treat_star: [
                        { type:'integer',required: true, message: '请给工作态度打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    pay_speed: [
                        { type:'integer',required: true, message: '请给工作能力打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    description_match: [
                        { type:'integer',required: true, message: '请给描述相符打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    text: [
                        {type:"string",trigger:"change"},
                        { required: true, message: '请输入具体的评价', trigger: 'blur' }
                    ],
                },
                dialogImageUrl: '',
                pictureDialogVisible: false
            }
        },
        methods: {
            fromNow: function (date) {
                return dateFromNow(date);
            },
            showMoreInfo: function () {
                this.show = !this.show;
                //增加兼职的页面浏览量
                this.$axios.get('/api/works/' + this.work.id).then( (res) => {

                })
            },
            getEmployer: function () {
                let that = this;
                return new Promise(function (resolve, reject) {
                    axios.get('/api/employers/'+ that.work.employer_id).then(function (response) {
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
                    return new Promise(function (resolve, reject) {
                        axios.get('/api/user/appliedstatus?work_id=' + that.work.id).then(function (response) {
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
            handlePictureCardPreview:function(file) {
                this.dialogImageUrl = file.url;
                this.pictureDialogVisible = true;
            },
            handlePictureRemove:function (file,fileList) {
                console.log(file,fileList)
            },
            submitForm:function (name) {
                this.reviewForm.employer_id = this.employer.id;
                console.log(this.reviewForm);
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        let that = this;
                        this.$axios.post('/api/employer/reviews',this.reviewForm).then(function (response) {
                            return new Promise( function (resolve, reject) {
                                if (response.data.status == 1) {
                                    resolve(response.data);
                                    that.$refs.pictureUpload.submit();
                                    that.$message.success(response.data.msg);
                                } else {
                                    reject(response.data);
                                    that.$message.error(response.data.msg);
                                }
                            })
                        }).catch(function (error) {
                            that.$message.error(error.msg)});
                    } else {
                        that.$message.error('选项不能为空');
                        return false;
                    }
                });
            },
            resetForm:function (name) {
                this.$refs[name].resetFields();
                this.$refs.pictureUpload.clearFiles();
            },
            handleUploadSuccess:function (response, file, fileList) {
                if (fileList.indexOf(file) == fileList.length -1) {
                    this.$message.success('评价成功');
                    this.reviewDialogShow = false;
                    this.isReviewed = true;
                }
            },
            handleUploadError:function (error, file, fileList) {
                this.$messgae.error(error.msg);
            },
            beforePictureUpload:function (file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;
                console.log([isJPG + isPNG == 0,'hajhah'])
                if (isJPG + isPNG == 0) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            getReviewStatus:function () {
                let that = this;
                if (this.work) {
                    this.$axios.get('/api/employer/review/status',{
                        params:{
                            work_id: that.work.id,
                            employer_id: that.employer.id
                        }
                    }).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.isReviewed = response.data.reviewed;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    }).catch(function (error) {
                        that.$message.error(error.msg);
                    })
                }
            },
            getReviewKeywords:function (query) {
                let total_star = (this.reviewForm.treat_star + this.reviewForm.pay_speed + this.reviewForm.description_match) / 3;
                let type= 'a';
                let that = this;
                if (total_star > 3.7) {
                    type = 'a';
                } else if (total_star < 2.3) {
                    type = 'c';
                } else {
                    type = 'b';
                }
                if (!query == '') {
                    that.keywordsLoading = true;
                    this.$axios.get('/api/employer/review/keywords',{
                        params:{
                            type: type,
                            search:query
                        }
                    }).then( res => {
                        if (res.data.status ==1) {
                            that.searchedKeywords = res.data.keywords;
                            that.keywordsLoading = false;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    }).catch(err => {
                        console.log(err);
                    })
                } else {
                    this.$axios.get('/api/employer/review/keywords',{
                        params:{
                            type: type
                        }
                    }).then( res => {
                        if (res.data.status ==1) {
                            that.searchedKeywords = res.data.keywords;
                            that.keywordsLoading = false;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    }).catch(err => {
                        console.log(err);
                    })
                }
            },
            init:function () {
                let that = this;
                if (this.size !== 'tiny') {
                    Promise.all([that.getEmployer(),that.checkApplied()]).then(function () {
                        if (that.work.status > 1 && that.applied) {
                            that.getReviewStatus();
                        }
                    })
                    this.checkFavorite();
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
            this.init();
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
        margin-right:20px;
        margin-bottom:10px;
    }
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
        font-size:15px;
    }
    .ReviewCount {
        display: flex;
        justify-content: center;
        margin-top: 5px;
        margin-bottom:15px;
    }
    .ReviewCount-text {
        font-size: 15px;
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
        display: inline-flex;
        margin: 0 -8px;
    }
    .WorkButtonGroup .el-button {
        margin-right: 10px;
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
    .ReviewDialog .Form-rate {
        margin-top:6px;
    }
    .ThanksIcon {
        font-size: 25px;
        margin-top: -5px;
        margin-left: 15px;
    }

    .QuestionItem {
        border-top: 1px solid #f0f2f7;
        position: relative;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        padding: 12px 16px 10px;
        font-size: 15px;
    }
    .QuestionItem:first-child {
        border-top: none;
    }

    .SimilarWorks-card {
        margin-top:10px;
        margin-left:10px;
        height:600px;
    }
    .SimilarWork-title {
        color: #175199;
    }
    .SimilarWork-PayTime {
        float: right;
        color: #93a1bb;
        font-size:14px;
    }
    .pay-amount {
        color: #FF7900;
        font-size:16px;
        margin-right:5px;
    }
    .settlement-type {
        font-size:14px;
        color: #93a1bb;
    }
</style>

