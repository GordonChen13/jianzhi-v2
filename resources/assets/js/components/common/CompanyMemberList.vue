<template>
    <div class="ListItem">
        <div class="ContentItem">
            <div class="ContentItem-image">
                <router-link to="'/employer/' + employer.id" class="UserLink UserItem-avatar">
                    <EmployerPopover :employer="employer" pic-width="70" role="employer" v-if="role == 'employer'"></EmployerPopover>
                    <EmployerPopover :employer="employer" pic-width="70"  v-else></EmployerPopover>
                </router-link>
            </div>
            <div class="ContentItem-head">
                <div class="ContentItem-title">
                    <EmployerPopover :employer="employer" :text="true" role="employer" v-if="role == 'employer'" class="Member-name"></EmployerPopover>
                    <EmployerPopover :employer="employer" :text="true" v-else class="Member-name"></EmployerPopover>
                    <div class="IsMember" v-if="employer.pivot">
                        <span class="Member-title" v-if="employer.pivot.title">{{title}}</span>
                        <span class="Member-title" v-else>成员</span>
                    </div>
                </div>
                <div class="ContentItem-meta">
                    <div class="ContentItem-stars">
                        <div class="NoReview DetailStars" v-if="employer.reviews_count == 0">
                            <span class= "tar-title">综合评分&nbsp;:</span>暂无评分
                        </div>
                        <el-popover  placement="bottom"  trigger="hover" v-else>
                            <div class="DetailStars" slot="reference">
                                <span class="Star-title">综合评分&nbsp;:</span>
                                <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="ReviewCount">
                                <router-link :to="'/employer/' + employer.id + '/reviews'">
                                    <span class="ReviewCount-text">来自&nbsp;{{employer.reviews_count}}&nbsp;份评价</span>
                                </router-link>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">工作态度&nbsp;:</span>
                                <el-rate v-model="treat_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">办事能力&nbsp;:</span>
                                <el-rate v-model="pay_speed" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">描述相符&nbsp;:</span>
                                <el-rate v-model="description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                        </el-popover>
                    </div>
                    <div class="ContentItem-status">
                        <router-link :to="'/employer/' + employer.id + '/works'">
                            <div class="ContentItem-statusItem">{{employer.checked_works_count}}&nbsp;兼职</div>
                        </router-link>
                        <div class="ContentItem-statusItem">{{employer.employer_exp}}&nbsp;经验</div>
                        <router-link :to="'/employer/' + employer.id + '/following'">
                            <div class="ContentItem-statusItem">{{employer.user_followers_count}}&nbsp;粉丝</div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="ContentItem-action">
                <div class="ManAction" v-if="employer.gender == '男'">
                    <div class="FollowAction"  v-if="action == 'follow'">
                        <el-button class="FollowButton" type="primary" @click="followEmployer" v-if="!followStatus"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注他</el-button>
                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowEmployer"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;取消关注</el-button>
                    </div>
                </div>
                <div class="WomanAction" v-else>
                    <div class="FollowAction"  v-if="action == 'follow'">
                        <el-button class="FollowButton" type="primary" @click="followEmployer" v-if="!followStatus"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;&nbsp;关注她</el-button>
                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowEmployer"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;&nbsp;取消关注</el-button>
                    </div>
                </div>
                <div class="ManageAction" v-if="action == 'manage'">
                    <el-button type="primary" :disabled="true" v-if="employer.pivot.title == 'owner'"><i class="fa fa-pencil-square-o fa-fw FollowIcon"></i>&nbsp;&nbsp;更改职称</el-button>
                    <el-button type="primary" @click="titleDialogShow = true" v-else><i class="fa fa-pencil-square-o fa-fw FollowIcon"></i>&nbsp;&nbsp;更改职称</el-button>
                    <el-button type="danger" :disabled="true" v-if="employer.pivot.title == 'owner'"><i class="fa fa-user-times fa-fw FollowIcon"></i>&nbsp;&nbsp;移出公司</el-button>
                    <el-button type="danger" @click="removeMember" v-else><i class="fa fa-user-times fa-fw FollowIcon"></i>&nbsp;&nbsp;移出公司</el-button>
                </div>
                <div class="JoinAction"  v-if="action == 'join'">
                    <el-button class="FollowButton" type="primary" @click="joinCompany" v-if="!isJoined"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;拉入公司</el-button>
                    <el-button :disabled="true" v-else>已加入</el-button>
                </div>
            </div>
        </div>
        <el-dialog title="更改职称" :visible.sync="titleDialogShow" :modal="false">
            <el-input v-model="memberTitle" placeholder="请在这里输入成员新的职称"></el-input>
            <div class="ButtonGroup">
                <el-button type="primary" @click="updateMemberTitle">提交</el-button>
                <el-button @click="titleDialogShow = false">取消</el-button>
            </div>
        </el-dialog>
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
    </div>
