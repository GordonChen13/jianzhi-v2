<template>
    <el-card class="WorkItem" :body-style="bodyStyle">
        <div class="Work">
            <div class="ContentItem">
                <div class="ContentItem-header">
                    <h2 class="ContentItem-title"><router-link :to="/work/ + work.id">{{work.title}}</router-link></h2>
                    <span class="WorkTime">{{fromNow(work.updated_at)}}</span>
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
                                    <el-col :span="10"><i class="fa fa-male fa-fw"></i>&nbsp;&nbsp;需要人数：{{work.hired_num}}/{{work.need_num}}</el-col>
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
                                        <div class="NumberBoard WorkFollowStatus-counts">
                                            <div class="NumberBoard-item">
                                                <div class="NumberBoard-name">收藏量</div>
                                                <div class="NumberBoard-value">99</div>
                                            </div>
                                            <div class="NumberBoard-divider"></div>
                                            <div class="NumberBoard-item">
                                                <div class="NumberBoard-name">浏览量</div>
                                                <div class="NumberBoard-value">9999</div>
                                            </div>
                                        </div>
                                        <div class="NumberBoard Pay-content">
                                            <span class="PayAmount">{{work.pay_amount}}</span>&nbsp;{{work.settlement_type}}
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
                            <el-button class="button-plain" v-if="questionCardShow == false" @click="questionCardShow = !questionCardShow"><i class="fa fa-comment fa-fw Icon"></i>&nbsp;{{questions.length}}条咨询</el-button>
                            <el-button class="button-plain" v-else @click="questionCardShow = !questionCardShow"><i class="fa fa-comment fa-fw Icon"></i>&nbsp;收起咨询</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain" v-popover:SharePopover><i class="fa fa-share-square-o fa-fw Icon"></i>&nbsp;分享</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain"><i class="fa fa-address-card-o fa-fw Icon"></i>&nbsp;邀请好友</el-button>
                        </div>
                    </el-col>
                    <el-col :span="7">
                        <div class="WorkHeader-side">
                            <div class="WorkButtonGroup">
                                <el-button v-if="work.status > 1" type="primary" :disabled="true" style="width: 88px">已结束</el-button>
                                <el-button type="primary" v-else @click="applyWork">申请兼职</el-button>
                                <el-button>收藏兼职</el-button>
                            </div>
                        </div>
                    </el-col>
                </div>
                <div class="QuestionCard" v-if="questionCardShow">
                    <QuestionCard :work="work"></QuestionCard>
                </div>
                <LoginDialog :show.sync ="loginShow"></LoginDialog>
            </div>
        </div>
    </el-card>
</template>

<script>
    import {ROOT} from '../../util/config';
    import {mapState} from 'vuex';
    import {dateFromNow} from '../../util/format';
    import axios from 'axios';
    import VueQrcode from 'vue-qrcode';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    import EmployerPopover from '../common/Popover/EmployerPopover.vue';
    import QuestionCard from '../common/Card/QuestionCard.vue';
    export default {
        name:'WorkList',
        components:{EmployerPopover,QuestionCard,VueQrcode,LoginDialog},
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
                treat_star: 4.2,
                pay_speed: 4,
                description_match: 4.6,
                total_star: 4.4,
                employer:null,
                questions:[],
                questionCardShow: false
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
            }
        },
        created: function () {
            this.getEmployer();
            this.getQuestions();
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
    .center {
        margin-top:45px;
    }
    .money {
        font-weight:400 ;
        font-size: 18px;
        color: red;
    }
    .center-button {
        margin-top:40px;
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
        margin-top: 10px;
    }
    .PayAmount {
        font-size: 24px;
        font-weight: 800;
        color: #FF7900;
    }
    .QuestionCard {
        margin-top: 30px;
    }
</style>

