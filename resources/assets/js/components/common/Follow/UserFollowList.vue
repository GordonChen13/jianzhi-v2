<template>
    <div class="ListItem">
        <div class="ContentItem">
            <div class="ContentItem-image">
                <router-link to="/user/1" class="UserLink UserItem-avatar">
                    <UserPopover :user="user" pic-width="70"></UserPopover>
                </router-link>
            </div>
            <div class="ContentItem-head">
                <h2 class="ContentItem-title">
                    <UserPopover :user="user" :text="true"></UserPopover>
                </h2>
                <div class="ContentItem-meta">
                    <div class="ContentItem-stars">
                        <el-popover  placement="bottom"  trigger="hover">
                            <div class="DetailStars" slot="reference">
                                <span class="Star-title">综合评分&nbsp;:</span>
                                <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
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
                        <div class="ContentItem-statusItem">500&nbsp;经验</div>
                        <router-link :to="'/user/' + user.id + '/following'">
                            <div class="ContentItem-statusItem">{{user.user_followers_count}}&nbsp;粉丝</div>
                        </router-link>
                    </div>
                </div>
            </div>
            <div class="ContentItem-action">
                <div class="ManAction" v-if="user.gender == '男'">
                    <div class="FollowAction"  v-if="action == 'follow'">
                        <el-button class="FollowButton" type="primary"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注他</el-button>
                    </div>
                    <div class="InviteAction" v-else-if="action == 'invite'">
                        <el-button class="FollowButton" type="danger" v-if="isInvited" @click="postUnInvite"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;取消邀请</el-button>
                        <el-button class="FollowButton" type="primary" v-else @click="postInvite"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;邀请他</el-button>
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
                        <el-button class="FollowButton" type="primary"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注她</el-button>
                    </div>
                    <div class="InviteAction" v-else-if="action == 'invite'">
                        <el-button class="FollowButton" type="primary" v-if="!isInvited" @click="postInvite"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;邀请她</el-button>
                        <el-button class="FollowButton" type="danger" v-else @click="postUnInvite"><i class="fa fa-user-plus FollowIcon"></i>&nbsp;取消邀请</el-button>
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
                <div class="ReviewAction" v-if="action == 'review'">
                    <el-button type="primary" @click="reviewDialogShow = true" v-if="isReviewed == false">评价</el-button>
                    <el-button type="primary" :disabled="true" v-else>已评价</el-button>
                </div>
            </div>
        </div>
        <el-dialog title="对该申请人的评价" :visible.sync="reviewDialogShow" class="ReviewDialog">
            <el-form :model="reviewForm" :rules="reviewRules" ref="reviewForm" label-width="100px">
                <el-row>
                    <el-col :span="8">
                        <el-form-item label="工作态度" prop="attitude_star">
                            <el-rate v-model="reviewForm.attitude_star" show-text></el-rate>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="办事能力" prop="ability_star">
                            <el-rate v-model="reviewForm.ability_star" show-text></el-rate>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="描述相符" prop="description_match">
                            <el-rate v-model="reviewForm.description_match" show-text></el-rate>
                        </el-form-item>
                    </el-col>
                </el-row>
                <el-form-item label="评价内容" prop="text">
                    <el-input v-model="reviewForm.text" type="textarea" :rows="3" placeholder="请在这里输入详细的评价内容" style="width: 80%"></el-input>
                </el-form-item>
                <el-form-item label="兼职照片">
                    <el-upload ref="pictureUpload" action="/api/employer/review/picture" list-type="picture-card" :on-preview="handlePictureCardPreview"
                               :headers="uploadConfig.headers" name="picture" :data="uploadConfig.data"
                               :multiple="true" :on-success="handleUploadSuccess" :on-error="handleUploadError" accept=".jpg,.png"
                               :before-upload="beforePictureUpload":on-remove="handlePictureRemove" :auto-upload="false">
                        <i class="el-icon-plus" style="margin-top:60px;"></i>
                    </el-upload>
                </el-form-item>
                <el-form-item>
                    <el-button type="primary" @click="submitForm('reviewForm')">提交评价</el-button>
                    <el-button @click="resetForm('reviewForm')">重置</el-button>
                </el-form-item>
            </el-form>
        </el-dialog>
        <el-dialog v-model="pictureDialogVisible" size="tiny">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
    </div>
</template>

