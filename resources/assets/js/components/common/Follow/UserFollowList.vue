<template>
    <div class="ListItem">
        <div class="ContentItem">
            <div class="ContentItem-image">
                <router-link to="/user/1" class="UserLink UserItem-avatar">
                    <UserPopover :user="user" pic-width="70"></UserPopover>
                </router-link>
            </div>
            <div class="ContentItem-head">
                <h2 class="ContentItem-title">
                    <UserPopover :user="user" :text="true"></UserPopover>
                </h2>
                <div class="ContentItem-meta">
                    <div class="ContentItem-stars">
                        <el-popover  placement="bottom"  trigger="hover">
                            <div class="DetailStars" slot="reference">
                                <span class="Star-title">综合评分&nbsp;:</span>
                                <el-rate v-model="total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">薪资待遇&nbsp;:</span>
                                <el-rate v-model="treat_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">描述相符&nbsp;:</span>
                                <el-rate v-model="description_match" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                            <div class="DetailStars">
                                <span class="Star-title">工资发放速度&nbsp;:</span>
                                <el-rate v-model="pay_speed" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                            </div>
                        </el-popover>
                    </div>
                    <div class="ContentItem-status">
                        <div class="ContentItem-statusItem">12&nbsp;兼职</div>
                        <div class="ContentItem-statusItem">500&nbsp;经验</div>
                        <div class="ContentItem-statusItem">20&nbsp;粉丝</div>
                    </div>
                </div>
            </div>
            <div class="ContentItem-action">
                <el-button class="FollowButton" type="primary"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注他</el-button>
            </div>
        </div>
    </div>
</template>

<script>
    import UserPopover from '../Popover/UserPopover.vue';
    export default {
        name:'UserFollowList',
//        props:['user'],
        components:{UserPopover},
        data() {
            return {
                user: JSON.parse(localStorage.user),
                treat_star: 4.2,
                pay_speed: 4,
                description_match: 4.6,
                total_star: 4.4
            }
        }
    }
</script>

<style scoped>
    .ListItem {
        position: relative;
        padding: 16px 20px;
    }
    .ContentItem {
        display: flex;
    }
    .ContentItem-image {
        float: left;
        margin-right: 16px;
    }
    .ContentItem-head {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
        margin-right: 6px;
        margin-top:8px;
    }
    .ContentItem-title {
        font-size: 18px;
        font-weight: 700;
        margin-left:20px;
        line-height: 1.6;
        color: #1e1e1e;
        margin-top: -4px;
        margin-bottom: -4px;
    }
    .ContentItem-meta {
        font-size: 15px;
        color: #555;
        margin-top:6px;
    }
    .ContentItem-status {
        margin-top: 5px;
        color: #8590a6;
        font-size: 14px;
        display: flex;
    }
    .ContentItem-statusItem {
        margin-left:20px;
    }
    .DetailStars {
        display: flex;
        margin-left:20px;
        margin-right:20px;
        margin-top: 5px;
        margin-bottom: 5px;
        width: 300px;
    }
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
        font-size:15px;
    }
    .FollowIcon {
        color: #ffffff;
    }
    .ContentItem-action {
        align-self: center;
    }
</style>