<template>
    <el-card class="RecommendCard">
        <span class="Card-header" slot="header">猜你喜欢</span>
        <div class="WorkLists">
            <WorkList v-for="work in works" :work="work" size="tiny"></WorkList>
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
</style>