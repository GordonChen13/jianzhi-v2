<template>
    <div class="container">
        <TopNavbar></TopNavbar>
        <LeftNavbar></LeftNavbar>
        <div class="main">
            <el-row>
                <el-col :span="18">
                    <div class="WorkLists" v-if="works.length > 0">
                        <WorkList v-for="work in works" :work="work" class="WorkList" action="review"></WorkList>
                    </div>
                    <el-card class="EmptyState" v-else>
                        <div class="EmptyState-inner">
                            <i class="fa fa-briefcase EmptyState-icon"></i>
                            <span>您暂时还没有等待评价的兼职</span>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="6">
                    <el-row>
                        <ToDos></ToDos>
                    </el-row>
                    <el-row>
                        <NoticeBoard></NoticeBoard>
                    </el-row>
                </el-col>
            </el-row>
        </div>
    </div>
</template>

<script>
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import LeftNavbar from '../../components/employer/LeftNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import ToDos from '../../components/employer/ToDos.vue';
    import WorkList from '../../components/employer/WorkList.vue';
    export default {
        name:'ApplicantManage',
        components: {TopNavbar,LeftNavbar,NoticeBoard,ToDos,WorkList},
        data() {
            return {
                works:[]
            }
        },
        methods: {
            getReviewingWorks:function () {
                let that = this;
                this.$axios.get('/api/employer/works?status=2',{
                    headers:{
                        'Authorization':'bearer' + localStorage.token
                    }
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status ==1) {
                            resolve(response.data);
                            that.works = response.data.works;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            }
        },
        created:function () {
            this.getReviewingWorks();
        }
    }
</script>

<style scoped>
    .main {
        margin-top: 60px;
        position: relative;
        margin-left: 200px;
        background-color: #F5F5F6;
    }
    .EmptyState {
        padding: 0px 0;
        margin-top:20px;
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
    .WorkList {
        margin-top: 20px;
        margin-left: 20px;
    }
</style>