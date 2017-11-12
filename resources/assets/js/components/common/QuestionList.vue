<template>
    <div class="QuestionList-main" v-if="user">
        <el-col :span="3" class="Question-avatar">
            <UserPopover :user="user" :pic-width="50" role="role"></UserPopover>
        </el-col>
        <el-col :span="21" class="Question-right">
            <div class="QuestionHead">
                <div class="QuestionHead-name">
                    <UserPopover :user="user" :text="true" role="role"></UserPopover>
                </div>
                <div class="WorkTitle" v-if="showTitle">
                    <router-link :to="'/work/' + work.id">
                        来自兼职<span class="WorkTitle-text">{{work.title}}</span>
                    </router-link>
                </div>
                <span class="date" v-if="showTime">{{fromNow(question.created_at)}}</span>
            </div>
            <div class="QuestionContent">
                <div class="QuestionDetail"><span style="color: #999;margin-right:5px;">[咨询内容]</span>{{question.content}}
                </div>
                <div class="QuestionAction">
                    <div class="Replyed"  v-if="answer">
                        <span class="Replied">雇主回复</span>
                        <a href="javascript:;" class="Up-thumbs" v-if="me !== null && me.id == work.employer_id">
                            <el-button type="text" :disabled="true"><i class="fa fa-bell"></i>&nbsp;想知道<span class="Up-number">(&nbsp;{{question.want_answer_users_count ? question.want_answer_users_count : 0}}&nbsp;)</span></el-button>
                        </a>
                        <a href="javascript:;" class="Up-thumbs" v-else>
                            <el-button type="text" @click="wantAnswer" v-if="!wanted"><i class="fa fa-bell"></i>&nbsp;想知道<span class="Up-number">(&nbsp;{{question.want_answer_users_count ? question.want_answer_users_count : 0}}&nbsp;)</span></el-button>
                            <el-button type="text" @click="unWantAnswer" v-else><i class="fa fa-bell-o"></i>&nbsp;取消<span class="Up-number">(&nbsp;{{question.want_answer_users_count ? question.want_answer_users_count : 0}}&nbsp;)</span></el-button>
                        </a>
                    </div>
                    <div class="Owener-action" v-else-if="me !== null && me.id == work.employer_id">
                        <el-button type="text" @click="replyDialogVisable = true">回复</el-button>
                        <el-dialog title="回复咨询" :visible.sync="replyDialogVisable" size="tiny">
                            <el-input type="textarea" v-model="reply" placeholder="请输入回复内容"></el-input>
                            <span slot="footer" class="dialog-footer">
                                <el-button @click="cancelReply">取 消</el-button>
                                <el-button type="primary" @click="storeReply">确 定</el-button>
                            </span>
                        </el-dialog>
                        <a href="javascript:;" class="Up-thumbs">
                            <el-button type="text" :disabled="true"><i class="fa fa-bell"></i>&nbsp;想知道<span class="Up-number">(&nbsp;{{question.want_answer_users_count ? question.want_answer_users_count : 0}}&nbsp;)</span></el-button>
                        </a>
                    </div>
                    <div class="User-action" v-else>
                        <el-tooltip  effect="dark" content="点击后当雇主回复将会通知你" placement="top-center">
                            <a href="javascript:;" class="Up-thumbs">
                                <el-button type="text" @click="wantAnswer" v-if="!wanted"><i class="fa fa-bell"></i>&nbsp;想知道<span class="Up-number">(&nbsp;{{question.want_answer_users_count ? question.want_answer_users_count : 0}}&nbsp;)</span></el-button>
                                <el-button type="text" @click="unWantAnswer" v-else><i class="fa fa-bell-o"></i>&nbsp;取消<span class="Up-number">(&nbsp;{{question.want_answer_users_count ? question.want_answer_users_count : 0}}&nbsp;)</span></el-button>
                            </a>
                        </el-tooltip>
                    </div>
                </div>
                <div class="QuestionReply" v-if="answer !== null">
                    <div class="TriangleOut"></div>
                    <div class="TriangleIn"></div>
                    <div class="ReplyContent">
                        <el-col :span="4" class="Question-avatar">
                            <EmployerPopover :employer="employer" pic-width="50" v-if="employer !== null"></EmployerPopover>
                        </el-col>
                        <el-col :span="20" class="Question-right">
                            <div class="ReplyHead">
                                <EmployerPopover :employer="employer" :text="true" v-if="employer !== null"></EmployerPopover>
                                <span class="date">{{fromNow(answer.created_at)}}</span>
                            </div>
                            <div class="ReplyDetail"><span style="color: #999;margin-right:5px;">[回复内容]</span>{{answer.content}}</div>
                        </el-col>
                    </div>
                </div>
            </div>
        </el-col>
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
    </div>
