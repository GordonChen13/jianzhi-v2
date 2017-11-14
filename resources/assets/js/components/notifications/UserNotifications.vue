<template>
    <div class="Container">
        <ChatDialog :show.sync="chatDialogShow"></ChatDialog>
    </div>
</template>

<script>
    import ChatDialog from '../common/Dialog/ChatDialog.vue';
    export default {
        name:'UserNotifications',
        components: {ChatDialog},
        data(){
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                chatDialogShow:false
            }
        },
        methods:{
            ApplyPassedEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('ApplyPassed',(work) => {
                    that.$notify.success({
                        title:'你的兼职申请已经通过了',
                        message:'来自兼职:   ' + work.data.title + '  --  ' + work.data.pay_amount + '/' + work.data.settlement_type + '  --  ' + work.data.city,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: work.data.id}
                            })
                        }
                    })
                })
            },
            ApplyDeniedEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('ApplyDenied',(work) => {
                    console.log(work)
                    that.$notify.warning({
                        title:'很遗憾，你的兼职申请没有通过',
                        message:'来自兼职:   '  + work.data.title + '  --  ' + work.data.pay_amount + '/' + work.data.settlement_type + '  --  ' + work.data.city,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: work.data.id}
                            })
                        }
                    })
                })
            },
            NewWorkEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewWork',(payload) => {
                    that.$notify.info({
                        title: payload.employer.name + '   发布了新的兼职',
                        message:'兼职内容:   '  + payload.work.title + '  --  ' + payload.work.pay_amount + '/' + payload.work.settlement_type + '  --  ' + payload.work.city,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: payload.work.id}
                            })
                        }
                    })
                })
            },
            NewAnswerEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewAnswer',(payload) => {
                    console.log(payload,Object.keys(payload))
                    that.$notify.info({
                        title: payload.employer.name + '   回复了来自兼职   ' + payload.work.title + '   的咨询',
                        message:'[咨询内容:]   '  + payload.answer.question.content +
                                '           [回复内容:]   '  + payload.answer.content,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: payload.work.id}
                            })
                        }
                    })
                })
            },
            NewFollowerEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewFollower',function (payload) {
                    if (payload.type == '11') {
                        that.$notify.success({
                            title: payload.from_user.name + '   关注了你',
                            message:'[粉丝简介:]   ' + payload.from_user.introduction,
                            duration:0,
                            onClick: function ()  {
                                that.$router.push({
                                    name:'user',
                                    params:{id: payload.from_user.id}
                                })
                            }
                        })
                    } else if (payload.type == '21') {
                        that.$notify.success({
                            title: '[雇主:]' + payload.from_employer.name + '   关注了你',
                            message:'[雇主简介:]   ' + payload.from_employer.introduction,
                            duration:0,
                            onClick: function ()  {
                                that.$router.push({
                                    name:'employer',
                                    params:{id: payload.from_employer.id}
                                })
                            }
                        })
                    } else {

                    }

                })
            },
            LostFollowerEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('LostFollower',function (payload) {
                    if (payload.type == '11') {
                        that.$notify.warning({
                            title: payload.from_user.name + '   取消关注你了',
                            message:'[粉丝简介:]   ' + payload.from_user.introduction,
                            duration:0,
                            onClick: function ()  {
                                that.$router.push({
                                    name:'user',
                                    params:{id: payload.from_user.id}
                                })
                            }
                        })
                    } else if (payload.type == '21') {
                        that.$notify.warning({
                            title: '[雇主:]' + payload.from_employer.name + '   取消关注你了',
                            message:'[雇主简介:]   ' + payload.from_employer.introduction,
                            duration:0,
                            onClick: function ()  {
                                that.$router.push({
                                    name:'employer',
                                    params:{id: payload.from_employer.id}
                                })
                            }
                        })
                    } else {

                    }

                })
            },
            NewInviteEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewInvite',(payload) => {
                    that.$notify.info({
                        title: payload.type + '：' + payload.from.name +  '   邀请你一起兼职',
                        message:'[兼职内容:]   '  + payload.work.title,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: payload.work.id}
                            })
                        }
                    })
                })
            },
            NewReviewEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewReview',(payload) => {
                    that.$notify.info({
                        title:payload.employer.name +  '   评价了你',
                        message:'[评价内容:]   '  + payload.review.content,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: payload.work.id}
                            })
                        }
                    })
                })
            },
            NewReplyEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewReply',(payload) => {
                    that.$notify.info({
                        title:payload.employer.name +  '   对你的评价做出了回应',
                        message:'[评价内容：]   '  + payload.review.content +'   [回应内容：]   ' + payload.reply.content,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'work',
                                params:{id: payload.work.id}
                            })
                        }
                    })
                })
            },
            NewMessageEvent:function () {
                let that = this;
                this.$echo.channel('user.' + this.me.id).listen('NewMessage',(payload) => {
                    that.$store.commit('CHANGE_ACTIVE_USER',payload.from);
                    that.$store.commit('UPDATE_MESSAGES',payload.message);
                    that.$notify.info({
                        title: payload.from.name + '   给你发了新的信息',
                        message: '[信息内容:]   ' + payload.message.content,
                        duration: 5000,
                        onClick: function () {
                            that.chatDialogShow = true;
                        }
                    })
                    that.$store.state.user.un_read_messages_count += 1;
                })
            },
        },
        mounted:function () {
            console.log('userNotifications')
            this.ApplyPassedEvent();
            this.ApplyDeniedEvent();
            this.NewWorkEvent();
            this.NewAnswerEvent();
            this.NewFollowerEvent();
            this.LostFollowerEvent();
            this.NewInviteEvent();
            this.NewReviewEvent();
            this.NewReplyEvent();
            this.NewMessageEvent();
        }
    }
</script>