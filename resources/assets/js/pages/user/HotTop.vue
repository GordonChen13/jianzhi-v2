<template>
    <div class="container">
        <div class="Navbar">
            <Navbar active-index="3"></Navbar>
        </div>
        <div class="Main">
            <el-col :span="16" class="left-panel">
                <el-card class="HotWorksCard" :body-style="{padding:'0px 20px 0px 20px'}">
                    <div class="Card-title" slot="header">
                        <span>本周热门</span>
                        <div class="Topbar-options">
                            <el-button class="button-plain is-active" v-if="activeButton === 'view'">浏览最多</el-button>
                            <el-button class="button-plain" @click="getWorks('view')" v-else>浏览最多</el-button>
                            <el-button class="button-plain is-active" v-if="activeButton === 'favorite'">收藏最多</el-button>
                            <el-button class="button-plain" @click="getWorks('favorite')" v-else>收藏最多</el-button>
                            <el-button class="button-plain is-active" v-if="activeButton === 'apply'">申请最多</el-button>
                            <el-button class="button-plain" @click="getWorks('apply')" v-else>申请最多</el-button>
                        </div>
                    </div>
                    <div class="WorkLists" v-if="worksOrderByView.length > 0 && activeButton === 'view'">
                        <WorkList class="WorkItem" v-for="work in worksOrderByView" :work="work"></WorkList>
                    </div>
                    <div class="WorkLists" v-else-if="worksOrderByFavorite.length > 0 && activeButton === 'favorite'">
                        <WorkList class="WorkItem" v-for="work in worksOrderByFavorite" :work="work"></WorkList>
                    </div>
                    <div class="WorkLists" v-else-if="worksOrderByApply.length > 0 && activeButton === 'apply'">
                        <WorkList class="WorkItem" v-for="work in worksOrderByApply" :work="work"></WorkList>
                    </div>
                    <div class="EmptyState" v-else>
                        <div class="EmptyState-inner">
                            <i class="fa fa-briefcase EmptyState-icon"></i>
                            <span>暂时还没热门兼职</span>
                        </div>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="8" class="right-panel">
                <SideNav class="SideNav"></SideNav>
                <RecommendWorkCard></RecommendWorkCard>
            </el-col>
        </div>
        <CornerButtons></CornerButtons>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import SideNav from '../../components/user/SideNav.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import WorkList from '../../components/common/WorkList.vue';
    import RecommendWorkCard from '../../components/common/Card/RecommendWorkCard.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'Home',
        components:{Navbar,SideNav,WorkList,CornerButtons,RecommendWorkCard},
        data() {
            return {
                worksOrderByView:[],
                worksOrderByFavorite:[],
                worksOrderByApply:[],
                activeButton:'view'
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {
            getWorks:function (orderBy) {
                this.activeButton = orderBy;
                let that = this;
                if (orderBy == 'view' && this.worksOrderByView.length == 0) {
                    this.$axios.get('/api/works?hot=true&order=' + orderBy).then( (res) => {
                        if (res.data.status == 1) {
                            that.worksOrderByView = res.data.works.data;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    })
                } else if (orderBy == 'favorite' && this.worksOrderByFavorite.length == 0) {
                    this.$axios.get('/api/works?hot=true&order=' + orderBy).then( (res) => {
                        if (res.data.status == 1) {
                            that.worksOrderByFavorite = res.data.works.data;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    })
                } else  if (orderBy == 'apply' && this.worksOrderByApply.length == 0) {
                    this.$axios.get('/api/works?hot=true&order=' + orderBy).then((res) => {
                        if (res.data.status == 1) {
                            that.worksOrderByApply = res.data.works.data;
                        } else {
                            that.$message.error(res.data.msg);
                        }
                    })
                }
            }
        },
        created:function () {
            this.getWorks('view');
        }

    }
</script>

<style scoped>
    .Main {
        margin:60px auto;
        width: 1210px;
        height:100%;
    }
    .left-panel {
        margin-top:10px;
    }
    .right-panel {
        float: right;
        margin-top:10px;
        padding-left:10px;
    }
    .Card-title {
        font-weight: 700;
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
    .WorkItem {
        border:none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    .WorkItem:first-child {
        margin-top:10px;
    }
    .WorkItem:not(:last-child) {
        border-bottom: 1px solid #f0f2f7;
    }
    .Topbar-options {
        float: right;
        margin-right:20px;
    }
</style>
