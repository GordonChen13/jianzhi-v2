<template>
    <div class="ReviewList-main" v-if="user">
        <el-row class="ListRow">
            <el-col :span="3" class="Review-avatar">
                <UserPopover :user="user" :pic-width="50"></UserPopover>
            </el-col>
            <el-col :span="21" class="Review-right">
                <div class="ReviewHead">
                    <div class="ReviewHead-name">
                        <UserPopover :user="user" :text="true"></UserPopover>
                    </div>
                    <div class="ReviewStars">
                        <el-popover  placement="bottom"  trigger="hover">
                            <div class="DetailStars TotalStars" slot="reference">
                                <span class="Star-title Star-title-all">给出的评分&nbsp;:</span>
                                <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
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
                    <div class="WorkInfo" v-if="work">
                        <span class="Work-label">来自兼职&nbsp;:</span>
                        <router-link :to="'/work/' + work.id">
                            <span class="Work-title">{{work.title}}</span>
                        </router-link>
                    </div>
                </div>
                <div class="ReviewTags" v-if="review.keywords.length > 0">
                    <el-tag type="gray" class="Tag-item" v-for="keyword in review.keywords">{{keyword.value}}</el-tag>
                    <span class="date">{{fromNow(review.created_at)}}</span>
                </div>
                <div class="ReviewTags" v-else>
                    <el-tag type="gray" class="Tag-item">该评价没有标签</el-tag>
                    <span class="date">{{fromNow(review.created_at)}}</span>
                </div>
                <div class="ReviewContent">
                    <div class="ReviewType">[认真评价]</div>
                    <div class="ReviewDetail">{{review.content}}</div>
                    <div class="ReviewPicture" v-if="review.pic_path.length > 0">
                        <div class="PictureLists">
                            <div v-for="path in review.pic_path" :src="'/storage/' + path">
                                <img :src="'/storage/' + path" alt="评价图片" class="PictureItem" @click="changeActivePicture(path)">
                            </div>
                        </div>
                        <div class="PictureContent" v-if="pictureShow">
                            <img :src="'/storage/' + activePath" width="400" alt="评价大图" class="ActivePicture" @click="pictureShow = false">
                        </div>
                    </div>
                    <div class="ReviewBottom" v-if="me && me.id == review.employer_id">
                        <div class="ReviewAction">
                            <span class="Replied" v-if="review.reply_count > 0">企业回复</span>
                            <el-button type="text" v-else @click="replyDialogShow = true" class="ReplyButton">回复</el-button>
                            <a href="javascript:;" class="Up-thumbs">
                                <el-button type="text" :disabled="true"><i class="fa fa-thumbs-up-o"></i>有用<span class="Up-number">(&nbsp;{{review.useful_count}}&nbsp;)</span></el-button>
                            </a>
                            <el-dialog title="回复对我的评价" :visible.sync="replyDialogShow" size="tiny">
                                <el-input type="textarea" :rows="3" v-model="replyText" placeholder="请在这里输入你的回复"></el-input>
                                <div class="DialogFooter" slot="footer">
                                    <el-button @click="replyText='';replyDialogShow = false">取消</el-button>
                                    <el-button type="primary" @click="postReviewReply">提交</el-button>
                                </div>
                            </el-dialog>
                            <div class="ReviewReply" v-if="review.reply_count > 0">
                                <div class="TriangleOut"></div>
                                <div class="TriangleIn"></div>
                                <div class="ReplyContent" v-if="employer">
                                    <el-col :span="4" class="Review-avatar">
                                        <img  :src="'/storage/' + employer.pic_path" width="50px" alt="图片被外星人劫持啦！">
                                    </el-col>
                                    <el-col :span="20" class="Review-right">
                                        <div class="ReplyHead">
                                            <span class="Employer-name">{{employer.name}}</span>
                                            <span class="date">{{$moment(review.reply.created_at).fromNow()}}</span>
                                        </div>
                                        <div class="ReplyDetail">{{review.reply.content}}</div>
                                    </el-col>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ReviewBottom" v-else>
                        <div class="ReviewAction">
                            <span class="Replied" v-if="review.reply_count > 0">企业回复</span>
                            <a href="javascript:;" class="Up-thumbs">
                                <el-button type="text" @click="postReviewUseful"><i class="fa fa-thumbs-up-o"></i>有用<span class="Up-number">(&nbsp;{{review.useful_count}}&nbsp;)</span></el-button>
                            </a>
                        </div>
                        <div class="ReviewReply" v-if="review.reply_count > 0">
                            <div class="TriangleOut"></div>
                            <div class="TriangleIn"></div>
                            <div class="ReplyContent" v-if="employer">
                                <el-col :span="4" class="Review-avatar">
                                    <img  :src="'/storage/' + employer.pic_path" width="50px" alt="图片被外星人劫持啦！">
                                </el-col>
                                <el-col :span="20" class="Review-right">
                                    <div class="ReplyHead">
                                        <span class="Employer-name">{{employer.name}}</span>
                                        <span class="date">{{$moment(review.reply.created_at).fromNow()}}</span>
                                    </div>
                                    <div class="ReplyDetail">{{review.reply.content}}</div>
                                </el-col>
                            </div>
                        </div>
                    </div>

                </div>
            </el-col>
        </el-row>
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
    </div>
</template>