</template>

<script>
    import EmployerPopover from './Popover/EmployerPopover.vue';
    import LoginDialog from './Dialog/LoginDialog.vue';
    export default {
        name:'CompanyMemberList',
        props:{
            employer:{
                type:Object,
                required:true
            },
            action:{
                default:'follow'
            },
            company:{
                type:Object
            },
            title:{
                default:'成员'
            },
            role:{
                default:'user'
            }
        },
        components:{EmployerPopover,LoginDialog},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                treat_star: 0,
                pay_speed: 0,
                description_match: 0,
                total_star: 0,
                followStatus:false,
                loginShow:false,
                searchedKeywords:[],
                keywordsLoading:null,
                titleDialogShow:false,
                dialogImageUrl: '',
                pictureDialogVisible: false,
                memberTitle:'',
                isJoined:false
            }
        },
        methods: {
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            postInvite:function () {
                let that = this;
                if (localStorage.user) {
                    this.$axios.post('/api/employer/inviteemployer',{
                        to_id: that.employer.id,
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
            getTotalStar:function () {
                let that = this;
                this.$axios.get('/api/employer/reviews',{
                    params:{
                        employer_id: this.employer.id,
                        total_star:true
                    }
                }).then( response => {
                    if (response.data.status == 1) {
                        that.total_star = Number(response.data.total_star);
                        that.treat_star = Number(response.data.treat_star);
                        that.pay_speed = Number(response.data.pay_speed);
                        that.description_match = Number(response.data.description_match);
                    } else {
                        that.$message.error(response.data.msg);
                    }
                }).catch( error => {
                    that.$message.error(error);
                })
            },
            followEmployer:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    if (this.role == 'employer') {
                        status = 22;
                    } else {
                        status = 12;
                    }
                    this.$axios.post('/api/user/followings',{
                        to_id: that.employer.id,
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
            unFollowEmployer:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    if (this.role == 'employer') {
                        status = 22;
                    } else {
                        status = 12;
                    }
                    this.$axios.delete('/api/employer/followings/' + that.employer.id +'?status=' + status).then(function (response) {
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
                if (localStorage.employer) {
                    let that = this;
                    if (this.role == 'employer') {
                        status = 22
                    } else {
                        status = 12
                    }
                    this.$axios.get('/api/employer/followingcheck?to_id=' + that.employer.id + '&status=' + status).then(function (response) {
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
                this.$confirm('请确认是否要把该成员移出公司','提示',{
                    confirmButtonText:'确定',
                    cancleButtonText:'取消',
                    type:'warning'
                }).then( () => {
                    console.log(this.company)
                    this.$axios.delete('/api/company/member/' + this.employer.id + '?company_id=' + this.company.id + '&role=owner').then( res => {
                        if (res.data.status ==1) {
                            that.$message.success(res.data.msg);
                            that.$emit('removeMember',that.employer);
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
                    this.$axios.put('/api/company/member/' + this.employer.id,{
                        title:this.memberTitle,
                        company_id:this.company.id
                    }).then( res => {
                        if (res.data.status == 1) {
                            that.$message.success(res.data.msg);
                            that.employer.pivot.title = that.memberTitle;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    }).catch( err => {
                        console.log('updateMemberTitleError',err);
                    })
                }
            },
            joinCompany:function () {
                let that = this;
                this.$axios.post('/api/company/member',{
                    company_id: this.company.id,
                    employer_id: this.employer.id
                }).then( res => {
                    if (res.data.status == 1) {
                        that.employer.pivot = {};
                        that.employer.pivot.title = '成员';
                        that.company.members.push(that.employer);
                        that.isJoined = true;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            init:function () {
                if (this.employer.reviews_count > 0) {
                    this.getTotalStar();
                }
                if (this.action == 'follow') {
                    this.checkFollowstatus();
                }
                if (this.action == 'join') {
                    let that = this;
                    let index = this.$_.findIndex(this.company.members,function (item) {
                        return item.id == that.employer.id
                    })
                    if (index !== -1) {
                        this.isJoined = true;
                    } else {
                        this.isJoined = false;
                    }
                }
            }
        },
        created:function () {
            this.init();
        },
        watch:{
            employer:function (newVal) {
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