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
                    <div class="FeedLists" v-if="feeds.length > 0">
                        <FeedItem class="FeedItem" v-for="feed in feeds" :feed="feed"></FeedItem>
                    </div>
                </el-card>
            </el-col>
            <el-col :span="8" class="right-panel">
                <SideNav class="SideNav"></SideNav>
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
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'Home',
        components:{Navbar,SideNav,FeedItem,CornerButtons},
        data() {
            return {
                feeds:[],
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {
            getFeeds:function () {
                let that = this;
                this.$axios.get('/api/user/feeds').then( (res) => {
                    if (res.data.status == 1) {
                        that.feeds = res.data.feeds;
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
</style>
