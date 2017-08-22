<template>
    <div class="container">
        <div class="nav">
            <Navbar></Navbar>
        </div>
        <div class="WorkHeader">
            <div class="WorkHeader-content">
                <div class="WorkHeader-main">
                    <div class="WorkHeader-topics">
                        <el-tag class="Tag WorkTopic" type="primary" v-for="tag in work.tags"><span class="Tag-content"><a class="TopicLink" href="">{{tag.name}}</a></span></el-tag>
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
                                <div class="NumberBoard-value">99</div>
                            </div>
                            <div class="NumberBoard-divider"></div>
                            <div class="NumberBoard-item">
                                <div class="NumberBoard-name">被浏览</div>
                                <div class="NumberBoard-value">9999</div>
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
                            <div class="WorkHeader-button-group">
                                <el-button class="button-plain"><i class="fa fa-comment fa-fw Icon"></i>&nbsp;{{12}}条咨询</el-button>
                            </div>
                            <div class="WorkHeader-button-group">
                                <el-button class="button-plain"><i class="fa fa-share-square-o fa-fw Icon"></i>&nbsp;分享</el-button>
                            </div>
                            <div class="WorkHeader-button-group">
                                <el-button class="button-plain"><i class="fa fa-address-card-o fa-fw Icon"></i>&nbsp;邀请好友</el-button>
                            </div>
                        </div>
                    </div>
                    <div class="WorkHeader-side">
                        <div class="WorkButtonGroup">
                            <el-button type="primary">申请兼职</el-button>
                            <el-button>收藏兼职</el-button>
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
                <el-card class="Question-card">
                    <div class="Question-topbar">
                        <div class="QuestionTopbar-title">
                            <h2 class="Topbar-title">{{12}}条咨询</h2>
                        </div>
                        <div class="Topbar-options">
                            <el-button class="button-plain">默认排序</el-button>
                            <el-button class="button-plain">最新排序</el-button>
                        </div>
                    </div>
                    <div class="QuestionList">
                        <div class="QuestionItem">
                            <div class="QuestionItem-meta">
                                <span class="QuestionItem-avatar">
                                    <router-link :to="/employer/ +work.employer.id" class="User-link">
                                        <img  :src="'/images/users/avatar/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
                                    </router-link>
                                </span>
                                <span class="User-link">
                                    <a href="" class="User-name">{{work.employer.name}}</a>
                                </span>
                                <span class="QuestionItem-time">1周前</span>
                            </div>
                            <div class="QuestionItem-content">
                                请问工资什么时候发，中午有休息时间吗？
                            </div>
                            <div class="QuestionItem-footer">
                                <el-button class="button-plain"><i class="fa fa-thumbs-up"></i>&nbsp;8</el-button>
                            </div>
                        </div>
                        <div class="QuestionItem">
                            <div class="QuestionItem-meta">
                                <span class="QuestionItem-avatar">
                                    <a href="" class="User-link">
                                        <img  :src="'/images/users/avatar/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
                                    </a>
                                </span>
                                <span class="User-link">
                                    <a href="" class="User-name">{{work.employer.name}}</a>
                                </span>
                                <span class="QuestionItem-time">1周前</span>
                            </div>
                            <div class="QuestionItem-content">
                                请问工资什么时候发，中午有休息时间吗？
                            </div>
                            <div class="QuestionItem-footer">
                                <el-button class="button-plain"><i class="fa fa-thumbs-up"></i>&nbsp;8</el-button>
                            </div>
                        </div>
                        <div class="QuestionItem">
                            <div class="QuestionItem-meta">
                                <span class="QuestionItem-avatar">
                                    <a href="" class="User-link">
                                        <img  :src="'/images/users/avatar/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
                                    </a>
                                </span>
                                <span class="User-link">
                                    <a href="" class="User-name">{{work.employer.name}}</a>
                                </span>
                                <span class="QuestionItem-time">1周前</span>
                            </div>
                            <div class="QuestionItem-content">
                                请问工资什么时候发，中午有休息时间吗？
                            </div>
                            <div class="QuestionItem-footer">
                                <el-button class="button-plain"><i class="fa fa-thumbs-up"></i>&nbsp;8</el-button>
                            </div>
                        </div>
                        <div class="QuestionItem">
                            <div class="QuestionItem-meta">
                                <span class="QuestionItem-avatar">
                                    <a href="" class="User-link">
                                        <img  :src="'/images/users/avatar/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
                                    </a>
                                </span>
                                <span class="User-link">
                                    <a href="" class="User-name">{{work.employer.name}}</a>
                                </span>
                                <span class="QuestionItem-time">1周前</span>
                            </div>
                            <div class="QuestionItem-content">
                                请问工资什么时候发，中午有休息时间吗？
                            </div>
                            <div class="QuestionItem-footer">
                                <el-button class="button-plain"><i class="fa fa-thumbs-up"></i>&nbsp;8</el-button>
                            </div>
                        </div>
                        <div class="QuestionItem">
                            <div class="QuestionItem-meta">
                                <span class="QuestionItem-avatar">
                                    <a href="" class="User-link">
                                        <img  :src="'/images/users/avatar/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
                                    </a>
                                </span>
                                <span class="User-link">
                                    <a href="" class="User-name">{{work.employer.name}}</a>
                                </span>
                                <span class="QuestionItem-time">1周前</span>
                            </div>
                            <div class="QuestionItem-content">
                                请问工资什么时候发，中午有休息时间吗？
                            </div>
                            <div class="QuestionItem-footer">
                                <el-button class="button-plain"><i class="fa fa-thumbs-up"></i>&nbsp;8</el-button>
                            </div>
                        </div>

                    </div>
                    <div class="Question-footer">
                        <el-input placeholder="请输入内容" v-model="question">
                            <el-button type="primary" slot="append"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;提问</el-button>
                        </el-input>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="8" class="right-panel">
                <el-card class="Employer-card">
                    <div class="Employer-title" slot="header">
                        <span>关于发布者</span>
                    </div>
                    <div class="Card-section">
                        <div class="Employer-info">
                            <div class="Employer-avatar">
                                <router-link :to="'/employer/' + work.employer.id"><img  :src="'/images/users/avatar/' + work.employer.pic_path" width="60px;" height="60px" alt="图片被外星人劫持啦！"></router-link>
                            </div>
                            <div class="Employer-content">
                                <div class="Employer-name">
                                    <span><router-link :to="'/employer/' + work.employer.id">{{work.employer.name}}</router-link></span>
                                </div>
                                <div class="Employer-introduction">{{work.employer.introduction}}</div>
                            </div>
                        </div>
                    </div>
                    <div class="Card-section">
                        <div class="Employer-counts">
                            <div class="NumberBoard">
                                <a href="" class="NumberBoard-item">
                                    <el-button class="button-plain">
                                        <div class="NumberBoard-name">兼职</div>
                                        <div class="NumberBoard-value">66</div>
                                    </el-button>
                                </a>
                                <a href="" class="NumberBoard-item">
                                    <el-button class="button-plain">
                                        <div class="NumberBoard-name">评分</div>
                                        <div class="NumberBoard-value">4.8</div>
                                    </el-button>
                                </a>
                                <a href="" class="NumberBoard-item">
                                    <el-button class="button-plain">
                                        <div class="NumberBoard-name">关注者</div>
                                        <div class="NumberBoard-value">666</div>
                                    </el-button>
                                </a>
                            </div>
                        </div>
                        <div class="Button-group">
                            <el-button type="primary" class="Employer-button"><i class="fa fa-plus fa-fw"></i>&nbsp;关注他</el-button>
                            <el-button class="Employer-button"><i class="fa fa-comments-o fa-fw"></i>&nbsp;发私信</el-button>
                        </div>
                    </div>
                </el-card>
                <el-card class="Question-card SimilarWorks-card">
                    <div class="Question-topbar SimilarWorks-topbar" slot="header">
                        <div class="QuestionTopbar-title">
                            <h2 class="Topbar-title">相似兼职推荐</h2>
                        </div>
                        <div class="Topbar-options">
                            <a href=""><el-button class="button-plain">更多兼职&nbsp;<i class="fa fa-hand-o-right"></i></el-button></a>
                        </div>
                    </div>
                    <div class="QuestionList SimilarWorksList">
                        <div class="NoSimilarWorks" v-if="similarWorks.length == 0">暂时还没有类似的兼职</div>
                        <div class="QuestionItem SimilarWorksItem" v-else v-for="work in similarWorks">
                            <el-row class="SimilarWork-title"><a :href="'#/work/' + work.id" target="_blank">{{work.title}}</a></el-row>
                            <el-row class="SimilarWork-info">
                                <el-col :span="12" class="SimilarWork-localtion">{{work.city}}--{{work.district}}</el-col>
                                <el-col :span="12"><span  class="SimilarWork-PayTime">{{work.pay_time}}</span></el-col>
                            </el-row>
                            <el-row class="SimilarWork-pay">
                                <el-col :span="12"><span class="pay-amount">{{work.pay_amount}}</span><span class="settlement-type">{{work.settlement_type}}</span></el-col>
                                <el-col :span="12"><span  class="SimilarWork-PayTime">{{work.pay_type}}</span></el-col>
                                </el-row>
                        </div>
                    </div>
                </el-card>
            </el-col>
        </div>
    </div>
