<template>
    <el-popover width="400" trigger="hover" @show="init">
        <router-link :to="'/user/' + user.id" v-if="text" slot="reference">
            <span  class="NameLink">{{user.name}}</span>
        </router-link>
        <router-link :to="'/user/' + user.id" v-else slot="reference">
            <img :src="'/storage/' + user.pic_path" :width="picWidth" :height="picWidth" alt="图片被外星人劫持啦！">
        </router-link>
        <div class="profile-card">
            <div class="ProfileHeader-userCover">
                <div class="UserCover">
                    <img class="UserCover-image" width="300" :src="'/storage/' + user.cover_path" alt="用户封面">
                </div>
            </div>
            <div class="ProfileHeader-wrapper">
                <div class="ProfileHeader-main">
                    <div class="UpPart">
                        <div class="ProfileHeader-avatar">
                            <router-link :to="'/user/' + user.id">
                                <img :src="'/storage/' + user.pic_path" alt="用户头像" width="60px" height="60px">
                            </router-link>
                        </div>
                        <div class="ProfileHeader-content">
                            <div class="ProfileHeader-contentHead">
                                <div class="ProfileHeader-title">
                                    <router-link :to="'/user/' + user.id">
                                        <span class="ProfileHeader-name">{{user.name}}</span>
                                    </router-link>
                                    <span class="fa-stack fa-lg CertificateIcon">
                                        <i class="fa fa-certificate fa-stack-2x" style="color:rgb(247, 186, 42) "></i>
                                        <i class="fa fa-check fa-stack-1x" style="color: white"></i>
                                    </span>
                                    <span class="NumberBoard-name">(已实名认证)</span>
                                </div>
                                <el-popover  placement="right"  trigger="hover">
                                    <div class="TotalStars" slot="reference">
                                        <span class="TotalStar-title">综合评分&nbsp;:</span>
                                        <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                    <div class="DetailStars">
                                        <span class="Star-title">工作态度&nbsp;:</span>
                                        <el-rate v-model="treat_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                    <div class="DetailStars">
                                        <span class="Star-title">描述相符&nbsp;:</span>
                                        <el-rate v-model="description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                    <div class="DetailStars">
                                        <span class="Star-title">工作能力&nbsp;:</span>
                                        <el-rate v-model="pay_speed" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                    </div>
                                </el-popover>
                            </div>
                        </div>
                    </div>
                    <div class="CenterPart">
                        <div class="NumberBoard">
                            <div class="NumberBoard-item">
                                <router-link :to="'/user/' + user.id + '/works'">
                                    <div class="NumberBoard-name">兼职数</div>
                                    <div class="NumberBoard-value">{{user.finished_works_count}}</div>
                                </router-link>
                            </div>
                            <div class="NumberBoard-divider"></div>
                            <div class="NumberBoard-item">
                                <div class="NumberBoard-name">经验值</div>
                                <div class="NumberBoard-value">999</div>
                            </div>
                            <div class="NumberBoard-divider"></div>
                            <div class="NumberBoard-item">
                                <router-link :to="'/user/' + user.id + '/following'">
                                    <div class="NumberBoard-name">粉丝量</div>
                                    <div class="NumberBoard-value">{{user.user_followers_count}}</div>
                                </router-link>
                            </div>
                        </div>
                    </div>
                    <div class="BottomPart">
                        <div class="ButtonGroup">
                            <div class="FollowAction" v-if="followStatus == false">
                                <el-button type="primary" @click="followUser" class="ActionButton" v-if="user.gender == '男'"><i class="fa fa-plus fa-fw WhiteIcon"></i>&nbsp;&nbsp;关注他</el-button>
                                <el-button type="primary" @click="followUser" class="ActionButton" v-else><i class="fa fa-plus fa-fw WhiteIcon"></i>&nbsp;&nbsp;关注她</el-button>
                            </div>
                            <div class="FollowAction" v-else>
                                <el-button type="danger" @click="unFollowUser" class="ActionButton"><i class="fa fa-user-times fa-fw WhiteIcon"></i>&nbsp;&nbsp;取消关注</el-button>
                            </div>
                            <el-button class="ActionButton"><i class="fa fa-comments-o fa-fw"></i>&nbsp;&nbsp;发私信</el-button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
    </el-popover>