<script>
    import UserPopover from '../common/Popover/UserPopover.vue';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    export default {
        name: 'ReviewList',
        props:['review'],
        components: {UserPopover,LoginDialog},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                user:null,
                employer:null,
                work:null,
                total_star: Number(this.review.total_star),
                treat_star: Number(this.review.treat_star),
                description_match: Number(this.review.description_match),
                pay_speed: Number(this.review.pay_speed),
                activePath:'',
                pictureShow:false,
                replyText:'',
                replyDialogShow:false,
                loginShow: false
            }
        },
        methods: {
            fromNow:function (date) {
                return this.$moment(date).fromNow();
            },
            checkLogin:function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            getUser:function () {
                let that = this;
                this.$axios.get('/api/users/' + this.review.user_id).then (function (res) {
                    if (res.data.status == 1) {
                        that.user = res.data.user;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch(function (error) {
                    console.log(['getUserError',error]);
                })
            },
            getEmployer:function () {
                let that = this;
                this.$axios.get('/api/employers/' + this.review.employer_id).then (function (res) {
                    if (res.data.status == 1) {
                        that.employer = res.data.employer;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch(function (error) {
                    console.log(['getEmployerError',error]);
                })
            },
            getWork:function () {
                let that = this;
                this.$axios.get('/api/works/' + this.review.work_id).then (function (res) {
                    if (res.data.status == 1) {
                        that.work = res.data.work;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            changeActivePicture:function (path) {
                this.activePath = path;
                this.pictureShow = true;
            },
            postReviewReply:function () {
                let that = this;
                this.$axios.post('/api/employer/review/reply',{
                    review_id: this.review.id,
                    text:this.replyText
                }).then(res => {
                    if (res.data.status ==1) {
                        that.$message.success(res.data.msg);
                        that.review.reply = res.data.reply;
                        that.review.reply_count = 1;
                        that.employer = this.me;
                        that.replyDialogShow = false;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            postReviewUseful:function () {
                this.checkLogin();
                if (localStorage.user) {
                    let that = this;
                    this.$axios.post('/api/employer/review/useful',{
                        review_id:this.review.id
                    }).then( res => {
                        if (res.data.status == 1) {
                            that.$message.success(res.data.msg);
                            that.review.useful_count += 1;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    }).catch( err => {
                        console.log(err);
                    })
                }
            },
            init:function () {
                this.getUser();
                this.getWork();
                if (this.review.reply_count > 0) {
                    this.getEmployer();
                }
            }
        },
        watch:{
            review:function (newVal) {
                this.init();
            }
        },
        created: function () {
            this.init();
        }
    }
</script>

<style scoped>
    .ReviewList-main {
        width: 100%;
        margin-left:20px;
        margin-top: 20px;
    }
    .ListRow {
        margin-bottom: 10px;
    }
    .Review-avatar {
        width: 60px;
    }
    .ReviewHead {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
    }
    .ReviewHead-name {
        width: 80px;
    }
    .Review-right {
        float: left;
    }
    .ReviewStars {
        margin-left:10px;
    }
    .DetailStars {
        display: flex;
        width:250px;
        margin-left:20px;
        margin-right:20px;
        margin-bottom:10px;
    }
    .TotalStars {
        margin-left: 0px;
    }
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
        font-size:15px;
    }
    .WorkInfo {
        float: right;
        color: #999;
        margin-left: 10px;
        width: 300px;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }
    .Work-title {
        color: black;
        font-weight: 500;
    }
    .Work-title:hover {
        color: #175199;
    }
    .ReviewTags {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
    }
    .Tag-item {
        padding:0px 10px;
        margin-left:10px;
    }
    .date {
        margin-left:auto;
        color: #999;;
        align-self: center;
    }
    .ReviewContent {
        margin-bottom:10px;
        margin-top: 10px;
    }
    .ReviewType {
        display: inline-block;
        color: #999;
    }
    .ReviewDetail {
        margin-top:10px;
        display:inline;
    }
    .ReviewPicture {
        margin: 10px 0px 5px 0px;
    }
    .PictureLists {
        display: flex;
    }
    .PictureItem {
        width: 60px;
        height: 60px;
        margin-right:10px;
        cursor: zoom-in;
    }
    .ActivePicture {
        margin-top: 10px;
        cursor: zoom-out;
    }
    .Replied {
        padding-right: 10px;
        border-right: 1px solid #dce4e6;
        color: #999;
    }
    .Up-thumbs {
        margin-left:10px;
    }
    .Employer-name {
        margin-bottom: 6px;
        color: #999;
    }
    .ReviewReply {
        position: relative;
    }
    .TriangleOut {
        position: absolute;
        top: -10px;
        left: 16px;
        width: 0;
        height: 0;
        border-right: 10px solid transparent;
        border-bottom: 10px solid #dce4e6;
    }
    .TriangleIn {
        position: absolute;
        top: -8px;
        left: 17px;
        width: 0;
        height: 0;
        border-right: 9px solid transparent;
        border-bottom: 9px solid #fafafa;
    }
    .ReplyContent {
        padding: 14px 10px 20px;
        margin-top: 10px;
        border: 1px solid #dce4e6;
        border-radius: 3px;
        background-color: #fafafa;
        overflow: hidden;
    }
    .ReplyHead {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
        width:550px;
    }
</style>