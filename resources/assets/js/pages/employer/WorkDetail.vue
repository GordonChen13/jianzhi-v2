<template>
    <div class="Container">
        <TopNavbar></TopNavbar>
        <LeftNavbar></LeftNavbar>
        <div class="Main">
            <el-col :span="18" class="CenterPanel">
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
                            <el-row class="list-item">
                                <el-col :span=24><i class="fa fa-th-list fa-fw"></i>&nbsp;&nbsp;工作要求：{{work.requirements}}</el-col>
                            </el-row>
                        </div>
                        <div class="WorkDetail-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;面试信息</div>
                        <div class="WorkDetail-info" v-if="work.need_interview">
                            <el-row class="list-item">
                                <el-col :span="12"><i class="fa fa-users fa-fw"></i>&nbsp;&nbsp;有无面试：有</el-col>
                            </el-row>
                            <el-row class="list-item">
                                <el-col :span=12><i class="fa fa-calendar fa-fw"></i>&nbsp;&nbsp;面试时间：{{work.interview_time}}</el-col>
                                <el-col :span=12><i class="fa fa-map-o fa-fw"></i>&nbsp;&nbsp;面试地点：{{work.interview_place}}</el-col>
                            </el-row>
                        </div>
                        <div class="WorkDetail-info" v-else>
                            <el-row class="list-item">
                                <el-col :span="12"><i class="fa fa-users fa-fw"></i>&nbsp;&nbsp;有无面试：无</el-col>
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
                                    <a href="" class="User-link">
                                        <img  :src="'/images/users/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
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
                                        <img  :src="'/images/users/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
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
                                        <img  :src="'/images/users/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
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
                                        <img  :src="'/images/users/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
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
                                        <img  :src="'/images/users/' + work.employer.pic_path" width="24px;" height="24px" alt="图片被外星人劫持啦！">
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
                similarWorks: [],
                question:null,
                activeTab:'second'
            }
        },
        computed: mapState ({
            work: state => state.work,
            user: state => state.user
        }),
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
            this.$store.dispatch('workGet',this.$route.params.id).then((data) => {
                console.log('curent work',data);
            });
        }
    }
</script>

<style scoped>
    .Main {
        margin-left: 200px;
        margin-top: 60px;
        background-color: #F5F5F6;
    }
    .WorkDetail {
        margin-left:20px;
        margin-top:20px;
    }
    .WorkDetail-title {
        font-size: 18px;
        font-weight: 600;
        margin-top:20px;
        margin-bottom:10px;
    }
    .WorkDetail-title:first-child {
        margin-top: 10px;
    }
    .blue {
        color: #316d9e;
    }
    .WorkDetail-info {
        margin-top:10px;
        margin-left:20px;
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
    .list-item {
        margin-bottom: 5px;
    }
    .CheckBox-group {
        display: inline-block;
    }
    .Question-card {
        margin-left:20px;
        margin-top:20px;
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
</style>