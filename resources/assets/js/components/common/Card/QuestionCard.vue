<template>
    <el-card class="Question-card">
        <div class="Question-topbar">
            <div class="QuestionTopbar-title">
                <h2 class="Topbar-title">{{questions.length}}条咨询</h2>
            </div>
            <div class="Topbar-options">
                <el-button class="button-plain is-active" v-if="activeButton == 'count'">默认排序</el-button>
                <el-button class="button-plain" @click="getQuestions('count')" v-else>默认排序</el-button>
                <el-button class="button-plain is-active" v-if="activeButton == 'newest'">最新排序</el-button>
                <el-button class="button-plain" @click="getQuestions('newest')" v-else>最新排序</el-button>
            </div>
        </div>
        <div class="QuestionLists" v-if="questions.length > 0 ">
            <QuestionList v-for="question in questions" :question="question" :work="work" class="QuestionItem"></QuestionList>
        </div>
        <div class="EmptyState" v-else>
            <div class="EmptyState-inner">
                <i class="fa fa-comments EmptyState-icon"></i>
                <span>暂时还没有咨询</span>
            </div>
        </div>
        <div class="Question-footer">
            <el-input placeholder="请输入内容" v-model="newQuestion">
                <el-button type="primary" slot="append" v-if="me !== null && me.id == work.employer_id" :disabled="true"><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;提问</el-button>
                <el-button type="primary" slot="append" @click="storeQuestion" v-else><i class="fa fa-paper-plane-o"></i>&nbsp;&nbsp;提问</el-button>
            </el-input>
        </div>
    </el-card>
</template>

<script>
    import axios from 'axios';
    import QuestionList from '../QuestionList.vue';
    export default {
        name:'QuestionCard',
        props:['work'],
        components:{QuestionList},
        data() {
            return {
                me:localStorage.user ? JSON.parse(localStorage.user) : null,
                questions:[],
                newQuestion:'',
                activeButton:'count'
            }
        },
        methods: {
            storeQuestion: function () {
                let that = this;
                axios.post('/api/questions',{
                    text: that.newQuestion,
                    work_id: that.work.id
                }).then(function(response) {
                    return new Promise(function(resolve,reject) {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.questions.push(response.data.question);
                            that.newQuestion = '';
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getQuestions: function (order) {
                let that = this;
                this.activeButton = order;
                axios.get('/api/questions?work_id=' + that.work.id + '&order=' + order).then(function (response) {
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
            }
        },
        created: function () {
            this.getQuestions('count');
        }
    }
</script>

<style>
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
        min-height: 100px;
        max-height: 498px;
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
    .button-plain {
        height: auto;
        padding: 0;
        line-height: inherit;
        background-color: transparent;
        border: none;
        border-radius: 0;
        color: #748aa2;
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
    .QuestionLists {
        min-height: 50px;
        max-height:380px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow-x: hidden;
        overflow-y: scroll;
    }
    .QuestionItem {
        border-bottom: 1px solid #f0f2f7;
        position: relative;
        overflow: hidden;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        padding: 12px 16px 10px;
        font-size: 15px;
    }
    .QuestionItem:last-child {
        border-bottom: none;
    }
    .Question-footer {
        padding: 12px 2px;
        width:100%;
        display: inline-block;
        height: 24px;
        background: #fff;
        border-top: 1px solid #ebeef5;
    }
</style>