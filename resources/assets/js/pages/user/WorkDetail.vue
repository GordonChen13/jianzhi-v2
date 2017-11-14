<template>
    <div class="container" v-if="work">
        <div class="nav">
            <Navbar active-index="2"></Navbar>
        </div>
        <div class="WorkHeader">
            <div class="WorkHeader-content">
                <div class="WorkHeader-main">
                    <div class="WorkHeader-topics">
                        <el-tag class="Tag WorkTopic" type="primary" v-for="tag in work.tags"><span class="Tag-content"><router-link class="TopicLink" :to="{name:'works',query:{tag:tag.name}}">{{tag.name}}</router-link></span></el-tag>
                    </div>
                    <h1 class="WorkHeader-title">{{work.title}}</h1>
                    <div class="WorkHeader-skills">
                        <span>技能要求：</span>
                        <el-tag class="WorkSkill"  type="primary" v-for="skill in work.skills"><span class="Tag-content"><a class="TopicLink" href="">{{skill.name}}</a></span></el-tag>
                    </div>
                </div>
                <div class="WorkHeader-side">
                    <div class="WorkHeader-follow-status">
                        <div class="NumberBoard WorkFollowStatus-counts">
                            <div class="NumberBoard-item">
                                <div class="NumberBoard-name">收藏者</div>
                                <div class="NumberBoard-value">{{work.favorite_user_count}}</div>
                            </div>
                            <div class="NumberBoard-divider"></div>
                            <div class="NumberBoard-item">
                                <div class="NumberBoard-name">被浏览</div>
                                <div class="NumberBoard-value">{{work.page_view}}</div>
                            </div>
                        </div>
                        <div class="NumberBoard Pay-content">
                            <span class="PayAmount">{{work.pay_amount}}</span>&nbsp;{{work.settlement_type}}
                        </div>
                    </div>
                </div>
            </div>
            <div class="WorkHeader-footer">
                <div class="WorkHeader-footer-inner">
                    <div class="WorkHeader-main WorkHeader-footer-main">
                        <div class="WorkHeaderActions">
                            <div class="SharePopover">
                                <el-popover ref="SharePopover" placement="bottom" title="扫一扫分享兼职" width="100" trigger="click">
                                    <VueQrcode :value="root + '/work/' + work.id" style="margin: 10px 25px 10px"></VueQrcode>
                                </el-popover>
                            </div>
                            <div class="Work-button-group">
                                <el-button class="button-plain" v-if="questionCardShow == false" @click="GoToQuestionCard"><i class="fa fa-comments-o fa-fw Icon"></i>&nbsp;{{work.questions_count}}条咨询</el-button>
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
                        </div>
                    </div>
                    <div class="WorkHeader-side">
                        <div class="SelfAction" v-if="me !== null && me.id == work.employer_id">
                            <router-link to="/employer/works/" style="margin-right:50px;">
                                <el-button type="primary">兼职管理</el-button>
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
                </div>
            </div>
        </div>
        <div class="Work-main">
            <el-col :span="16" class="left-panel">
                <el-card class="WorkDetail">
                    <div class="WorkDetail-content">
                        <div class="WorkDetail-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;兼职内容</div>
                        <div class="WorkDetail-info">
                            <el-row class="list-item">
                                <el-col :span="12"><i class="fa fa-calendar-o fa-fw"></i>&nbsp;&nbsp;工作日期：{{work.start_date}}至{{work.end_date}}</el-col>
                                <el-col :span="12"><i class="fa fa-clock-o fa-fw"></i>&nbsp;&nbsp;工作时间：{{work.start_time}}--{{work.end_time}}</el-col>
                            </el-row>
                            <el-row class="list-item">
                                <el-col :span="12"><i class="fa fa-map-marker fa-fw"></i>&nbsp;&nbsp;工作地区：{{work.city}}-{{work.district}}</el-col>
                                <el-col :span="12"><i class="fa fa-map-o fa-fw"></i>&nbsp;&nbsp;详细地址：{{work.address}}</el-col>
                            </el-row>
                            <el-row class="list-item">
                                <el-col :span=24><i class="fa fa-th-large fa-fw"></i>&nbsp;&nbsp;工作内容：{{work.description}}</el-col>
                            </el-row>
                        </div>
                        <div class="WorkDetail-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;薪资待遇</div>
                        <div class="WorkDetail-info">
                            <el-row class="list-item" v-if="work.has_commission == 0">
                                <el-col :span="12"><i class="fa fa-line-chart fa-fw"></i>&nbsp;&nbsp;提成： 无</el-col>
                            </el-row>
                            <el-row class="list-item" v-else>
                                <el-col :span="12"><i class="fa fa-line-chart fa-fw"></i>&nbsp;&nbsp;提成： 有</el-col>
                                <el-col :span="12"><i class="fa fa-tasks fa-fw"></i>&nbsp;&nbsp;提成方式： {{work.commission}}</el-col>
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
                            <el-row class="list-item">
                                <el-col :span="12"><i class="fa fa-credit-card fa-fw"></i>&nbsp;&nbsp;工资支付方式：{{work.pay_type}}</el-col>
                                <el-col :span="12"><i class="fa fa-calendar-check-o fa-fw"></i>&nbsp;&nbsp;工资发放时间：{{work.pay_time}}</el-col>
                            </el-row>
                        </div>
                        <div class="WorkDetail-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;工作要求</div>
                        <div class="WorkDetail-info">
                            <el-row class="list-item">
                                <el-col :span="12"><i class="fa fa-users fa-fw"></i>&nbsp;&nbsp;需要人数：{{work.hired_num}}/{{work.need_num}}</el-col>
                                <el-col :span="12"><i class="fa fa-mars fa-fw"></i>&nbsp;&nbsp;性别要求：{{work.gender}}</el-col>
                            </el-row>
                            <el-row class="list-item" v-if="work.need_interview == 0">
                                <el-col :span="12"><i class="fa fa-coffee fa-fw"></i>&nbsp;&nbsp;需要面试： 不</el-col>
                            </el-row>
                            <el-row class="list-item" v-else>
                                <el-col :span="12"><i class="fa fa-coffee fa-fw"></i>&nbsp;&nbsp;需要面试： 是</el-col>
                            </el-row>
                            <el-row class="list-item">
                                <el-col :span=24><i class="fa fa-th-list fa-fw"></i>&nbsp;&nbsp;工作要求：{{work.requirements}}</el-col>
                            </el-row>
                        </div>
                    </div>
                </el-card>
                <QuestionCard :work="work" v-if="work" id="QuestionCard" style="height: auto;"></QuestionCard>
            </el-col>
            <el-col :span="8" class="right-panel">
                <el-card class="Employer-card">
                    <div class="Employer-title" slot="header">
                        <span>关于发布者</span>
                    </div>
                    <div class="Card-section">
                        <div class="Employer-info">
                            <div class="Employer-avatar">
                                <router-link :to="'/employer/' + work.employer.id"><img  :src="'/storage/' + work.employer.pic_path" width="60px;" height="60px" alt="图片被外星人劫持啦！"></router-link>
                            </div>
                            <div class="Employer-content">
                                <div class="Employer-header">
                                    <div class="Employer-name">
                                        <span><router-link :to="'/employer/' + work.employer.id">{{work.employer.name}}</router-link></span>
                                    </div>
                                    <div class="Certificated" v-if="employer.certificated">
                                        <span class="fa-stack fa-lg CertificateIcon">
                                            <i class="fa fa-certificate fa-stack-2x" style="color:rgb(247, 186, 42) "></i>
                                            <i class="fa fa-check fa-stack-1x" style="color: white"></i>
                                        </span>
                                        <span class="NumberBoard-name">(已实名认证)</span>
                                    </div>
                                </div>
                                <div class="NoReview DetailStars" v-if="employer.reviews_count == 0">
                                    <span class="Star-title">综合评分&nbsp;:</span><span style="font-size: 15px;color:999">暂无评分</span>
                                </div>
                                <el-popover  placement="right"  trigger="hover" v-else>
                                    <div class="DetailStars " style="margin-left: 0px; margin-top: 10px; display:inline-flex" slot="reference">
                                        <span class="Star-title All-Star">综合评分&nbsp;:</span>
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
                    <div class="Card-section">
                        <div class="Employer-counts">
                            <div class="NumberBoard">
                                <div class="NumberBoard-item">
                                    <router-link :to="'/employer/' + employer.id + '/works'">
                                        <div class="NumberBoard-name">兼职数</div>
                                        <div class="NumberBoard-value">{{employer.checked_works_count}}</div>
                                    </router-link>
                                </div>
                                <div class="NumberBoard-divider"></div>
                                <div class="NumberBoard-item">
                                    <div class="NumberBoard-name">经验值</div>
                                    <div class="NumberBoard-value">{{employer.employer_exp}}</div>
                                </div>
                                <div class="NumberBoard-divider"></div>
                                <div class="NumberBoard-item">
                                    <router-link :to="'/employer/' + employer.id + '/following'">
                                        <div class="NumberBoard-name">粉丝量</div>
                                        <div class="NumberBoard-value">{{employer.user_followers_count}}</div>
                                    </router-link>
                                </div>
                            </div>
                        </div>
                        <div class="ButtonGroup">
                            <div class="FollowAction" v-if="followStatus == false">
                                <el-button type="primary" @click="followEmployer" class="ActionButton" v-if="employer.gender == '男'"><i class="fa fa-plus fa-fw WhiteIcon"></i>&nbsp;&nbsp;关注他</el-button>
                                <el-button type="primary" @click="followEmployer" class="ActionButton" v-else><i class="fa fa-plus fa-fw WhiteIcon"></i>&nbsp;&nbsp;关注她</el-button>
                            </div>
                            <div class="FollowAction" v-else>
                                <el-button type="danger" @click="unFollowEmployer" class="ActionButton"><i class="fa fa-user-times fa-fw WhiteIcon"></i>&nbsp;&nbsp;取消关注</el-button>
                            </div>
                            <el-button class="ActionButton"><i class="fa fa-comments-o fa-fw"></i>&nbsp;&nbsp;发私信</el-button>
                        </div>
                    </div>
                </el-card>
                <el-card class="SimilarWorks-card">
                    <div class="Question-topbar SimilarWorks-topbar" slot="header">
                        <div class="QuestionTopbar-title">
                            <h2 class="Topbar-title">相似兼职推荐</h2>
                        </div>
                        <div class="Topbar-options">
                            <router-link to="/works"><el-button class="button-plain">更多兼职&nbsp;<i class="fa fa-hand-o-right"></i></el-button></router-link>
                        </div>
                    </div>
                    <div class="SimilarWorksList">
                        <div class="NoSimilarWorks" v-if="similarWorks.length == 0">暂时还没有类似的兼职</div>
                        <div v-else>
                            <WorkList v-for="work in similarWorks" :work="work" size="tiny"></WorkList>
                        </div>
                    </div>
                </el-card>
            </el-col>
        </div>
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
        <el-dialog class="InviteCard" title="快邀请好友一起兼职吧" :visible.sync="inviteCardShow">
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
        </el-dialog>
        <el-dialog v-model="pictureDialogVisible">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
    </div>
