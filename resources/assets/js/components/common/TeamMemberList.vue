<template>
    <div class="ListItem">
        <div class="ContentItem">
            <div class="ContentItem-image">
                <router-link to="'/user/' + user.id" class="UserLink UserItem-avatar">
                    <UserPopover :user="user" pic-width="70" role="employer" v-if="role == 'employer'"></UserPopover>
                    <UserPopover :user="user" pic-width="70"  v-else></UserPopover>
                </router-link>
            </div>
            <div class="ContentItem-head">
                <div class="ContentItem-title">
                    <UserPopover :user="user" :text="true" role="employer" v-if="role == 'employer'" class="Member-name"></UserPopover>
                    <UserPopover :user="user" :text="true" v-else class="Member-name"></UserPopover>
                    <span class="Member-title" v-if="user.pivot.title">{{title}}</span>
                    <span class="Member-title" v-else>队员</span>
                </div>
                <div class="ContentItem-meta">
                    <div class="ContentItem-stars">
                        <div class="NoReview DetailStars" v-if="user.reviews_count == 0">
                            <span class= "tar-title">综合评分&nbsp;:</span>暂无评分
                        </div>
                        <el-popover  placement="bottom"  trigger="hover" v-else>
                            <div class="DetailStars" slot="reference">
                                <span class="Star-title">综合评分&nbsp;:</span>
                                <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="ReviewCount">
                                <router-link :to="'/user/' + user.id + '/reviews'">
                                    <span class="ReviewCount-text">来自&nbsp;{{user.reviews_count}}&nbsp;份评价</span>
                                </router-link>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">工作态度&nbsp;:</span>
                                <el-rate v-model="attitude_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">办事能力&nbsp;:</span>
                                <el-rate v-model="ability_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">描述相符&nbsp;:</span>
                                <el-rate v-model="description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                        </el-popover>
                    </div>
                    <div class="ContentItem-status">
                        <router-link :to="'/user/' + user.id + '/works'">
                            <div class="ContentItem-statusItem">{{user.finished_works_count}}&nbsp;兼职</div>
                        </router-link>
                        <div class="ContentItem-statusItem">{{user.user_exp}}&nbsp;经验</div>
                        <router-link :to="'/user/' + user.id + '/following'">
                            <div class="ContentItem-statusItem">{{user.user_followers_count}}&nbsp;粉丝</div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="ContentItem-action">
                <div class="ManAction" v-if="user.gender == '男'">
                    <div class="FollowAction"  v-if="action == 'follow'">
                        <el-button class="FollowButton" type="primary" @click="followUser" v-if="!followStatus"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注他</el-button>
                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowUser"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;取消关注</el-button>
                    </div>
                    <div class="InviteAction" v-else-if="action == 'decide'">
                        <div class="Uncheck" v-if="applyStatus == 0">
                            <el-button class="FollowButton" type="primary" @click="applyPass"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;同意申请</el-button>
                            <el-button class="FollowButton" type="danger"  @click="applyDeny"><i class="fa fa-user-times FollowIcon"></i>&nbsp;拒绝申请</el-button>
                        </div>
                        <div class="checked" v-else-if="applyStatus == -1 || applyStatus == -2">
                            <el-button type="danger" :disabled="true"><i class="fa fa-user-times FollowIcon"></i>&nbsp;已拒绝</el-button>
                        </div>
                        <div class="checked" v-else>
                            <el-button type="primary" :disabled="true"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;已通过</el-button>
                        </div>
                    </div>
                </div>
                <div class="WomanAction" v-else>
                    <div class="FollowAction"  v-if="action == 'follow'">
                        <el-button class="FollowButton" type="primary" @click="followUser" v-if="!followStatus"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;&nbsp;关注她</el-button>
                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowUser"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;&nbsp;取消关注</el-button>
                    </div>
                    <div class="InviteAction" v-else-if="action == 'decide'">
                        <div class="Uncheck" v-if="applyStatus == 0">
                            <el-button class="FollowButton" type="primary" @click="applyPass"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;&nbsp;同意申请</el-button>
                            <el-button class="FollowButton" type="danger"  @click="applyDeny"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;拒绝申请</el-button>
                        </div>
                        <div class="checked" v-else-if="applyStatus == -1 || applyStatus == -2">
                            <el-button type="danger" :disabled="true"><i class="fa fa-user-times FollowIcon"></i>&nbsp;已拒绝</el-button>
                        </div>
                        <div class="checked" v-else>
                            <el-button type="primary" :disabled="true"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;已通过</el-button>
                        </div>
                    </div>
                </div>
                <div class="ManageAction" v-if="action == 'manage'">
                    <el-button type="primary" :disabled="true" v-if="user.pivot.title == '队长'"><i class="fa fa-pencil-square-o fa-fw FollowIcon"></i>&nbsp;&nbsp;更改职称</el-button>
                    <el-button type="primary" @click="titleDialogShow = true" v-else><i class="fa fa-pencil-square-o fa-fw FollowIcon"></i>&nbsp;&nbsp;更改职称</el-button>
                    <el-button type="danger" :disabled="true" v-if="user.pivot.title == '队长'"><i class="fa fa-user-times fa-fw FollowIcon"></i>&nbsp;&nbsp;移出团队</el-button>
                    <el-button type="danger" @click="removeMember" v-else><i class="fa fa-user-times fa-fw FollowIcon"></i>&nbsp;&nbsp;移出团队</el-button>
                </div>
            </div>
        </div>
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
        <el-dialog title="更改职称" :visible.sync="titleDialogShow" :modal="false">
            <el-input v-model="memberTitle" placeholder="请在这里输入队员新的职称"></el-input>
            <div class="ButtonGroup">
                <el-button type="primary" @click="updateMemberTitle">提交</el-button>
                <el-button @click="titleDialogShow = false">取消</el-button>
            </div>
        </el-dialog>
    </div>