<script>
    import UserPopover from '../Popover/UserPopover.vue';
    export default {
        name:'UserFollowList',
        props:{
            user:{
                type:Object,
                required:true
            },
            action:{
                default:'follow'
            },
            work:{
                type:Object
            }
        },
        components:{UserPopover},
        data() {
            return {
                me: JSON.parse(localStorage.user) ? JSON.parse(localStorage.user) : null,
                isInvited:false,
                isReviewed:false,
                reviewDialogShow:false,
                uploadConfig: {
                    headers:{
                        Authorization: 'bearer' + localStorage.token
                    },
                    data:{
                        work_id: this.work.id,
                        user_id: this.user.id
                    }
                },
                fileList:[],
                attitude_star: 4.2,
                ability_star: 4,
                description_match: 4.6,
                total_star: 4.4,
                applyStatus:0,
                reviewForm:{
                    attitude_star: null,
                    ability_star: null,
                    description_match: null,
                    text:null,
                    work_id: this.work.id,
                    user_id: this.user.id
                },
                reviewRules:{
                    attitude_star: [
                        { type:'integer',required: true, message: '请给工作态度打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    ability_star: [
                        { type:'integer',required: true, message: '请给工作能力打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    description_match: [
                        { type:'integer',required: true, message: '请给描述相符打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    text: [
                        {type:"string",trigger:"change"},
                        { required: true, message: '请输入具体的评价', trigger: 'blur' }
                    ],
                },
                dialogImageUrl: '',
                pictureDialogVisible: false
            }
        },
        methods: {
            postInvite:function () {
                let that = this;
                if (localStorage.user) {
                    this.$axios.post('/api/user/inviteuser',{
                        to_id: that.user.id,
                        work_id: that.work.id
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
            postUnInvite:function () {
                let that = this;
                if (localStorage.user) {
                    this.$axios.delete('/api/user/inviteuser/' + that.user.id,{
                        params:{
                            work_id: that.work.id
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
                            work_id: that.work.id
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
                this.$axios.put('/api/work/' + this.work.id + '/applicant/' + this.user.id,{
                    result:'pass'
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.applyStatus = 1;
                            this.$emit('addHiredNum');
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
                this.$axios.put('/api/work/' + this.work.id + '/applicant/' + this.user.id,{
                    result:'deny'
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
            getApplyStatus:function () {
                let that = this;
                this.$axios.get('/api/work/' + this.work.id + '/applicant/' + this.user.id).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status == 1) {
                            resolve(response.data);
                            that.applyStatus = response.data.applyStatus;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            handlePictureCardPreview:function(file) {
                this.dialogImageUrl = file.url;
                this.pictureDialogVisible = true;
            },
            submitForm:function (name) {
                console.log(this.reviewForm);
                this.$refs[name].validate((valid) => {
                    if (valid) {
                        let that = this;
                        this.$axios.post('/api/employer/reviews',this.reviewForm).then(function (response) {
                            return new Promise( function (resolve, reject) {
                                if (response.data.status == 1) {
                                    resolve(response.data);
                                    that.$refs.pictureUpload.submit();
                                    that.$message.success(response.data.msg);
                                } else {
                                    reject(response.data);
                                    that.$message.error(response.data.msg);
                                }
                            })
                        }).catch(function (error) {
                            that.$message.error(error.msg)});
                    } else {
                        that.$message.error('选项不能为空');
                        return false;
                    }
                });
            },
            resetForm:function (name) {
                this.$refs[name].resetFields();
                this.$refs.pictureUpload.clearFiles();
            },
            handleUploadSuccess:function (response, file, fileList) {
                if (fileList.indexOf(file) == fileList.length -1) {
                    this.$message.success('评价成功');
                    this.reviewDialogShow = false;
                    this.isReviewed = true;
                }
            },
            handleUploadError:function (error, file, fileList) {
                this.$messgae.error(error.msg);
            },
            beforePictureUpload:function (file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;
                console.log([isJPG + isPNG == 0,'hajhah'])
                if (isJPG + isPNG == 0) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            getReviewStatus:function () {
                let that = this;
                this.$axios.get('/api/employer/review/status',{
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
            },
            handlePictureRemove:function (file,fileList) {
                console.log(file,fileList)
            },
            init:function () {
                if (this.action == 'invite') {
                    this.checkIsInvited();
                }
                if (this.action == 'decide') {
                    this.getApplyStatus();
                }
                if (this.action == 'review') {
                    this.getReviewStatus();
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
        margin-left:20px;
        line-height: 1.6;
        color: #1e1e1e;
        margin-top: -4px;
        margin-bottom: -4px;
    }
    .ContentItem-meta {
        font-size: 15px;
        color: #555;
        margin-top:6px;
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
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
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
</style>