</template>

<script>
    import {ROOT} from '../../util/config';
    import _ from 'lodash';
    import axios from 'axios';
    import Navbar from '../../components/user/Navbar.vue';
    import QuestionCard from '../../components/common/Card/QuestionCard.vue';
    import WorkList from '../../components/common/WorkList.vue';
    import LoginDialog from '../../components/common/Dialog/LoginDialog.vue';
    import UserFollowList from '../../components/common/Follow/UserFollowList.vue';
    import VueQrcode from 'vue-qrcode';
    import * as types from '../../store/mutation-types';
    import {mapState} from 'vuex';
    export default {
        name: 'WorkDetail',
        components: {Navbar,QuestionCard,WorkList,LoginDialog,VueQrcode,UserFollowList},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                similarWorks: [],
                work:null,
                question:null,
                employer:null,
                loginShow:false,
                followStatus:false,root: [ROOT],
                applied:false,
                isReviewed:false,
                favorited:false,
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
        computed: mapState ({
            user: state => state.user
        }),
        methods: {
            GoToQuestionCard:function () {
                var anchor = this.$el.querySelector('#QuestionCard');
                document.body.scrollTop = anchor.offsetTop;
            },
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            getWork:function (id) {
                let that = this;
                return new Promise( (resolve,reject) => {
                    this.$axios.get('/api/works/' + id).then( function (res) {
                        if (res.data.status == 1) {
                            that.work = res.data.work;
                            that.employer = that.work.employer;
                            resolve(res.data.work);
                        } else {
                            that.$message.error(res.data.msg);
                            reject(res.data);
                        }
                    }).catch( (err) => {
                        reject(err);
                    })
                })
            },
            getSimilarWorks (id) {
                let that = this;
                axios.get('/api/works?similar=true&id=' + id).then(function (response) {
                    let data = response.data;
                    if (data.status == 0) {
                        that.$message.error(data.msg);
                    } else {
                        that.similarWorks = data.works;
                    }
                }).catch(function (error) {
                    console.log('getSimilarWorkError',error);
                });
            },
            checkFollowstatus:function () {
                if (localStorage.user) {
                    let that = this;
                    axios.get('/api/user/followingcheck?to_id=' + that.employer.id + '&status=12').then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.followStatus = response.data.follow;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            followEmployer:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.post('/api/user/followings',{
                        to_id: that.employer.id,
                        status: 12
                    }).then(function (response) {
                        return new Promise(function (resovle, reject) {
                            if (response.data.status == 1) {
                                resovle(response.data);
                                that.$message.success(response.data.msg);
                                that.followStatus = true;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                                that.followStatus = false;
                            }
                        })
                    })
                }
            },
            unFollowEmployer:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.delete('/api/user/followings/' + that.employer.id +'?status=12').then(function (response) {
                        return new Promise(function (resovle, reject) {
                            if (response.data.status == 1) {
                                resovle(response.data);
                                that.followStatus = false;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
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
            },
            init:function () {
                let that = this;
                this.getWork(this.$route.params.id).then( (work) => {
                    that.checkFollowstatus();
                    that.checkApplied().then( (data) => {
                        if (that.work.status > 1 && that.applied) {
                            that.getReviewStatus();
                        }
                    });
                });
                this.getSimilarWorks(this.$route.params.id);
            }
        },
        watch:{
            $route:function (newRoute) {
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
    .container {
        width: 100%;
        height:100%;
        background-color: #f7f8fa;
        display: block;
    }
    .nav {
        height: 60px;
    }
    .Work-main {
        margin: 10px auto;
        width: 1210px;
        height:100%;
    }
    .WorkDetail-title {
        font-size: 18px;
        font-weight: 600;
        margin-top:10px;
        margin-bottom:10px;
    }
    .WorkDetail-title:first-child {
        margin-top: 0px;
    }
    .blue {
        color: #316d9e;
    }
    .WorkDetail-info {
        margin-top:10px;
        margin-left:10px;
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
    .Icon {
         color: #9fadc7;
    }
    .WorkHeader {
        position: relative;
        width:100%;
        padding: 16px 0;
        overflow: hidden;
        background: #fff;
        border-bottom: 1px solid #e7eaf1;
        box-shadow: 0 1px 3px 0 rgba(0,37,55,.05);
    }
    .WorkHeader-content {
        width:1210px;
        display: flex;
        -webkit-box-pack: justify;
        justify-content: space-between;
        padding: 0 16px;
        margin: 0 auto;
    }
    .WorkHeader-main {
        width: 694px;
        padding-left: 20px;
        box-sizing: border-box;
    }
    .WorkHeader-topics {
        display: flex;
        -webkit-box-orient: horizontal;
        -webkit-box-direction: normal;
        -ms-flex-direction: row;
        flex-direction: row;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .WorkHeader-skills {
        margin-top:5px;
        font-weight: 200;
    }
    .WorkTopic {
        margin: 3px 5px 3px 0;
        vertical-align: middle;
    }
    .WorkSkill {
        margin: 3px 10px 3px 0;
        vertical-align: middle;
    }
    .Tag {
        position: relative;
        display: inline-block;
        height: 30px;
        padding: 0 12px;
        font-size: 14px;
        line-height: 30px;
        color: #3e7ac2;
        vertical-align: top;
        background: #eef4fa;
        border-radius: 100px;
    }
    .Tag-content {

    }
    .TopicLink {
        color: inherit;
        text-decoration: none;
    }
    .WorkHeader-side {
        width: 296px;
        padding-right: 100px;
        text-align: right;
    }
    .WorkButtonGroup {
        display: inline-block;
        margin: 0 -8px;
    }
    .DoneWork {
        display: inherit;
        margin-right:10px;
    }
    .WorkingWork {
        display: inherit;
        margin-right:10px;
    }
    .WorkHeader-follow-status {
        dispaly:block;
        margin-top:10px;
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
    .All-Star {
        width: 80px;
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
        margin-top: 10px;
    }
    .PayAmount {
        font-size: 24px;
        font-weight: 800;
        color: #FF7900;
    }
    .WorkHeader-footer {
        padding-bottom: 12px;
        height:34px;
        margin-top: 4px;
        margin-bottom: -12px;
        background: #fff;
    }
    .WorkHeader-footer-inner {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        width: 1210px;
        height: 100%;
        padding: 0 16px;
        margin: 0 auto;
    }
    .WorkHeader-footer-main {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }
    .WorkHeaderActions {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
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
    .WorkHeader-title {
        margin-top: 12px;
        margin-bottom: 4px;
        font-size: 22px;
        font-weight: 400;
        line-height: 32px;
        color: #1e1e1e;
    }
    .list-item {
        margin-bottom: 5px;
    }
    .CheckBox-group {
        display: inline-block;
    }
    .Question-card {
        margin-top:10px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        overflow: hidden;
        box-sizing: border-box;
        height: 498px;
    }
    .Question-topbar {
        background: #fff;
        border-bottom: 1px solid #f0f2f7;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        padding-bottom: 10px;
    }
    .SimilarWorks-topbar {
        padding-bottom:0px;
        border-bottom:none;
    }
    .QuestionTopbar-title {
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
    .QuestionList {
        height:380px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow-x: hidden;
        overflow-y: scroll;
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
    .right-panel {
        float: right;
    }
    .Employer-card {
        margin-left: 10px;
    }
    .Employer-title {
        padding: 0px -10px;
        overflow: hidden;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .Card-section {
        padding: 16px 20px;
        position: relative;
    }
    .Card-section:first-child {
        padding-top: 0;
        padding-left:0;
    }
    .Card-section:not(:last-child):after {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        margin: 0 20px;
        display: block;
        border-bottom: 1px solid #f0f2f7;
        content: "";
    }
    .Employer-info {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
    }
    .Employer-avatar {
        margin-right:12px;
        margin-top: -15px;
    }
    .Employer-content {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
    }
    .Employer-header {
        display: inline-flex;
    }
    .CertificateIcon {
        margin-left:10px;
        font-size: 10px
    }
    .Employer-name {
        font-size: 20px;
        font-weight: 700;
        line-height: 30px;
        color: #1e1e1e;
    }
    .Certificated {
        margin-top:5px;
    }
    .Employer-counts {
        font-size: 14px;
        text-align: center;
    }
    .Button-group {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-top: 16px;
    }
    .Employer-button {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        width:96px;
    }
    .WhiteIcon {
        color: white;;
    }
    .SimilarWorks-card {
        margin-top:10px;
        margin-left:10px;
        height:600px;
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
    .SimilarWork-info {
        color: #93a1bb;
        font-size:14px;
    }
    .SimilarWorksList {
        height:500px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow-x: hidden;
        overflow-y: scroll;
    }
    .ButtonGroup {
        margin-top:20px;
        display: flex;
        justify-content: center;
    }
    .ActionButton {
        width: 150px;
    }
    .ActionButton:first-child {
        margin-right:20px;
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
    [v-cloak] {
        display: none;
    }
</style>