</template>

<script>
    import UserPopover from './Popover/UserPopover.vue';
    import LoginDialog from './Dialog/LoginDialog.vue';
    export default {
        name:'TeamMemberList',
        props:{
            user:{
                type:Object,
                required:true
            },
            action:{
                default:'follow'
            },
            team:{
                type:Object
            },
            title:{
                default:'队员'
            },
            role:{
                default:'user'
            }
        },
        components:{UserPopover,LoginDialog},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                isInvited:false,
                loginShow:false,
                attitude_star: 0,
                ability_star: 0,
                description_match: 0,
                total_star: 0,
                applyStatus:0,
                followStatus:false,
                searchedKeywords:[],
                keywordsLoading:null,
                titleDialogShow:false,
                dialogImageUrl: '',
                pictureDialogVisible: false,
                titleDialogShow:false,
                memberTitle:''
            }
        },
        methods: {
            postInvite:function () {
                let that = this;
                if (localStorage.user) {
                    this.$axios.post('/api/user/inviteuser',{
                        to_id: that.user.id,
                        work_id: that.work ? that.work.id : null
                    }).then(response => {
                        return new Promise( (resolve,reject) => {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.isInvited = true;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            postUnInvite:function () {
                let that = this;
                if (localStorage.user) {
                    this.$axios.delete('/api/user/inviteuser/' + that.user.id,{
                        params:{
                            work_id: that.work ? that.work.id : null
                        }
                    }).then(response => {
                        return new Promise( (resolve,reject) => {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.isInvited = false;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            checkIsInvited:function () {
                let that = this;
                if (localStorage.user) {
                    this.$axios.get('/api/user/invitedcheck',{
                        params:{
                            to_id: that.user.id,
                            work_id: this.work ? this.work.id : null
                        }
                    }).then(response => {
                        return new Promise( (resolve,reject) => {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.isInvited = response.data.invited;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            applyPass:function () {
                let that = this;
                this.$axios.post('/api/team/member/applypass',{
                    team_id:this.team.id,
                    user_id:this.user.id
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.$emit('addMember',that.user);
                            that.$message.success(response.data.msg);
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            applyDeny:function () {
                let that = this;
                this.$axios.post('/api/team/member/applydeny',{
                    team_id:this.team.id,
                    user_id:this.user.id
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.applyStatus = -1;
                            that.$message.success(response.data.msg);
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            getReviewStatus:function () {
                let that = this;
                if (this.work) {
                    this.$axios.get('/api/user/review/status',{
                        params:{
                            work_id: that.work.id,
                            user_id: that.user.id
                        }
                    }).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.isReviewed = response.data.reviewed;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    }).catch(function (error) {
                        that.$message.error(error.msg);
                    })
                }
            },
            getTotalStar:function () {
                let that = this;
                this.$axios.get('/api/user/reviews',{
                    params:{
                        user_id: this.user.id,
                        total_star:true
                    }
                }).then( response => {
                    if (response.data.status == 1) {
                        that.total_star = Number(response.data.total_star);
                        that.attitude_star = Number(response.data.attitude_star);
                        that.ability_star = Number(response.data.ability_star);
                        that.description_match = Number(response.data.description_match);
                    } else {
                        that.$message.error(response.data.msg);
                    }
                }).catch( error => {
                    that.$message.error(error);
                })
            },
            followUser:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    if (this.role == 'employer') {
                        status = 21;
                    } else {
                        status = 11;
                    }
                    this.$axios.post('/api/user/followings',{
                        to_id: that.user.id,
                        status: status
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
                    if (this.role == 'employer') {
                        status = 21;
                    } else {
                        status = 11;
                    }
                    this.$axios.delete('/api/user/followings/' + that.user.id +'?status=' + status).then(function (response) {
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
            checkFollowstatus:function () {
                if (localStorage.user) {
                    let that = this;
                    if (this.role == 'employer') {
                        status = 21
                    } else {
                        status = 11
                    }
                    this.$axios.get('/api/user/followingcheck?to_id=' + that.user.id + '&status=' + status).then(function (response) {
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
            removeMember:function () {
                let that = this;
                this.$confirm('请确认是否要把该队员移出团队','提示',{
                    confirmButtonText:'确定',
                    cancleButtonText:'取消',
                    type:'warning'
                }).then( () => {
                    console.log(this.team)
                    this.$axios.delete('/api/team/member/' + this.user.id + '?team_id=' + this.team.id + '&role=owner').then( res => {
                        if (res.data.status ==1) {
                            that.$message.success(res.data.msg);
                            that.$emit('removeMember',that.user);
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    })
                }).catch( (err)=> {
                    console.log(err)
                    that.$message.info('取消操作');
                })

            },
            updateMemberTitle:function () {
                if (this.memberTitle == '') {
                    this.$message.error('内容不能为空')
                } else {
                    let that = this;
                    this.$axios.put('/api/team/member/' + this.user.id,{
                        title:this.memberTitle,
                        team_id:this.team.id
                    }).then( res => {
                        if (res.data.status == 1) {
                            that.$message.success(res.data.msg);
                            that.user.pivot.title = that.memberTitle;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    }).catch( err => {
                        console.log('updateMemberTitleError',err);
                    })
                }
            },
            init:function () {
                if (this.user.reviews_count > 0) {
                    this.getTotalStar();
                }
                if (this.action == 'invite') {
                    this.checkIsInvited();
                }
                if (this.action == 'review') {
                    this.getReviewStatus();
                }
                if (this.action == 'follow') {
                    this.checkFollowstatus();
                }

            }
        },
        created:function () {
            this.init();
        },
        watch:{
            user:function (newVal) {
                this.init();
            }
        }
    }
</script>

<style scoped>
    .ListItem {
        position: relative;
        padding: 16px 20px;
    }
    .ContentItem {
        display: flex;
    }
    .ContentItem-image {
        float: left;
        margin-right: 16px;
    }
    .ContentItem-head {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
        margin-right: 6px;
        margin-top:8px;
    }
    .ContentItem-title {
        font-size: 18px;
        font-weight: 700;
        display: inline-flex;
        margin-left:20px;
        line-height: 1.6;
        color: #1e1e1e;
        margin-top: -4px;
        margin-bottom: -4px;
    }
    .Member-name {
        width: 100px;
    }
    .Member-title {
        font-size: 14px;
        font-weight: normal;
        color: #999;
    }
    .ContentItem-meta {
        font-size: 15px;
        color: #555;
        margin-top:10px;
    }
    .ContentItem-status {
        margin-top: 5px;
        color: #8590a6;
        font-size: 14px;
        display: flex;
    }
    .ContentItem-statusItem {
        margin-left:20px;
    }
    .DetailStars {
        display: flex;
        margin-left:20px;
        margin-right:20px;
        margin-top: 5px;
        margin-bottom: 5px;
        width: 300px;
    }
    .ReviewCount {
        display: flex;
        justify-content: center;
    }
    .ReviewCount-text {
        font-size: 15px;
    }
    .Star-title {
        float: right;
        width: 100px;
        font-size:15px;
    }
    .FollowIcon {
        color: #ffffff;
    }
    .ContentItem-action {
        align-self: center;
    }
    .ContentItem-action .el-button {
        width: 120px;
    }
    .ReviewDialog .el-rate {
        margin-top:6px;
    }
    .ButtonGroup {
        margin-top: 20px;
        float: right;
        margin-bottom:20px;
    }
</style>