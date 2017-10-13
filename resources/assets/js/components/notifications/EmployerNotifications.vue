<template>

</template>

<script>
    export default {
        name:'EmployerNotifications',
        data(){
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
            }
        },
        methods:{
            NewQuestionEvent:function () {
                let that = this;
                this.$echo.channel('employer.' + this.me.id).listen('NewQuestion',(payload) => {
                    that.$notify.success({
                        title: payload.user.name + '   对兼职   ' + payload.work.title + '   提出了咨询',
                        message:'[咨询内容:]   ' + payload.question.content,
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
                this.$echo.channel('employer.' + this.me.id).listen('NewFollower',(payload) => {
                    that.$notify.success({
                        title: payload.from_user.name + '   关注了你',
                        message:'[粉丝简介:]   ' + payload.from_user.introduction,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'user',
                                params:{id: payload.from_user.id}
                            })
                        }
                    })
                })
            },
            LostFollowerEvent:function () {
                let that = this;
                this.$echo.channel('employer.' + this.me.id).listen('LostFollower',(payload) => {
                    that.$notify.warning({
                        title: payload.from_user.name + '   取消关注你了',
                        message:'[粉丝简介:]   ' + payload.from_user.introduction,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'user',
                                params:{id: payload.from_user.id}
                            })
                        }
                    })
                })
            },
            NewApplyEvent:function () {
                let that = this;
                this.$echo.channel('employer.' + this.me.id).listen('NewApply',(payload) => {
                    that.$notify.info({
                        title: payload.user.name + '   申请了兼职：   ' + payload.work.title,
                        message:'[申请人简介:]   ' + payload.user.introduction,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'user',
                                params:{id: payload.user.id}
                            })
                        }
                    })
                })
            },
            NewUnApplyEvent:function () {
                let that = this;
                this.$echo.channel('employer.' + this.me.id).listen('NewUnApply',(payload) => {
                    that.$notify.warning({
                        title: payload.user.name + '   取消了兼职申请：   ' + payload.work.title,
                        message:'[申请人简介:]   ' + payload.user.introduction,
                        duration:0,
                        onClick: () => {
                            that.$router.push({
                                name:'user',
                                params:{id: payload.user.id}
                            })
                        }
                    })
                })
            },
            NewReviewEvent:function () {
                let that = this;
                this.$echo.channel('employer.' + this.me.id).listen('NewReview',(payload) => {
                    that.$notify.info({
                        title:payload.user.name +  '   评价了你的兼职  ' + payload.work.title,
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
                this.$echo.channel('employer.' + this.me.id).listen('NewReply',(payload) => {
                    that.$notify.info({
                        title:payload.user.name +  '   对你的评价做出了回应',
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
        },
        mounted:function () {
            this.NewQuestionEvent();
            this.NewFollowerEvent();
            this.LostFollowerEvent();
            this.NewApplyEvent();
            this.NewUnApplyEvent();
            this.NewReviewEvent();
            this.NewReplyEvent();
        }
    }
</script>