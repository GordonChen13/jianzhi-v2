<template>
    <div class="container">
        <Navbar></Navbar>
        <div class="main">
            <el-col :span="15" class="left-panel">
                <GetWorks></GetWorks>
            </el-col>
            <el-col :span="8" class="right-panel">
                <SideNav></SideNav>
            </el-col>

        </div>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import SideNav from '../../components/user/SideNav.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    import GetWorks from '../../components/common/GetWorks.vue';
    export default {
        name: 'Works',
        components: {Navbar,GetWorks,SideNav},
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
        margin: 100px auto;
        width: 1210px;
    }
    .right-panel {
        float: right;
    }
</style>