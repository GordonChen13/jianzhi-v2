<template>
    <div class="container">
        <TopNavbar></TopNavbar>
        <div class="main">
        <el-row>
            <el-col :span="3">
                <RightNavbar></RightNavbar>
            </el-col>
            <el-col :span="15">
            </el-col>
        </el-row>
        </div>
    </div>
</template>

<script>
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import RightNavbar from '../../components/employer/RightNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import {mapState} from 'vuex';
    export default {
        name:'home',
        components:{TopNavbar,RightNavbar,NoticeBoard},
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