</template>

<script>
    import {dateFromNow} from '../../util/format';
    import axios from 'axios';
    import UserPopover from './Popover/UserPopover.vue';
    import EmployerPopover from './Popover/EmployerPopover.vue';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    export default {
        name: 'QuestionList',
        props:{
            question:{
                type:Object,
                required:true
            },
            work: {
                type:Object
            },
            role: {
                default:'user'
            },
            showTitle: {
                default:false
            },
            showTime: {
                default:true
            }
        },
        components: {UserPopover,EmployerPopover,LoginDialog},
        data() {
            return {
                me:localStorage.user ? JSON.parse(localStorage.user) : null,
                user: null,
                employer:null,
                answer:null,
                replyDialogVisable: false,
                reply:null,
                loginShow:false,
                wanted:false
            }
        },
        methods: {
            fromNow: function (date) {
                return dateFromNow(date);
            },
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            getQuestionOwner() {
                let that = this;
                axios.get('/api/users/' + that.question.user_id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.user = response.data.user;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getEmployer() {
                let that = this;
                axios.get('/api/employers/' + that.work.employer_id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.employer = response.data.employer;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getAnswers() {
                let that = this;
                axios.get('/api/answers?question_id=' + that.question.id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.answer = response.data.answer;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            cancelReply() {
                this.replyDialogVisable = false;
                this.reply = null
            },
            storeReply() {
                let that = this;
                axios.post('/api/answers',{
                    question_id: that.question.id,
                    text: that.reply
                }).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.answer = response.data.answer;
                            this.replyDialogVisable = false;
                            this.reply = null
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            wantAnswer:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.post('/api/user/wantanswer',{
                        question_id: that.question.id
                    }).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.wanted = true;
                                that.question.want_answer_users_count = that.question.want_answer_users_count + 1;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            unWantAnswer:function () {
                let that = this;
                axios.delete('/api/user/wantanswer/' + that.question.id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.question.want_answer_users_count = that.question.want_answer_users_count - 1;
                            that.$message.success(response.data.msg);
                            that.wanted = false;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            checkWanted:function () {
                if (localStorage.user) {
                    let that = this;
                    axios.get('/api/user/wantedcheck?question_id=' + that.question.id).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.wanted = response.data.wanted;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            init:function () {
                this.getQuestionOwner();
                if (this.question.answers_count > 0 && this.answer == null) {
                    this.getAnswers();
                }

                this.getEmployer();
                this.checkWanted();
            }
        },
        watch:{
            question:function (newVal) {
                this.init();
            }
        },
        created: function () {
            this.getQuestionOwner();
            this.getAnswers();
            this.getEmployer();
            this.checkWanted();
        }
    }
</script>

<style scoped>
    .QuestionList-main {
        margin-top: 10px;
    }
    .Question-avatar {
        width: 60px;
    }
    .QuestionHead {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
    }
    .WorkTitle {
        margin-left:20px;
        color: #8590a6;
    }
    .WorkTitle-text {
        margin-left: 10px;
        color: black;
        font-weight:500;
    }
    .Question-right {
        float: left;
    }
    .date {
        position: absolute;
        right: 30px;
        color: #999;;
        align-self: center;
    }
    .QuestionContent {
        margin-bottom:10px;
    }
    .QuestionDetail {
        margin-top:10px;
        display:inline;
    }
    .Replied {
        padding-right: 10px;
        border-right: 1px solid #dce4e6;
        color: #999;
    }
    .Up-thumbs {
        margin-left:10px;
    }
    .User-action .Up-thumbs {
        margin-left:0px;
    }
    .Employer-name {
        margin-bottom: 6px;
        color: #999;
    }
    .QuestionReply {
        position: relative;
    }
    .TriangleOut {
        position: absolute;
        top: -10px;
        left: 16px;
        width: 0;
        height: 0;
        border-right: 10px solid transparent;
        border-bottom: 10px solid #dce4e6;
    }
    .TriangleIn {
        position: absolute;
        top: -8px;
        left: 17px;
        width: 0;
        height: 0;
        border-right: 9px solid transparent;
        border-bottom: 9px solid #fafafa;
    }
    .ReplyContent {
        padding: 10px 10px 10px;
        margin-top: 10px;
        border: 1px solid #dce4e6;
        border-radius: 3px;
        background-color: #fafafa;
        overflow: hidden;
    }
    .ReplyHead {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
        width:500px;
    }
</style>