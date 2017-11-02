<template>
    <div class="container">
        <Navbar active-index="2"></Navbar>
        <div class="main">
            <el-col :span="16" class="left-panel">
                <GetWorks :query="query"></GetWorks>
            </el-col>
            <el-col :span="8" class="right-panel">
                <SideNav class="SideNav" v-if="me"></SideNav>
                <RecommendWorkCard v-if="me"></RecommendWorkCard>
            </el-col>
            <CornerButtons></CornerButtons>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import SideNav from '../../components/user/SideNav.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import RecommendWorkCard from '../../components/common/Card/RecommendWorkCard.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    import GetWorks from '../../components/common/GetWorks.vue';
    export default {
        name: 'Works',
        components: {Navbar,GetWorks,SideNav,CornerButtons,RecommendWorkCard},
        data() {
            return {
                name:  'chen',
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                query:this.$route.query
            }
        }
    }
</script>

<style scoped>
    .main {
        margin: 60px auto;
        width: 1210px;
    }
    .left-panel {
        margin-top:10px;
    }
    .right-panel {
        float: right;
        margin-top:10px;
        padding-left:10px;
    }
</style>