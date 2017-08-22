<template>
    <div class="ReviewList-main">
        <el-col :span="3" class="Review-avatar">
            <UserPopover :user="user" :pic-width="50"></UserPopover>
        </el-col>
        <el-col :span="21" class="Review-right">
            <div class="ReviewHead">
                <div class="ReviewHead-name">
                    <UserPopover :user="user" :text="true"></UserPopover>
                </div>
                <div class="ReviewStars">
                    <el-popover  placement="bottom"  trigger="hover">
                        <div class="DetailStars" slot="reference">
                            <span class="Star-title Star-title-all">综合评分&nbsp;:</span>
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
                <div class="WorkInfo">
                    <span class="Work-label">来自兼职&nbsp;:</span>
                    <router-link to="/work/1">
                        <span class="Work-title">小学六年级语文家教</span>
                    </router-link>
                </div>
            </div>
            <div class="ReviewTags">
                <el-tag type="gray" class="Tag-item">涨姿势了</el-tag>
                <el-tag type="gray" class="Tag-item">同事很好人</el-tag>
                <el-tag type="gray" class="Tag-item">待遇超级棒</el-tag>
                <span class="date">2017-8-16</span>
            </div>
            <div class="ReviewContent">
                <div class="ReviewType">[认真评价]</div>
                <div class="ReviewDetail">家长人很体贴，对人很好。小孩子也很聪明，学得很快。
                    热心的妈妈还做了我的饭，非要我留下来吃。很开心的一次家教经历。另外，工资是直接微信转账的，就是喜欢这种速度，哈哈哈
                </div>
                <div class="ReviewAction">
                    <span class="Replied">企业回复</span>
                    <a href="javascript:;" class="Up-thumbs">
                        <el-button type="text"><i class="fa fa-thumbs-up-o"></i>有用<span class="Up-number">(&nbsp;2&nbsp;)</span></el-button>
                    </a>
                </div>
                <div class="ReviewReply">
                    <div class="TriangleOut"></div>
                    <div class="TriangleIn"></div>
                    <div class="ReplyContent">
                        <el-col :span="4" class="Review-avatar">
                            <img  :src="'/images/users/avatar/' + user.pic_path" width="50px" alt="图片被外星人劫持啦！">
                        </el-col>
                        <el-col :span="20" class="Review-right">
                            <div class="ReplyHead">
                                <span class="Employer-name">{{user.name}}</span>
                                <span class="date">2017-8-18</span>
                            </div>
                            <div class="ReplyDetail">非常感谢你的赞美，下次有机会我们继续合作。在你的教导下，
                                我女儿的成绩有了很大的进步。如果我身边有人要找家教我也会向他们推荐你的哦。</div>
                        </el-col>
                    </div>
                </div>
            </div>
        </el-col>
    </div>
</template>

<script>
    import UserPopover from '../common/Popover/UserPopover.vue';
    export default {
        name: 'ReviewList',
        components: {UserPopover},
        data() {
            return {
                user: JSON.parse(localStorage.user),
                total_star: 4.8,
                treat_star: 4.6,
                description_match: 5,
                pay_speed: 4.8
            }
        },
        created: function () {

        }
    }
</script>

<style scoped>
    .ReviewList-main {
        width: 100%;
        height: 280px;
        margin-left:20px;
        margin-top: 20px;
    }
    .Review-avatar {
        width: 60px;
    }
    .ReviewHead {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
    }
    .Review-right {
        float: left;
    }
    .ReviewStars {
        margin-left:10px;
    }
    .DetailStars {
        display: flex;
        margin-left:20px;
        margin-right:20px;
        margin-bottom:10px;
    }
    .Star-title {
        float: right;
        width: 100px;
        color: #999;
        font-size:15px;
    }
    .Star-title-all {
        width:70px;
    }
    .WorkInfo {
        color: #999;
        width: 300px;
        overflow: hidden;
        text-overflow:ellipsis;
        white-space: nowrap;
    }
    .Work-title {
        color: black;
        font-weight: 500;
    }
    .Work-title:hover {
        color: #175199;
    }
    .ReviewTags {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
    }
    .Tag-item {
        padding:0px 10px;
        margin-left:10px;
    }
    .date {
        margin-left:auto;
        color: #999;;
        align-self: center;
    }
    .ReviewContent {
        margin-bottom:10px;
        margin-top: 10px;
    }
    .ReviewType {
        display: inline-block;
        color: #999;
    }
    .ReviewDetail {
        margin-top:10px;
        display:inline;
    }
    .Replied {
        padding-right: 10px;
        border-right: 1px solid #dce4e6;
        color: #999;
    }
    .Up-thumbs {
        margin-left:10px;
    }
    .Employer-name {
        margin-bottom: 6px;
        color: #999;
    }
    .ReviewReply {
        position: relative;
    }
    .TriangleOut {
        position: absolute;
        top: -10px;
        left: 16px;
        width: 0;
        height: 0;
        border-right: 10px solid transparent;
        border-bottom: 10px solid #dce4e6;
    }
    .TriangleIn {
        position: absolute;
        top: -8px;
        left: 17px;
        width: 0;
        height: 0;
        border-right: 9px solid transparent;
        border-bottom: 9px solid #fafafa;
    }
    .ReplyContent {
        padding: 14px 10px 20px;
        margin-top: 10px;
        border: 1px solid #dce4e6;
        border-radius: 3px;
        background-color: #fafafa;
        overflow: hidden;
    }
    .ReplyHead {
        display: -webkit-box;
        display:-ms-flexbox;
        display: flex;
        width:550px;
    }
</style>