</template>

<script>
    import axios from 'axios';
    import Navbar from '../../components/user/Navbar.vue';
    import * as types from '../../store/mutation-types';
    import {mapState} from 'vuex';
    export default {
        name: 'WorkDetail',
        components: {Navbar},
        data() {
            return {
                similarWorks: [],
                question:null
            }
        },
        computed: mapState ({
            work: state => state.work,
            user: state => state.user
        }),
        methods: {
            getSimilarWorks (data) {
                let that = this;
                axios({
                    url: '/api/works',
                    method: 'get',
                    params: {
                        tag: data.tags[0].name
                    }
                }).then(function (response) {
                    let data = response.data;
                    if (data.status == 0) {
                        alert(data.msg);
                    } else {
                        that.similarWorks = data.works;
                        console.log(['similarWorks',data.works]);
                    }
                }).catch(function (error) {
                    console.log(error);
                });
            }
        },
        created: function () {
            this.$store.dispatch('workGet',this.$route.params.id).then((data) => {
                console.log('curent work',data);
                this.getSimilarWorks(data);
            });
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
        padding-right: 10px;
        text-align: right;
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
    .WorkHeader-button-group {
        margin-right:20px;
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
    .QuestionItem-meta {
        position: relative;
        height: 27px;
        padding-right: 3px;
        padding-left: 1px;
        margin-bottom: 5px;
        line-height: 24px;
    }
    .QuestionItem-avatar {
        margin-right:8px;
        vertical-align: top;
    }
    .User-name {
        vertical-align: top;;
    }
    .QuestionItem-time {
        float: right;
        font-size: 14px;
        color: #8590a6;
    }
    .QuestionItem-content {
        margin-bottom: 6px;
        line-height: 25px;
    }
    .QuestionItem-footer {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        height: 24px;
        font-size: 14px;
        line-height: 24px;
    }
    .Question-footer {
        padding: 12px 16px;
        display: inline-block;
        height: 24px;
        background: #fff;
        border-top: 1px solid #ebeef5;
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
    }
    .Employer-content {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
    }
    .Employer-name {
        font-size: 20px;
        font-weight: 700;
        line-height: 30px;
        color: #1e1e1e;
    }
    .Employer-introduction {
        font-size: 14px;
        line-height: 20px;
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
    .SimilarWork-info {
        color: #93a1bb;
        font-size:14px;
    }
    .SimilarWorksList {
        height:500px;
    }
    [v-cloak] {
        display: none;
    }
</style>