<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <el-col :span="16" class="left-panel">
                <el-card class="NotificationsCard" :body-style="{padding:'0px 20px 0px 20px'}">
                    <div class="Card-title" slot="header">
                        <span>全部提醒</span>
                    </div>
                    <div class="Day-lists" v-if="Object.keys(notifications).length > 0">
                        <div class="Day-item" v-for="(dayNotification,key) in notifications">
                            <span class="Date">{{key}}</span>
                            <div class="Notifications-lists">
                                <UserNotificationList v-for="notification in dayNotification" :notification="notification"></UserNotificationList>
                            </div>
                        </div>
                    </div>
                    <div class="EmptyState" v-else>
                        <div class="EmptyState-inner">
                            <i class="fa fa-bell EmptyState-icon"></i>
                            <span>暂时还没有提醒</span>
                        </div>
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
    import UserNotificationList from '../../components/notifications/UserNotificationList.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import * as types from '../../store/mutation-types'
    import {mapState} from 'vuex'
    export default {
        name:'notifications',
        components:{Navbar,SideNav,UserNotificationList,CornerButtons},
        data() {
            return {
                notifications:[],
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token
        }),
        methods: {
            getNotifications:function () {
                let that = this;
                this.$axios.get('/api/user/notifications').then( (res) => {
                    if (res.data.status == 1) {
                        that.notifications = res.data.notifications;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            }
        },
        created:function () {
            this.getNotifications();
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
    .Day-lists {
        margin-top: 20px;
        margin-bottom:20px;
    }
    .Day-item {
        margin-top:10px;
        margin-bottom:10px;
    }
    .Date {
        font-weight: 700;
        font-size: 15px;
    }
    .Notifications-lists {
        margin-top:20px;
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
</style>
