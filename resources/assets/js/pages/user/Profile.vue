<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <div class="ProfileHeader" id="ProfileHeader">
                <el-card class="HeaderCard">
                    <div class="ProfileHeader-userCover">
                        <div class="UserCover">
                            <img class="UserCover-image" width="1210" src="https://pic3.zhimg.com/80/v2-c2b1832b468de8f73ee504ee35d4139e_r.jpg" alt="用户封面">
                        </div>
                    </div>
                    <div class="ProfileHeader-wrapper">
                        <div class="ProfileHeader-main">
                            <div class="ProfileHeader-avatar">
                                <img :src="'/images/users/avatar/' + user.pic_path" alt="用户头像" width="160px" height="160px">
                            </div>
                            <div class="ProfileHeader-content">
                                <div class="ProfileHeader-contentHead">
                                    <h1 class="ProfileHeader-title">
                                        <span class="ProfileHeader-name">{{user.name}}</span>
                                        <span class="ProfileHeader-intro">{{user.simple_intro}}</span>
                                    </h1>
                                </div>
                                <div class="ProfileHeader-contentBody">
                                    <div class="ProfileHeader-info" v-if="!detailShow">
                                        <div class="ProfileHeader-infoItem">
                                            <div class="ProfileHeader-iconWrapper">
                                                <i class="fa fa-map-marker"></i>
                                            </div>
                                            {{user.city}}
                                            <div class="ProfileHeader-divider"></div>
                                            <div class="ProfileHeader-iconWrapper" v-if="user.gender == '男' ">
                                                <i class="fa fa-mars"></i>
                                            </div>
                                            <div class="ProfileHeader-iconWrapper" v-else>
                                                <i class="fa fa-venus"></i>
                                            </div>
                                        </div>
                                        <div class="ProfileHeader-infoItem">
                                            <div class="ProfileHeader-iconWrapper">
                                                <i class="fa fa-graduation-cap"></i>
                                            </div>
                                            {{user.school}}
                                            <div class="ProfileHeader-divider"></div>
                                            {{user.major}}
                                        </div>
                                    </div>
                                    <div class="ProfileHeader-detail" v-else>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">居住地</span>
                                            <div class="ProfileHeader-detailValue"><span>现居{{user.city}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">年龄</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.age}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">邮箱</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.email}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">教育经历</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.school}}--{{user.major}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">个人介绍</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.introduction}}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ProfileHeader-contentFooter">
                                    <el-button class="ExpandButton" @click="detailShow = !detailShow" v-if="!detailShow">
                                        <div class="ProfileHeader-iconWrapper"><i class="fa fa-arrow-down"></i></div>查看详细资料
                                    </el-button>
                                    <el-button class="ExpandButton" @click="detailShow = !detailShow" v-else>
                                        <div class="ProfileHeader-iconWrapper"><i class="fa fa-arrow-up"></i></div>收起详细资料
                                    </el-button>
                                    <router-link to="/user/profile/edit" v-if="myself.id == user.id">
                                        <el-button class="EditButton ProfileButtonGroup" type="primary">
                                            修改个人资料
                                        </el-button>
                                    </router-link>
                                    <div class="ProfileButtonGroup" v-else>
                                        <el-button class="FollowButton" type="primary"><i class="fa fa-plus fa-fw FollowIcon"></i>&nbsp;&nbsp;关注他</el-button>
                                        <el-button class="ChatButton"><i class="fa fa-comments fa-fw"></i>&nbsp;&nbsp;发私信</el-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-card>
            </div>
            <div class="Profile-main">
                <el-col :span="16" class="left-panel">
                    <el-card class="ProfileCard">
                        <div class="MainHeader">
                            <el-tabs v-model="activeName"  class="ProfileTabs">
                                <el-tab-pane name="动态">
                                    <span class="TapTitle" slot="label"><i class="fa fa-rss-square fa-fw"></i>&nbsp;动态</span>

                                </el-tab-pane>
                                <el-tab-pane name="兼职">
                                    <span class="TapTitle" slot="label"><i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职</span>
                                    兼职
                                </el-tab-pane>
                                <el-tab-pane name="评价">
                                    <span class="TapTitle" slot="label"><i class="fa fa-star-half-o fa-fw"></i>&nbsp;评价</span>
                                    评价
                                </el-tab-pane>
                                <el-tab-pane name="团队">
                                    <span class="TapTitle" slot="label"><i class="fa fa-group fa-fw"></i>&nbsp;团队</span>
                                    团队
                                </el-tab-pane>
                                <el-tab-pane name="展示">
                                    <span class="TapTitle" slot="label"><i class="fa fa-tasks fa-fw"></i>&nbsp;展示</span>
                                    作品展示、个人能力形象
                                </el-tab-pane>
                                <el-tab-pane name="关注">
                                    <span class="TapTitle" slot="label"><i class="fa fa-address-card fa-fw"></i>&nbsp;关注</span>
                                    关注他的人，他关注的人，他关注的兼职
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="8" class="right-panel">
                    <el-card>
                        <div slot="header">
                            <span>个人成就</span>
                        </div>
                    </el-card>
                </el-col>
            </div>
        </div>
        <CornerButtons></CornerButtons>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import axios from 'axios';
    export default {
        name:'Profile',
        components:{Navbar,CornerButtons},
        data() {
            return {
                myself: JSON.parse(localStorage.user),
                detailShow: false,
                activeName: '动态',
                user: {
                    pic_path:'default02.png'
                }
            }
        },
        created: function () {
            let me = this;
            axios.get('/api/users/'+ this.$route.params.id).then(function (response) {
                console.log(response.data);
                me.user = response.data.user;
            });
        }
    }
