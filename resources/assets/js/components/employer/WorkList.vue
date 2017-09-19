<template>
    <el-card>
        <div slot="header">来自 <router-link :to="'/work/' + work.id"><span class="WorkTitle">{{work.title}}</span></router-link>
            <span class="ApplyPercent" v-if="action == 'apply'">已录用/需要人数：<span class="HiredNum">{{work.hired_num}}</span> / <span class="NeedNum">{{work.need_num}}</span></span>
            <span class="ApplyPercent" v-else-if="action == 'answer'"><span class="NeedNum">{{questions.length}}</span> 条咨询</span>
        </div>
        <div class="Content" v-if="action == 'apply'">
            <div class="ApplicantLists" v-if="applicants.length > 0">
                <UserList v-for="applicant in applicants" v-on:addHiredNum="work.hired_num = work.hired_num + 1" :user="applicant" :work="work" role="employer" action="decide" class="ApplicantList"></UserList>
            </div>
            <div class="EmptyState" v-else>
                <div class="EmptyState-inner">
                    <i class="fa fa-users EmptyState-icon"></i>
                    <span>该兼职暂时还没有人申请</span>
                </div>
            </div>
        </div>
        <div class="Content" v-else-if="action == 'review'">
            <div class="ApplicantLists" v-if="applicants.length > 0">
                <UserList v-for="applicant in applicants" :user="applicant" :work="work" action="review" role="employer" class="ApplicantList"></UserList>
            </div>
            <div class="EmptyState" v-else>
                <div class="EmptyState-inner">
                    <i class="fa fa-users EmptyState-icon"></i>
                    <span>该兼职暂时还没有人等待评价</span>
                </div>
            </div>
        </div>
        <div class="Content" v-else>
            <el-tabs v-model="activeTab" @tab-click="handleTabChange">
                <el-tab-pane label="待回复" name="待回复" ref="unAnswered">
                    <div class="QuestionLists" v-if="unAnsweredQuestions.length > 0 ">
                        <QuestionList v-for="question in unAnsweredQuestions" role="employer" :question="question" :work="work" class="QuestionItem"></QuestionList>
                    </div>
                    <div class="EmptyState" v-else>
                        <div class="EmptyState-inner">
                            <i class="fa fa-comments EmptyState-icon"></i>
                            <span>暂时还没有待回复的咨询</span>
                        </div>
                    </div>
                </el-tab-pane>
                <el-tab-pane label="已回复" name="已回复" ref="answered">
                    <div class="QuestionLists" v-if="answeredQuestions.length > 0 ">
                        <QuestionList v-for="question in answeredQuestions" role="employer" :question="question" :work="work" class="QuestionItem"></QuestionList>
                    </div>
                    <div class="EmptyState" v-else>
                        <div class="EmptyState-inner">
                            <i class="fa fa-comments EmptyState-icon"></i>
                            <span>暂时还没有已回复的咨询</span>
                        </div>
                    </div>
                </el-tab-pane>
            </el-tabs>
        </div>
    </el-card>

</template>

<script>
    import UserList from '../common/Follow/UserFollowList.vue';
    import QuestionList from '../common/QuestionList.vue';
    export default {
        name:'WorkList',
        components:{UserList,QuestionList},
        props:{
            work: {
                type:Object,
                required:true
            },
            action: {
                type:String,
                default:'apply'
            }
        },
        data() {
            return {
                applicants:[],
                questions:[],
                activeTab:'待回复',
                unAnsweredQuestions:[],
                answeredQuestions:[]
            }
        },
        methods: {
            getApplicants:function () {
                let that = this;
                this.$axios.get('/api/work/' + that.work.id + '/applicant').then(response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.applicants = response.data.applicants;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getQuestions:function () {
                let that = this;
                this.$axios.get('/api/questions?work_id=' + that.work.id ).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.questions = response.data.questions;
                            that.unAnsweredQuestions = that.$_.filter(response.data.questions,{answers_count:0});
                            that.answeredQuestions = that.$_.filter(response.data.questions,{answers_count:1});
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            handleTabChange:function (tab, event) {
                if (tab == this.$refs.unAnswered) {
                    this.unAnsweredQuestions = this.$_.filter(this.questions,{answers_count:0});
                } else {
                    this.answeredQuestions = this.$_.filter(this.questions,{answers_count:1});
                }
            }
        },
        created:function () {
            if (this.action == 'answer') {
                this.getQuestions();
            } else {
                this.getApplicants();
            }
        }
    }
</script>

<style scoped>
    .WorkTitle {
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ApplyPercent {
        float: right;
        margin-right:20px;
        color: #8590a6;;
    }
    .HiredNum {
        color: #ff4949;
        font-weight: 500;
    }
    .NeedNum {
        color: #20a0ff;
        font-weight: 500;
    }
    .ApplicantList {
        border-bottom: 1px solid #f0f2f7;
    }
    .ApplicantList:last-child {
        border-bottom: none;
    }
    .EmptyState {
        padding: 0px 0;
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
</style>