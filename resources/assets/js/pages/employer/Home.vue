<template>
    <div class="container">
        <TopNavbar></TopNavbar>
        <div class="main">
        <el-row>
            <el-col :span="3">
                <LeftNavbar></LeftNavbar>
            </el-col>
            <el-col :span="15">
            </el-col>
        </el-row>
        </div>
    </div>
</template>

<script>
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import LeftNavbar from '../../components/employer/LeftNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import {mapState} from 'vuex';
    export default {
        name:'home',
        components:{TopNavbar,LeftNavbar,NoticeBoard},
        data() {
            return {
                user: null,
                token: null
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
    .main {
        margin-top: 60px;
        background-color: #F5F5F6;
    }
</style>