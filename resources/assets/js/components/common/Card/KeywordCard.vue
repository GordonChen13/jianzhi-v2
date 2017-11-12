<template>
    <el-card class="Card-container">
        <div slot="header">
            <span class="Achievement-Title">标签印象</span>
        </div>
        <div class="TagLists" v-if="Object.keys(keywords).length > 0">
            <div class="TagItem" v-for="(value,key) in keywords">
                <el-tag type="gray" class="Tag-word">{{key}} <span class="Tag-number">{{value}}</span></el-tag>
            </div>
        </div>
        <div class="EmptyTags" v-else>
            <el-tag type="gray">该用户暂时还没有标签</el-tag>
        </div>
    </el-card>
</template>

<script>
    export default {
        name:'KeywordCard',
        props:{
            role:{
                type:String,
                default:'user'
            },
            user:{
                type:Object,
            },
            employer:{
                type:Object
            }
        },
        data() {
            return {
                keywords:{}
            }
        },
        methods: {
            getUserTags:function () {
                let that = this;
                this.$axios.get('/api/user/review/keywords?user_id=' + this.user.id).then(function (res) {
                    if (res.data.status == 1) {
                       that.keywords = res.data.keywords;
                        console.log(that.keywords)
                    } else {
                        that.$message.error('获取用户关键词印象时出错');
                    }
                }).catch( (err) => {
                    console.log('getUserTagsError',err);
                })
            },
            getEmployerTags:function () {
                let that = this;
                this.$axios.get('/api/employer/review/keywords?employer_id=' + this.employer.id).then(function (res) {
                    if (res.data.status == 1) {
                        that.keywords = res.data.keywords;
                    } else {
                        that.$message.error('获取用户关键词印象时出错');
                    }
                }).catch( (err) => {
                    console.log('getUserTagsError',err);
                })
            }
        },
        mounted:function () {
            if (this.role == 'user') {
                this.getUserTags();
            } else {
                this.getEmployerTags();
            }
        }
    }
</script>

<style scoped>
    .Card-container {
        min-height: 200px;
    }
    .TagLists {
        display:flex;
        flex-wrap:wrap;
    }
    .TagItem {
        margin-right: 20px;
        margin-bottom: 20px;
    }
    .Tag-word {
        font-size: 14px;
    }
    .Achievement-Title {
        overflow: hidden;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
</style>