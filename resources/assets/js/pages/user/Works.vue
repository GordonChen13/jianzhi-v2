<template>
    <div class="container">
        <Navbar></Navbar>
        <div class="main">
            <el-col :span="16" class="left-panel">
                <GetWorks></GetWorks>
            </el-col>
            <el-col :span="8" class="right-panel">
                <SideNav class="SideNav"></SideNav>
            </el-col>
            <CornerButtons></CornerButtons>
        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import SideNav from '../../components/user/SideNav.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    import GetWorks from '../../components/common/GetWorks.vue';
    export default {
        name: 'Works',
        components: {Navbar,GetWorks,SideNav,CornerButtons},
        data() {
            return {
                name:  'chen'
            }
        },
        mounted: function () {
            let params = this.$store.state.params;
            this.$store.dispatch('GetWorks',params);
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