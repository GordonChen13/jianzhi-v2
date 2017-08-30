<template>
    <el-popover width="400" trigger="hover">
            <router-link :to="'/employer/' + employer.id" v-if="text" slot="reference">
                <span  class="NameLink">{{employer.name}}</span>
            </router-link>
            <router-link :to="'/employer/' + employer.id" v-else slot="reference">
                <img :src="'/storage/' + employer.pic_path" :width="picWidth" :height="picWidth" alt="图片被外星人劫持啦！">
            </router-link>
        <div class="profile-card">
            <div class="upper">
                <el-row>
                    <el-col :span="6">
                        <router-link :to="'/employer/' + employer.id">
                            <img  :src="'/storage/' + employer.pic_path" width="70px;" height="70px" alt="图片被外星人劫持啦！">
                        </router-link>
                    </el-col>
                    <el-col :span="18">
                        <router-link :to="'/employer/' + employer.id">
                            <span class="name">{{employer.name}}</span>
                        </router-link>
                        <p>{{employer.introduction}}</p>
                    </el-col>
                </el-row>
            </div>
            <div class="lower">
                <el-row>
                    <div class="meta">
                        <el-col :span="4">
                            <a href="" target="_blank" class="item">
                                <span class="value">8</span>
                                <span class="key">兼职</span>
                            </a>
                        </el-col>
                        <el-col :span="4">
                            <a href="" target="_blank" class="item">
                                <span class="value">4.8</span>
                                <span class="key">评分</span>
                            </a>
                        </el-col>
                        <el-col :span="4">
                            <a href="" target="_blank" class="item">
                                <span class="value">88</span>
                                <span class="key">粉丝</span>
                            </a>
                        </el-col>
                    </div>
                    <div class="operation">
                        <el-col :span="4">
                            <el-button>关注</el-button>
                        </el-col>
                        <el-col :span="4">
                            <el-button @click=" "><i class="el-icon-message"></i></el-button>
                        </el-col>
                    </div>
                </el-row>
            </div>
        </div>
    </el-popover>
</template>

<script>
    import axios from 'axios';
    export default {
        name:'EmployerPopover',
        props: {
            employer: {
                required: true,
                default: {
                    id:1,
                    pic_path:'',
                    name:'',
                    introduction:''
                }
            },
            picWidth: {
                default: 60
            },
            text: {
                default: false
            }
        },
        data() {
            return {
                works:[],
                loading:true
            }
        },
        methods: {
            getEmployerWorks: function () {
                let that = this;
                axios.get('/api/works?employer_id=' + that.employer.id).then(function (response) {
                    return new Promise(function (resolve, reject) {
                        if (response.data.status ==1) {
                            that.works = response.data.works;
                            resolve(response.data);
                        } else {
                            that.$message.error(response.data.msg);
                            reject(response.data);
                        }
                    })
                })
            }
        },
        created: function () {
//            this.getEmployerWorks(this.employer.id);
//            console.log(this.employer);
        }
    }
</script>

<style scoped>
    .profile-card .upper span.name {
        font-size: 14px;
        font-weight: 700;
    }
    .profile-card .lower {
        background: #fafafa;
        border: 14px solid transparent;
        margin: 0px -10px -10px -10px;
    }
    .profile-card .lower .meta .item {
        float: left;
        padding: 0 16px;
        border-right: 1px solid #eee;
        color: inherit;
        text-decoration: none;
    }
    .profile-card .lower .meta .item .value {
        font-size: 15px;
        font-weight: 700;
        display: block;
        text-align: center;
    }
    .profile-card .lower .meta .item .key {
        font-size: 14px;
        color: #999;
        display: block;
        text-align: center;
    }
    .profile-card .lower .operation .el-col {
        float: right;
    }
    .NameLink {
        font-weight:600;
        cursor: auto;
        font-size: 15px;
        line-height: normal;
    }
</style>