</template>

<script>
    import axios from 'axios';
    import LoginDialog from '../Dialog/LoginDialog.vue';
    export default {
        name:'UserPopover',
        props: {
            user: {
                required: true
            },
            picWidth: {
                default: 60
            },
            text: {
                default: false
            }
        },
        components:{LoginDialog},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                works:[],
                loading:true,
                loginShow:false,
                treat_star: 4.2,
                pay_speed: 4,
                description_match: 4.6,
                total_star: 4.4,
                followStatus:false
            }
        },
        methods: {
            getUserWorks: function () {
                let that = this;
                axios.get('/api/works?user_id=' + that.user.id).then(function (response) {
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
            },
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            checkFollowstatus:function () {
                if (localStorage.user) {
                    let that = this;
                    axios.get('/api/user/followingcheck?to_id=' + that.user.id + '&status=11').then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.followStatus = response.data.follow;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            followUser:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.post('/api/user/followings',{
                        to_id: that.user.id,
                        status: 11
                    }).then(function (response) {
                        return new Promise(function (resovle, reject) {
                            if (response.data.status == 1) {
                                resovle(response.data);
                                that.$message.success(response.data.msg);
                                that.followStatus = true;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                                that.followStatus = false;
                            }
                        })
                    })
                }
            },
            unFollowUser:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    axios.delete('/api/user/followings/' + that.user.id +'?status=11').then(function (response) {
                        return new Promise(function (resovle, reject) {
                            if (response.data.status == 1) {
                                resovle(response.data);
                                that.followStatus = false;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            init:function () {
                this.checkFollowstatus();
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
    .ProfileHeader-userCover {
        margin:-9px -11px 0 -11px;
    }
    .UserCover {
        position: relative;
        height: 100px;
        overflow: hidden;
        background: #f7f8fa;
        border-top-right-radius: 1px;
        border-top-left-radius: 1px;
        transition: height .3s;
    }
    .UserCover-image, .UserCover-image img {
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .ProfileHeader-wrapper {
        position: relative;
        width: 100%;
        background: #fff;
        box-sizing: border-box;
    }
    .ProfileHeader-main {
        display: flex;
        flex-direction:column;
        justify-content:center;
        position: relative;
        margin: 0 20px 24px;
    }
    .UpPart {
        flex: 1;
        border-bottom: 1px solid #f0f2f7;
    }
    .CenterPart {
        flex:1;
        padding-bottom:20px;
    }
    .NumberBoard {
        margin-top:20px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        text-align: center;
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
    .BottomPart {
        flex:1;
    }
    .ButtonGroup {
        display: flex;
        justify-content: center;
    }
    .ActionButton {
        width: 150px;
    }
    .ActionButton:first-child {
        margin-right:20px;
    }
    .WhiteIcon {
        color: white;
    }
    .ProfileHeader-avatar {
        vertical-align: top;
        display: inline-block;
        overflow: hidden;
        border: 4px solid #fff;
        border-radius: 8px;
        position: absolute;
        top: -15px;
        left: 0;
        z-index: 4;
    }
    .ProfileHeader-content {
        padding-top: 5px;
        padding-left: 80px;
    }
    .ProfileHeader-contentHead {
        position: relative;
        margin-bottom: 16px;
    }
    .ProfileHeader-title {

    }
    .ProfileHeader-name {
        font-size: 16px;
        font-weight: 700;
    }
    .CertificateIcon {
        margin-left:10px;
        font-size: 10px
    }
    .TotalStars {
        margin-top:5px;
    }
    .DetailStars {
        display: flex;
        margin-left:20px;
        margin-right:20px;
        margin-bottom:10px;
    }
    .TotalStar-title {
        width: 100px;
        float:left;
        color: #999;
        font-size:15px;
    }
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
        font-size:15px;
    }
</style>