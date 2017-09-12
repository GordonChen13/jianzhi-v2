<template>
    <el-card>
        <div slot="header">来自 <span class="WorkTitle">{{work.title}}</span></div>
        <div class="Content">
            <div class="ApplicantLists" v-if="applicants.length > 0">
                <UserList v-for="applicant in applicants" :user="applicant"></UserList>
            </div>
            <div class="EmptyState" v-else>
                <div class="EmptyState-inner">
                    <i class="fa fa-users EmptyState-icon"></i>
                    <span>该兼职暂时还没有人申请</span>
                </div>
            </div>
        </div>
    </el-card>

</template>

<script>
    import UserList from '../common/Follow/UserFollowList.vue';
    export default {
        name:'WorkList',
        props:{
            work: {
                type:Object,
                required:true
            }
        },
        components:{UserList},
        data() {
            return {
                applicants:[]
            }
        },
        methods: {
            getApplicants:function () {
                let that = this;
                this.$axios.get('/api/')
            }
        }
    }
</script>

<style scoped>
    .WorkTitle {
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .EmptyState {
        padding: 0px 0;
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