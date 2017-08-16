<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <el-col :span="16" class="left-panel">
                <FeedItem class="FeedItem"></FeedItem>
                <FeedItem class="FeedItem"></FeedItem>
                <FeedItem class="FeedItem"></FeedItem>
                <FeedItem class="FeedItem"></FeedItem>
                <FeedItem class="FeedItem"></FeedItem>
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
    import FeedItem from '../../components/user/FeedItem.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'Home',
        components:{Navbar,SideNav,FeedItem,CornerButtons},
        data() {
            return {

            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {

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
    .FeedItem {
        margin-bottom:10px;
    }
</style>
