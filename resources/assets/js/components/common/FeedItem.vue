<template>
    <el-card class="FeedCard" :body-style="bodyStyle">
        <div class="Feed">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        {{"来自话题"}}
                        <span>
                            <router-link to="/tags/id" class="TagLink">{{"家教助教"}}</router-link>
                        </span>
                    </span>
                    <span class="Feed-time">{{fromNow(work.updated_at)}}</span>
                </div>
            </div>
            <div class="EmployerInfo">
                <span class="EmployerLink">
                    <router-link :to="'/employer/' + user.id">
                        <EmployerPopover :employer="user" pic-width="30"></EmployerPopover>
                    </router-link>
                </span>
                <div class="EmployerInfo-content">
                    <div class="EmployerInfo-header">
                        <span class="EmployerLink Employer-name">
                            <EmployerPopover :employer="user" text="true"></EmployerPopover>
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
            <div class="ContentItem">
                <h2 class="ContentItem-title"><router-link :to="/work/ + work.id">{{work.title}}</router-link></h2>
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
                        <div class="Work-button-group">
                            <el-button class="button-plain"><i class="fa fa-comment fa-fw Icon"></i>&nbsp;{{12}}条咨询</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain"><i class="fa fa-share-square-o fa-fw Icon"></i>&nbsp;分享</el-button>
                        </div>
                        <div class="Work-button-group">
                            <el-button class="button-plain"><i class="fa fa-address-card-o fa-fw Icon"></i>&nbsp;邀请好友</el-button>
                        </div>
                    </el-col>
                    <el-col :span="7">
                        <div class="WorkHeader-side">
                            <div class="WorkButtonGroup">
                                <el-button type="primary">申请兼职</el-button>
                                <el-button>收藏兼职</el-button>
                            </div>
                        </div>
                    </el-col>
                </div>
            </div>
        </div>
    </el-card>
</template>

<script>
    import {mapState} from 'vuex';
    import {dateFromNow} from '../../util/format';
    import EmployerPopover from './Popover/EmployerPopover.vue'
    export default {
        name:'FeedItem',
        components:{EmployerPopover},
        props: {
            feed: {
                type: Object,
                requier: true
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
                user: JSON.parse(window.localStorage.user),
                show: false,
                treat_star: 4.2,
                pay_speed: 4,
                description_match: 4.6,
                total_star: 4.4
            }
        },
        methods: {
            fromNow: function (date) {
                return dateFromNow(date);
            }
        },
        computed: mapState ({
            work: state => state.work
        }),
        created:function () {
            this.$store.dispatch('workGet',1);
        }
    }
</script>

<style scoped>
    .FeedCard {
        margin-bottom: 10px;
    }
    .Feed {
        display: block;
    }
    .Feed-title {
        margin-bottom:14px;
    }
    .Feed-meta {
        color: #8590a6;
        line-height: 1;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }
    .Feed-meta-item {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .Feed-time {

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
        padding-top:10px;
        flex: 1;
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
        float: left;
        width: 100px;
        color: #999;
        font-size:15px;
    }
    .ContentItem {
        margin-top: 14px;
        margin-bottom:10px;
    }
    .ContentItem-title {
        font-size: 18px;
        font-weight: 700;
        line-height: 1.6;
        color: #1e1e1e;
        margin-top: -4px;
        margin-bottom: -4px;
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
</style>