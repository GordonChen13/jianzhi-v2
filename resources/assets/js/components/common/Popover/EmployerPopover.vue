<template>
    <el-popover width="400" trigger="hover">
            <router-link :to="/employer/ + employer.id" v-if="text" slot="reference">
                <span  class="NameLink">{{employer.name}}</span>
            </router-link>
            <img v-else  :src="'/images/users/avatar/' + employer.pic_path" :width="picWidth" alt="图片被外星人劫持啦！" slot="reference">
        <div class="profile-card">
            <div class="upper">
                <el-row>
                    <el-col :span="6">
                        <img  :src="'/images/users/avatar/' + employer.pic_path" width="70px;" alt="图片被外星人劫持啦！">
                    </el-col>
                    <el-col :span="18">
                        <a href=""><span class="name">{{employer.name}}</span></a>
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
    export default {
        name:'EmployerPopover',
        props: {
            employer: {
                required: true
            },
            picWidth: {
                default: 60
            },
            text: {
                default: false
            }
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