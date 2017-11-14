<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <el-col :span="16" class="left-panel">
                <el-card class="FeedsCard" :body-style="{padding:'0px 20px 0px 20px'}">
                    <div class="Card-title" slot="header">
                        <span>最新动态</span>
                    </div>
                    <div class="FeedLists" v-if="feeds.data.length > 0">
                        <FeedItem class="FeedItem" v-for="feed in feeds.data" :feed="feed"></FeedItem>
                        <el-button type="primary" class="LongButton" @click="loadMoreFeeds" v-if="!feeds.isEnded">加载更多</el-button>
                        <el-button type="primary" :disabled="true" class="LongButton" v-else>已全部加载完</el-button>
                    </div>
                    <div class="EmptyState" v-else>
                        <div class="EmptyState-inner">
                            <i class="fa fa-rss-square EmptyState-icon"></i>
                            <span>暂时还没新的动态，快去关注一些好友吧</span>
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
    import FeedItem from '../../components/common/FeedItem.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import RecommendWorkCard from '../../components/common/Card/RecommendWorkCard.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'Home',
        components:{Navbar,SideNav,FeedItem,CornerButtons,RecommendWorkCard},
        data() {
            return {
                feeds:{
                    current_page:1,
                    data:[],
                    isEnded:false,
                },
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {
            getFeeds:function () {
                let that = this;
                this.$axios.get('/api/user/feeds?page=' + this.feeds.current_page).then( (res) => {
                    if (res.data.status == 1) {
                        that.feeds.data = res.data.feeds;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            loadMoreFeeds:function () {
                let that = this;
                let page = this.feeds.current_page + 1;
                this.$axios.get('/api/user/feeds?page=' + page).then( (res) => {
                    if (res.data.status == 1) {
                        that.feeds.data.push(...res.data.feeds);
                        that.feeds.current_page += 1;
                        if (res.data.feeds.length == 0) {
                            that.feeds.isEnded = true;
                        }
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            }
        },
        created:function () {
            this.getFeeds();
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
    .FeedLists {
        display: flex;
        flex-direction: column;
        margin-bottom: 20px;
    }
</style>
