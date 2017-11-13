<template>
    <el-card class="FeedCard" :body-style="bodyStyle">
        <div class="FeedMain" v-if="feed.type == 'ew1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="{name:'employerProfile',params:{id:feed.employer.id}}" v-if="showName">{{feed.employer.name}}</router-link>
                        发布了兼职
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent">
                    <WorkList :work = feed.work class="FeedItem"  :body-style="{ padding: '10px 0px'}" :time-show="false"></WorkList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'ew2'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/employer/' + feed.employer.id" v-if="showName">{{feed.employer.name}}</router-link>
                        分享了兼职
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent">
                    <WorkList :work = feed.work class="FeedItem"  :body-style="{ padding: '10px 0px'}" :time-show="false"></WorkList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'eu1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/employer/' + feed.employer.id" v-if="showName">{{feed.employer.name}}</router-link>
                        关注了用户
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <UserList :user = feed.user class="FeedItem"></UserList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'ea1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/employer/' + feed.employer.id" v-if="showName">{{feed.employer.name}}</router-link>
                        回复了咨询
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent">
                    <QuestionList :question="feed.question" :work="feed.work" :show-time="false" :show-title="true" class="FeedQuestionList"></QuestionList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'er1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/employer/' + feed.employer.id" v-if="showName">{{feed.employer.name}}</router-link>
                        对兼职做出了评价
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <UserReviewList :review="feed.employerReview"></UserReviewList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'er2'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/employer/' + feed.employer.id" v-if="showName">{{feed.employer.name}}</router-link>
                        对兼职的评价做出了回复
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <UserReviewList :review="feed.userReview"></UserReviewList>
                </div>
            </div>
        </div>

        <div class="FeedMain" v-else-if="feed.type == 'ue1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        关注了雇主
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <EmployerList :employer = feed.employer class="FeedItem"></EmployerList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-if="feed.type == 'uw1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="{name:'user',params:{id:feed.user.id}}" v-if="showName">{{feed.user.name}}</router-link>
                        申请了兼职
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent">
                    <WorkList :work = feed.work class="FeedItem"  :body-style="{ padding: '10px 0px'}" :time-show="false"></WorkList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'uw2'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        收藏了兼职
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent">
                    <WorkList :work = feed.work class="FeedItem"  :body-style="{ padding: '10px 0px'}" :time-show="false"></WorkList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'uw3'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        分享了兼职
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent">
                    <WorkList :work = feed.work class="FeedItem"  :body-style="{ padding: '10px 0px'}" :time-show="false"></WorkList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'uu1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        关注了用户
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <UserList :user = feed.followingUser class="FeedItem"></UserList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'uq1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        提出了咨询
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent" style="margin-top: 20px">
                    <QuestionList :question="feed.question" :work="feed.work" :show-time="false" :show-title="true" class="FeedQuestionList"></QuestionList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'ur1'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        对兼职做出了评价
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <EmployerReviewList :review="feed.userReview"></EmployerReviewList>
                </div>
            </div>
        </div>
        <div class="FeedMain" v-else-if="feed.type == 'ur2'">
            <div class="Feed-title">
                <div class="Feed-meta">
                    <span class="Feed-meta-item">
                        <router-link :to="'/user/' + feed.user.id" v-if="showName">{{feed.user.name}}</router-link>
                        对得到的评价做出了回复
                    </span>
                    <span class="Feed-time">{{fromNow(feed.created_at)}}</span>
                </div>
                <div class="FeedContent GoLeft">
                    <EmployerReviewList :review="feed.employerReview"></EmployerReviewList>
                </div>
            </div>
        </div>
    </el-card>
</template>

<script>
    import {dateFromNow} from '../../util/format';
    import EmployerPopover from './Popover/EmployerPopover.vue';
    import UserList from './Follow/UserFollowList.vue';
    import EmployerList from './Follow/EmployerFollowList.vue';
    import WorkList from './WorkList.vue';
    import QuestionList from './QuestionList.vue';
    import EmployerReviewList from '../employer/ReviewList.vue';
    import UserReviewList from '../user/ReviewList.vue';

    export default {
        name:'FeedItem',
        components:{EmployerPopover,WorkList,UserList,EmployerList,QuestionList,EmployerReviewList,UserReviewList},
        props: {
            feed: {
                type: Object,
                required: true
            },
            bodyStyle: {
                type: Object,
                default: function () {
                    return { padding: '20px' }
                }
            },
            from: {
                type:Object,
            },
            showName: {
                default:true
            }
        },
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user):null,
                user: localStorage.user ? JSON.parse(localStorage.user) : null,
                show: false,
                treat_star: 4.2,
                pay_speed: 4,
                description_match: 4.6,
                total_star: 4.4
            }
        },
        methods: {
            fromNow: function (date) {
                return dateFromNow(date);
            }
        }
    }
</script>

<style scoped>
    .FeedCard {
        margin-bottom: 10px;
        border:none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
    }
    .FeedMain {
        display: block;
        margin-bottom: -30px;
        margin-top: -10px;
    }
    .Feed-title {
        margin-bottom:14px;
    }
    .Feed-meta {
        color: #8590a6;
        line-height: 1;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
    }
    .Feed-meta-item {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
    }
    .Feed-time {

    }
    .FeedContent {
        display: flex;
    }
    .FeedQuestionList {
        margin-bottom:10px;
        width:100%;
    }
    .FeedItem {
        border:none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        width: 100%;
    }
    .FeedItem:first-child {
        margin-top:10px;
    }
    .FeedItem:not(:last-child) {
        border-bottom: 1px solid #f0f2f7;
    }
    .GoLeft {
        margin-left: -20px;
    }
</style>