</script>

<style scoped>
    .Main {
        margin:71px auto;
        width: 1210px;
        height:100%;
    }
    .left-panel {

    }
    .right-panel {
        float: right;
        padding-left:10px;
    }
    .HeaderCard {
        margin-top:10px;
    }
    .ProfileHeader {
        margin-top:10px;
        position: relative;
        width: 100%;
    }
    .ProfileHeader-userCover {
        margin:-20px -20px 0 -20px;
    }
    .UserCover {
        position: relative;
        height: 240px;
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
        position: relative;
        margin: 0 20px 24px;
    }
    .ProfileHeader-avatar {
        vertical-align: top;
        display: inline-block;
        overflow: hidden;
        border: 4px solid #fff;
        border-radius: 8px;
        position: absolute;
        top: -25px;
        left: 0;
        z-index: 4;
    }
    .ProfileHeader-content {
        padding-top: 16px;
        padding-left: 32px;
        border-left: 164px solid transparent;
    }
    .ProfileHeader-contentHead {
        position: relative;
        padding-right: 106px;
        margin-bottom: 16px;
    }
    .ProfileHeader-title {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ProfileHeader-name {
        font-size: 26px;
        font-weight: 700;
        line-height: 30px;
    }
    .ProfileHeader-intro {
        margin-left: 12px;
        font-weight:lighter;
        font-size: 18px;
        white-space: nowrap;
    }
    .ProfileHeader-contentBody {
        position: relative;
        width: 524px;
        overflow: hidden;
        transition: height .3s;
    }
    .ProfileHeader-info {
        font-size: 14px;
        line-height: 20px;
        box-sizing: border-box;
    }
    .ProfileHeader-infoItem {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ProfileHeader-infoItem:not(:last-child) {
        margin-bottom: 10px;
    }
    .ProfileHeader-iconWrapper {
        display: inline-block;
        width: 18px;
        height: 18px;
        margin-right: 12px;
        text-align: center;
    }
    .ProfileHeader-divider {
        display: inline-block;
        width: 1px;
        height: 10px;
        margin: 0 8px;
        background: #e7eaf1;
    }
    .ProfileHeader-contentFooter {
        position: relative;
        padding-top: 8px;
    }
    .ExpandButton {
        height: auto;
        padding: 0;
        line-height: inherit;
        background-color: transparent;
        border: none;
        border-radius: 0;
        color: #8590a6;
    }
    .ProfileButtonGroup {
        position: absolute;
        right: 0;
        bottom: 0;
    }
    .FollowIcon {
        color: #ffffff;
    }
    .ProfileHeader-detail {
        width: 100%;
        font-size: 14px;
        line-height: 1.8;
        color: #262626;
    }
    .ProfileHeader-detailItem {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 18px;
    }
    .ProfileHeader-detailLabel {
        width: 60px;
        margin-right: 37px;
        font-weight: 700;
    }
    .ProfileHeader-detailValue {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
    }
    .Profile-main {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 1210px;
        min-height: 100vh;
        margin: 10px auto;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
    }
    .ProfileTabs {
        margin-top: -10px;
    }
    .TapTitle {
        font-size: 16px;
    }
    .is-active i {
        color: #20a0ff;
    }
    .is-active .TapTitle {
        font-weight: 700;
    }
</style>