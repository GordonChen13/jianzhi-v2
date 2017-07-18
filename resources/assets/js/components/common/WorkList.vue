<template>
    <el-card class="box-card">
        <el-col :span="4" v-popover:userPopover>
            <img  :src="'/images/users/' + work.user.pic_path" width="80px;" alt="图片被外星人劫持啦！">
            <el-popover ref="userPopover" width="400" trigger="hover">
                <div class="profile-card">
                    <div class="upper">
                        <el-row>
                            <el-col :span="6">
                                <img  :src="'/images/users/' + work.user.pic_path" width="70px;" alt="图片被外星人劫持啦！">
                            </el-col>
                            <el-col :span="18">
                                <a href=""><span class="name">{{work.user.name}}</span></a>
                                <p>{{work.user.introduction}}</p>
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
        </el-col>
        <el-col :span="14">
            <el-row><a :href="'/#/work/' + work.id">{{work.title}}</a></el-row>
            <el-row class="list-item">
                <el-col :span="14"><i class="fa fa-calendar-o fa-fw"></i>&nbsp;&nbsp;工作日期：{{work.start_date}}至{{work.end_date}}</el-col>
                <el-col :span="10"><i class="fa fa-mars fa-fw"></i>&nbsp;&nbsp;性别要求：{{work.gender}}</el-col>
            </el-row>
            <el-row class="list-item">
                <el-col :span="14"><i class="fa fa-map-marker fa-fw"></i>&nbsp;&nbsp;工作地点：{{work.city}}--{{work.district}}</el-col>
                <el-col :span="10"><i class="fa fa-male fa-fw"></i>&nbsp;&nbsp;需要人数：{{work.hired_num}}/{{work.need_num}}</el-col>
            </el-row>
        </el-col>
        <el-col :span="3">
            <div class="center">
                <span class="money">{{work.pay_amount}}</span>&nbsp;{{work.settlement_type}}
            </div>
        </el-col>
        <el-col :span="3">
            <div class="center-button">
                <el-button type="primary">申请兼职</el-button>
            </div>
        </el-col>
    </el-card>
</template>

<script>
    import {mapState} from 'vuex';
    export default {
        name: 'WorkList',
        props:['work'],
        components:{},
        computed: mapState ({
            params: state => state.params,
            works: state => state.works
        }),
    }
</script>

<style scoped>
    a {
        text-decoration: none;
        font-weight: 700;
        color: #259;
    }
    a:hover {
        text-decoration: underline;
    }
    .box-card {
        width:100%;
        margin:0px auto 20px;
        padding-bottom: 10px;
    }
    .list-item {
        font-size: 12px;
        margin-top: 5px;
    }
    .money {
        font-weight:400 ;
        font-size: 18px;
        color: red;
    }
    .center {
        margin-top:25px;
    }
    .center-button {
        margin-top:20px;
    }
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
</style>