<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <div class="ProfileHeader" id="ProfileHeader">
                <el-card class="HeaderCard">
                    <div class="ProfileHeader-userCover">
                        <div class="UserCover">
                            <img class="UserCover-image" width="1210" src="https://pic3.zhimg.com/80/v2-c2b1832b468de8f73ee504ee35d4139e_r.jpg" alt="用户封面">
                        </div>
                    </div>
                    <div class="ProfileHeader-wrapper">
                        <div class="ProfileHeader-main">
                            <div class="ProfileHeader-avatar">
                                <img :src="'/images/users/avatar/' + employer.pic_path" alt="用户头像">
                            </div>
                            <div class="ProfileHeader-content">
                                <div class="ProfileHeader-contentHead">
                                    {{employer.name}}
                                </div>
                                <div class="ProfileHeader-contentBody">

                                </div>
                                <div class="ProfileHeader-contentFooter">

                                </div>
                            </div>
                        </div>

                    </div>

                </el-card>
            </div>
            <el-col :span="16" class="left-panel">

            </el-col>
            <el-col :span="8" class="right-panel">

            </el-col>
        </div>
        <CornerButtons></CornerButtons>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import axios from 'axios';
    export default {
        name:'Profile',
        components:{Navbar,CornerButtons},
        data() {
            return {
                employer: null
            }
        },
        created: function () {
            let me = this;
            axios.get('/api/employers/'+ this.$route.params.id).then(function (response) {
                console.log(response.data);
                me.employer = response.data.employer;
            });
        }
    }
</script>

<style scoped>
    .Main {
        margin:71px auto;
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
    .HeaderCard {
        margin-top:10px;
    }
    .ProfileHeader {
        margin-top:10px;
        position: relative;
        width: 100%;
    }
    .ProfileHeader-userCover {
        margin:-20px -20px 0 -20px;
    }
</style>