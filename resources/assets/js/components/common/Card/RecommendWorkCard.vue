<template>
    <el-card class="RecommendCard">
        <span class="Card-header" slot="header">猜你喜欢</span>
        <div class="WorkLists" v-if="works.length > 0">
            <WorkList v-for="work in works" :work="work" size="tiny"></WorkList>
        </div>
        <div class="EmptyState" v-else>
            <div class="EmptyState-inner">
                <i class="fa fa-briefcase EmptyState-icon"></i>
                <span>暂无推荐</span>
            </div>
        </div>
    </el-card>
</template>

<script>
    import WorkList from '../WorkList.vue';
    export default {
        name:'RecommendWorkCard',
        components:{WorkList},
        data() {
            return {
                user: localStorage.user ? JSON.parse(localStorage.user) : null,
                works:[]
            }
        },
        methods:{
            getRecommendWorks:function () {
                let that = this;
                this.$axios.get('/api/works?recommend=true').then( function (res) {
                    if (res.data.status == 1)  {
                        that.works = res.data.works
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            }
        },
        created:function () {
            this.getRecommendWorks();
        }
    }
</script>

<style scoped>
    .Card-header {
        font-weight: 700;
    }
    .WorkLists {
        max-height: 600px;
        overflow-y: scroll;
    }
    .EmptyState {
        padding: 70px 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        height: 30%;
    }
    .EmptyState-inner {
        font-size: 15px;
        color: #8590a6;
        text-align: center;
    }
    .EmptyState-icon {
        display: block;
        margin: 0 auto;
        font-size: 80px;
        padding-bottom:30px;
        color: rgba(133, 144, 166, 0.3);
    }
</style>