<template>
    <div class="ListItem">
        <div class="ContentItem">
            <div class="ContentItem-image">
                <router-link to="'/employer/' + employer.id" class="UserLink UserItem-avatar">
                    <EmployerPopover :employer="employer" pic-width="70"></EmployerPopover>
                </router-link>
            </div>
            <div class="ContentItem-head">
                <h2 class="ContentItem-title">
                    <EmployerPopover :employer="employer" :text="true"></EmployerPopover>
                </h2>
                <div class="ContentItem-meta">
                    <div class="ContentItem-stars">
                        <div class="NoReview DetailStars" v-if="employer.reviews_count == 0">
                            <span class="Star-title">综合评分&nbsp;:</span>暂无评分
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
                                <span class="Star-title">薪资待遇&nbsp;:</span>
                                <el-rate v-model="treat_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">描述相符&nbsp;:</span>
                                <el-rate v-model="description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">工资发放速度&nbsp;:</span>
                                <el-rate v-model="pay_speed" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
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
                        <el-button class="FollowButton" type="primary" @click="followEmployer" v-if="!followStatus"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注她</el-button>
                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowEmployer"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;取消关注</el-button>
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
                        <el-form-item label="薪资待遇" prop="treat_star">
                            <el-rate v-model="reviewForm.treat_star" show-text></el-rate>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="描述相符" prop="description_match">
                            <el-rate v-model="reviewForm.description_match" show-text></el-rate>
                        </el-form-item>
                    </el-col>
                    <el-col :span="8">
                        <el-form-item label="工资发放速度" prop="pay_speed">
                            <el-rate v-model="reviewForm.pay_speed" show-text></el-rate>
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
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
    </div>
</template>

<script>
    import EmployerPopover from '../Popover/EmployerPopover.vue';
    import LoginDialog from '../Dialog/LoginDialog.vue';
    export default {
        name:'EmployerFollowList',
        props:{
            employer:{
                type:Object,
                required:true
            },
            action:{
                default:'follow'
            },
            work:{
                type:Object
            },
            role:{
                default:'user'
            }
        },
        components:{EmployerPopover,LoginDialog},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                isReviewed:false,
                loginShow:false,
                reviewDialogShow:false,
                uploadConfig: {
                    headers:{
                        Authorization: 'bearer' + localStorage.token
                    },
                    data:{
                        work_id: this.work ? this.work.id : null,
                        employer_id: this.employeremployer ? this.employer.id : null
                    }
                },
                fileList:[],
                treat_star: 0,
                pay_speed: 0,
                description_match: 0,
                total_star: 0,
                applyStatus:0,
                followStatus:false,
                reviewForm:{
                    treat_star: null,
                    pay_speed: null,
                    description_match: null,
                    text:null,
                    work_id: this.work ? this.work.id : null,
                    employer_id: this.employer.id
                },
                reviewRules:{
                    treat_star: [
                        { type:'integer',required: true, message: '请给薪资待遇打分', trigger: 'change' },
                        { type:'integer',min: 1, max: 5, message: '评分只能在1跟5之间', trigger: 'change' }
                    ],
                    pay_speed: [
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
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
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
                if (this.work) {
                    this.$axios.get('/api/employer/review/status',{
                        params:{
                            work_id: that.work.id,
                            employer_id: that.employer.id
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
                    this.$axios.delete('/api/user/followings/' + that.employer.id +'?status=' + status).then(function (response) {
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
                        status = 22
                    } else {
                        status = 12
                    }
                    this.$axios.get('/api/user/followingcheck?to_id=' + that.employer.id + '&status=' + status).then(function (response) {
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
            handlePictureRemove:function (file,fileList) {
                console.log(file,fileList)
            },
            init:function () {
                if (this.employer.reviews_count > 0) {
                    this.getTotalStar();
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
        margin-left:20px;
        line-height: 1.6;
        color: #1e1e1e;
        margin-top: -4px;
        margin-bottom: -4px;
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
</style>