<template>
    <div class="CornerButtons">
        <div class="CornerAnimayedFlex">
            <el-tooltip effect="dark" content="意见反馈" placement="left">
                <el-button class="Button-plain CornerButton " @click="FeedbackVisible = true"><i class="fa fa-archive Icon"></i></el-button>
            </el-tooltip>
        </div>
        <div class="CornerAnimayedFlex">
            <el-tooltip effect="dark" content="返回顶部" placement="left">
                <el-button class="Button-plain CornerButton " @click="this.scrollTo(0,0)"><i class="fa fa-arrow-up Icon"></i></el-button>
            </el-tooltip>
        </div>
        <el-dialog title="意见反馈" :visible.sync="FeedbackVisible" size="tiny" class="FeedbackDialog" :modal-append-to-body="false">
            <el-input type="textarea" :rows="3"
                    placeholder="告诉我们你遇到的问题或者你的想法" v-model="feedback">
            </el-input>
            <span slot="footer" class="dialog-footer">
                <el-button @click="FeedbackVisible = false">取 消</el-button>
                <el-button type="primary" @click="uploadAdvice">确 定</el-button>
            </span>
        </el-dialog>
        <LoginDialog :show.sync ="loginShow"></LoginDialog>
    </div>
</template>

<script>
    import axios from 'axios';
    import LoginDialog from '../common/Dialog/LoginDialog.vue';
    export default {
        name:'CornerButtons',
        components:{LoginDialog},
        data() {
            return {
                FeedbackVisible: false,
                IMVisible: false,
                loginShow:false,
                feedback:''
            }
        },
        methods: {
            checkLogin: function () {
                if (!localStorage.user) {
                    this.loginShow = true;
                }
            },
            uploadAdvice:function () {
                this.checkLogin();
                let that = this;
                if (!that.feedback) {
                    that.$message.error('建议不能为空');
                } else if (localStorage.user) {
                    axios.post('/api/user/advice',{
                        text:that.feedback
                    }).then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.FeedbackVisible = false;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
        }
    }
</script>

<style scoped>
    .CornerButtons {
        position: fixed;
        bottom: 0;
        right: 12px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        transition: -webkit-transform .2s ease;
        transition: transform .2s ease;
        transition: transform .2s ease,-webkit-transform .2s ease;
        will-change: transfrom;
    }
    .CornerAnimayedFlex {
        width: 50px;
        height: 62px;
        transition: height .2s ease;
    }
    .CornerButton {
        background: #fff;
        border-radius: 4px;
        width: 50px;
        height: 50px;
        box-shadow: 0 1px 3px rgba(0,0,0,.1);
    }
    .Button-plain {
        padding: 0;
        line-height: inherit;
        border: none;
        border-radius: 0;
    }
    .Icon {
        font-size:22px;
        color: #9aaabf;
    }
    .FeedbackDialog {
        z-index:10;
    }
    .FeedbackDialog {
        z-index:12;
    }
</style>