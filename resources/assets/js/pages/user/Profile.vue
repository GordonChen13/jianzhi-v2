<template>
    <div class="container">
        <div class="Navbar">
            <Navbar active-index="8"></Navbar>
        </div>
        <div class="Main" v-if="user">
            <div class="ProfileHeader" id="ProfileHeader">
                <el-card class="HeaderCard">
                    <div class="ProfileHeader-userCover">
                        <div class="UserCoverEditor">
                            <el-upload action="/api/photos/cover" :show-file-list="false"  name="cover" accept=".jpg,.png"
                                       :on-success="handleCoverSuccess" :before-upload="beforeCoverUpload" :headers="uploadConfig.headers">
                                <el-button class="Cover-button"><i class="fa fa-pencil fa-fw"></i>更换封面图片</el-button>
                            </el-upload>
                        </div>
                        <div class="UserCover">
                            <img class="UserCover-image" width="1210" :src="'/storage/' + user.cover_path" alt="用户封面">
                        </div>
                    </div>
                    <div class="ProfileHeader-wrapper">
                        <div class="ProfileHeader-main">
                            <div class="ProfileHeader-avatar">
                                <img :src="'/storage/' + user.pic_path" alt="用户头像" width="160px" height="160px">
                                <div class="EmployerAvatar-mask">
                                    <div class="Mask-mask MaskInner">
                                        <el-upload class="avatar-uploader" action="/api/photos/avatar" :show-file-list="false"
                                                   :on-success="uploadAvatarSuccess" :before-upload="beforeAvatarUpload"
                                                   :headers="uploadConfig.headers" name="avatar" accept=".jpg,.png">
                                            <img v-if="avatarUrl" :src="user.pic_path" class="avatar">
                                            <div class="MaskContent" v-else>
                                                <i class="fa fa-camera avatar-uploader-icon"></i>
                                            </div>
                                        </el-upload>
                                    </div>
                                </div>
                            </div>
                            <div class="ProfileHeader-content">
                                <div class="ProfileHeader-contentHead">
                                    <h1 class="ProfileHeader-title">
                                        <span class="ProfileHeader-name">{{user.name}}</span>
                                        <span class="ProfileHeader-intro">{{user.simple_intro}}</span>
                                    </h1>
                                </div>
                                <div class="ProfileHeader-contentBody">
                                    <div class="ProfileHeader-info" v-if="!detailShow">
                                        <div class="ProfileHeader-infoItem">
                                            <div class="ProfileHeader-iconWrapper">
                                                <i class="fa fa-map-marker"></i>
                                            </div>
                                            {{user.city}}
                                            <div class="ProfileHeader-divider"></div>
                                            <div class="ProfileHeader-iconWrapper" v-if="user.gender == '男' ">
                                                <i class="fa fa-mars"></i>
                                            </div>
                                            <div class="ProfileHeader-iconWrapper" v-else>
                                                <i class="fa fa-venus"></i>
                                            </div>
                                        </div>
                                        <div class="ProfileHeader-infoItem">
                                            <div class="ProfileHeader-iconWrapper">
                                                <i class="fa fa-graduation-cap"></i>
                                            </div>
                                            {{user.school}}
                                            <div class="ProfileHeader-divider"></div>
                                            {{user.major}}
                                        </div>
                                    </div>
                                    <div class="ProfileHeader-detail" v-else>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">居住地</span>
                                            <div class="ProfileHeader-detailValue"><span>现居{{user.city}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">年龄</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.age}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">邮箱</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.email}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">教育经历</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.school}}--{{user.major}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">个人介绍</span>
                                            <div class="ProfileHeader-detailValue"><span>{{user.introduction}}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="ProfileHeader-contentFooter">
                                    <el-button class="ExpandButton" @click="detailShow = !detailShow" v-if="!detailShow">
                                        <div class="ProfileHeader-iconWrapper"><i class="fa fa-arrow-down"></i></div>查看详细资料
                                    </el-button>
                                    <el-button class="ExpandButton" @click="detailShow = !detailShow" v-else>
                                        <div class="ProfileHeader-iconWrapper"><i class="fa fa-arrow-up"></i></div>收起详细资料
                                    </el-button>
                                    <router-link :to="'/user/' + user.id + '/edit'" v-if="me !== null && me.id == user.id">
                                        <el-button class="EditButton ProfileButtonGroup" type="primary">
                                            修改个人资料
                                        </el-button>
                                    </router-link>
                                    <div class="ProfileButtonGroup" v-else-if="user.gender == '男' ">
                                        <el-button class="FollowButton" type="primary" @click="followUser" v-if="!isFollowing"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注他</el-button>
                                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowUser"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;取消关注</el-button>
                                        <el-button class="ChatButton" :disabled="cantChat" @click="chatDialogShow = !chatDialogShow"><i class="fa fa-comments fa-fw"></i>&nbsp;&nbsp;发私信</el-button>
                                    </div>
                                    <div class="ProfileButtonGroup" v-else>
                                        <el-button class="FollowButton" type="primary" @click="followUser" v-if="!isFollowing"><i class="fa fa-plus FollowIcon"></i>&nbsp;&nbsp;关注她</el-button>
                                        <el-button class="FollowButton" type="danger"  v-else @click="unFollowUser"><i class="fa fa-user-times FollowIcon"></i>&nbsp;&nbsp;取消关注</el-button>
                                        <el-button class="ChatButton"  :disabled="cantChat" @click="chatDialogShow = !chatDialogShow"><i class="fa fa-comments fa-fw"></i>&nbsp;&nbsp;发私信</el-button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-card>
            </div>
            <div class="Profile-main">
                <el-col :span="16" class="left-panel">
                    <el-card class="ProfileCard">
                        <div class="MainHeader">
                            <el-tabs v-model="activeName"  class="ProfileTabs" @tab-click="handleTagChange">
                                <el-tab-pane name="activities" id="Activities" ref="Activities">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/activities'">
                                            <i class="fa fa-rss-square fa-fw"></i>&nbsp;动态
                                        </router-link>
                                    </span>
                                    <div class="ProfileActivities">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && user.id == me.id">我的动态</h4>
                                            <h4 class="ListHeader-text" v-else-if="user.gender == '男'">他的动态</h4>
                                            <h4 class="ListHeader-text" v-else>她的动态</h4>
                                        </div>
                                        <div class="ListContent">
                                            <div class="FeedItems ListItems" v-if="feeds.data.length !== 0 ">
                                                <FeedItem class="FeedItem" v-for="feed in feeds.data" :body-style="{ padding: '10px 10px 0px'}" :from="user" :feed="feed" :show-name="false"></FeedItem>
                                                <el-button type="primary" @click="loadMoreFeeds" v-if="feeds.next_page_url">加载更多</el-button>
                                                <el-button type="primary" :disabled="true" v-else>已全部加载完</el-button>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-rss-square EmptyState-icon"></i>
                                                    <span>暂时还没有动态</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="works" id="Works" ref="MyWorks" v-if="me && me.id == user.id">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/works'">
                                            <i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职<span class="Tag-meta">{{user.works_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileWorks">
                                        <div class="ListHeader">
                                            <div class="SubTabs">
                                                <div class="SubTabs-item Active-item" v-if="workType == 'applying'">
                                                    <span class="SubTab-text">申请中</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('applying')">申请中</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="workType == 'interviewing'">
                                                    <span class="SubTab-text">待面试</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('interviewing')">待面试</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="workType == 'rejected'">
                                                    <span class="SubTab-text">申请未通过</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('rejected')">申请未通过</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="workType == 'interviewFailed'">
                                                    <span class="SubTab-text">面试未通过</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('interviewFailed')">面试未通过</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="workType == 'working'">
                                                    <span class="SubTab-text">进行中</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('working')">进行中</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="workType == 'reviewing'">
                                                    <span class="SubTab-text">待评价</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('reviewing')">待评价</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="workType == 'finished'">
                                                    <span class="SubTab-text">已结束</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text"  @click="getUserWorks('finished')">已结束</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'applying'">
                                            <div class="WorkLists" v-if="applyingWorks.length !== 0 ">
                                                <WorkList v-for="work in applyingWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有正在申请的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'interviewing'">
                                            <div class="WorkLists" v-if="interviewingWorks.length !== 0 ">
                                                <WorkList v-for="work in interviewingWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有需要面试的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'rejected'">
                                            <div class="WorkLists" v-if="rejectedWorks.length !== 0 ">
                                                <WorkList v-for="work in rejectedWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有申请没通过的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'interviewFailed'">
                                            <div class="WorkLists" v-if="interviewFailedWorks.length !== 0 ">
                                                <WorkList v-for="work in interviewFailedWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有面试未通过的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'working'">
                                            <div class="WorkLists" v-if="workingWorks.length !== 0 ">
                                                <WorkList v-for="work in workingWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有正在进行的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'reviewing'">
                                            <div class="WorkLists" v-if="reviewingWorks.length !== 0 ">
                                                <WorkList v-for="work in reviewingWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有等待评价的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'finished'">
                                            <div class="WorkLists" v-if="finishedWorks.length !== 0 ">
                                                <WorkList v-for="work in finishedWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有已结束的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="works" id="Works" ref="OtherWorks" v-else>
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/works'">
                                            <i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职<span class="Tag-meta">{{user.passed_works_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileWorks">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="user.gender == '男'">他做过的兼职</h4>
                                            <h4 class="ListHeader-text" v-else>她做过的兼职</h4>
                                        </div>
                                        <div class="ListContent" v-if="workType == 'passed'">
                                            <div class="WorkLists" v-if="passedWorks.length !== 0 ">
                                                <WorkList v-for="work in passedWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>该用户暂时还没有做过的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="reviews" id="Reviews" ref="Reviews">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/reviews'">
                                            <i class="fa fa-star-half-o fa-fw"></i>&nbsp;评价<span class="Tag-meta">{{user.reviews_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileReviews">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && user.id == me.id">我得到的评价</h4>
                                            <h4 class="ListHeader-text" v-else-if="user.gender == '男'">他得到的评价</h4>
                                            <h4 class="ListHeader-text" v-else>她得到的评价</h4>
                                            <div class="ListHeader-options">
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '全部'">全部 <span class="Button-meta">{{reviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getUserReviews('全部')">全部<span class="Button-meta">{{reviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '好评'">好评<span class="Button-meta">{{goodReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getUserReviews('好评')">好评<span class="Button-meta">{{goodReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '中评'" >中评<span class="Button-meta">{{centerReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getUserReviews('中评')">中评<span class="Button-meta">{{centerReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '差评'" >差评<span class="Button-meta">{{badReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getUserReviews('差评')">差评<span class="Button-meta">{{badReviews.length}}</span></el-button>
                                            </div>
                                        </div>
                                        <div class="ListContent">
                                            <div class="ReviewState" v-if="reviews.data.length !== 0 ">
                                                <div class="ReviewTop">
                                                    <div class="Review-comprehensive-all">
                                                        <span class="Star-title-all" style="color:inherit;">综合评分&nbsp;:</span>
                                                        <el-rate v-model="stars.total_star" class="StarRate" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                        <span class="StarInfo">（&nbsp;来自{{user.reviews_count}}份评价&nbsp;）</span>
                                                    </div>
                                                    <div class="Review-comprehensive">
                                                        <el-popover  placement="bottom"  trigger="hover">
                                                            <div class="DetailStars DetailStars-first" slot="reference">
                                                                <span class="Star-title">薪资待遇:&nbsp;</span>
                                                                <el-rate v-model="stars.attitude_star.total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                            </div>
                                                            <div class="StarLists">
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.five_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.attitude_star.five_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.four_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.attitude_star.four_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.three_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.attitude_star.three_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.two_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.attitude_star.two_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.one_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.attitude_star.one_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                            </div>
                                                        </el-popover>
                                                        <el-popover  placement="bottom"  trigger="hover">
                                                            <div class="DetailStars" slot="reference">
                                                                <span class="Star-title">工资发放速度:&nbsp;</span>
                                                                <el-rate v-model="stars.ability_star.total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                            </div>
                                                            <div class="StarLists">
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.five_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.ability_star.five_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.four_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.ability_star.four_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.three_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.ability_star.three_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.two_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.ability_star.two_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.one_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.ability_star.one_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                            </div>
                                                        </el-popover>
                                                        <el-popover  placement="bottom"  trigger="hover">
                                                            <div class="DetailStars" slot="reference">
                                                                <span class="Star-title">描述相符:&nbsp;</span>
                                                                <el-rate v-model="stars.description_match.total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                            </div>
                                                            <div class="StarLists">
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.five_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.description_match.five_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.four_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.description_match.four_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.three_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.description_match.three_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.two_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.description_match.two_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.one_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.description_match.one_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                            </div>
                                                        </el-popover>
                                                    </div>
                                                </div>
                                                <div class="ReviewLists" v-if="activeReviewButton == '全部'">
                                                    <div class="ReviewLists ListItems" v-if="reviews.data.length > 0">
                                                        <ReviewList v-for="review in reviews.data" :review="review" class="ReviewList"></ReviewList>
                                                        <el-button type="primary" @click="loadMoreReviews('全部')" v-if="reviews.next_page_url">加载更多</el-button>
                                                        <el-button type="primary" :disabled="true" v-else>已全部加载完</el-button>
                                                    </div>
                                                    <div class="EmptyState" v-else>
                                                        <div class="EmptyState-inner">
                                                            <i class="fa fa-star-half-o EmptyState-icon"></i>
                                                            <span>暂时还没有评价</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ReviewState" v-else-if="activeReviewButton == '好评'">
                                                    <div class="ReviewLists" v-if="goodReviews.length > 0">
                                                        <ReviewList v-for="review in goodReviews" :review="review" class="ReviewList"></ReviewList>
                                                    </div>
                                                    <div class="EmptyState" v-else>
                                                        <div class="EmptyState-inner">
                                                            <i class="fa fa-star-half-o EmptyState-icon"></i>
                                                            <span>暂时还没有好评</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ReviewState" v-else-if="activeReviewButton == '中评'">
                                                    <div class="ReviewLists" v-if="centerReviews.length > 0">
                                                        <ReviewList v-for="review in centerReviews" :review="review" class="ReviewList"></ReviewList>
                                                    </div>
                                                    <div class="EmptyState" v-else>
                                                        <div class="EmptyState-inner">
                                                            <i class="fa fa-star-half-o EmptyState-icon"></i>
                                                            <span>暂时还没有中评</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="ReviewState" v-else-if="activeReviewButton == '差评'">
                                                    <div class="ReviewLists" v-if="badReviews.length > 0">
                                                        <ReviewList v-for="review in badReviews" :review="review" class="ReviewList"></ReviewList>
                                                    </div>
                                                    <div class="EmptyState" v-else>
                                                        <div class="EmptyState-inner">
                                                            <i class="fa fa-star-half-o EmptyState-icon"></i>
                                                            <span>暂时还没有差评</span>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-star-half-o EmptyState-icon"></i>
                                                    <span>暂时还没有评价</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="team" id="Team" ref="Team">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/team'">
                                            <i class="fa fa-users fa-fw"></i>&nbsp;团队
                                        </router-link>
                                    </span>
                                    <div class="ProfileCompanys">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && user.id == me.id">我属于的团队</h4>
                                            <h4 class="ListHeader-text" v-else-if="user.gender == '男'">他属于的团队</h4>
                                            <h4 class="ListHeader-text" v-else>她属于的团队</h4>
                                        </div>
                                        <div class="ListContent">
                                            <div class="EmptyState" v-if="!team">
                                                <div class="Empty-myself" v-if="me !== null && user.id == me.id">
                                                    <div class="EmptyState-inner">
                                                        <i class="fa fa-users EmptyState-icon"></i>
                                                        <span>你暂时还没加入团队</span><br>
                                                        <el-button type="text" @click="createTeamShow = true" style="font-weight:500;">点击这里创建团队</el-button>
                                                    </div>
                                                </div>
                                                <div class="Empty-else" v-else>
                                                    <div class="EmptyState-inner">
                                                        <i class="fa fa-users EmptyState-icon"></i>
                                                        <span>该用户暂时还没有加入团队</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="CompanyInfo" v-else>
                                                <div class="CompanyWarp">
                                                    <div class="Company-logo">
                                                        <img :src="'/storage/' + team.logo_path" alt="logo" width="90">
                                                    </div>
                                                    <div class="Company-right">
                                                        <div class="Company-main">
                                                            <div class="Company-head">
                                                                <router-link to="/company/1">
                                                                    <span class="Company-title">{{team.name}}</span>
                                                                </router-link>
                                                                <div class="Identification" v-if="team.certificated">
                                                                    <i class="fa fa-check-circle-o white"></i>&nbsp;&nbsp;已验证
                                                                </div>
                                                                <div class="Identification" v-else>未认证</div>
                                                            </div>
                                                            <div class="Company-word">
                                                                {{team.slogan}}
                                                            </div>
                                                            <div class="Company-info">
                                                                <div class="InfoItem"><i class="fa fa-industry fa-fw"></i> <span>{{team.industry}}</span></div>
                                                                <div class="InfoItem"><i class="fa fa-user fa-fw"></i> <span>{{team.members.length}}</span></div>
                                                                <div class="InfoItem"><i class="fa fa-map-marker fa-fw"></i> <span>{{team.city}}</span></div>
                                                            </div>
                                                        </div>
                                                        <div class="TeamAction" v-if="isTeamMember">
                                                            <el-badge :value="team.un_check_applicants.length"  v-if="isTeamOwner">
                                                                <el-button type="primary" class="LongButton" @click="teamDialogShow = true"><i class="fa fa-user-plus fa-fw white"></i>&nbsp;&nbsp;团队管理</el-button>
                                                            </el-badge>
                                                            <el-button type="danger" class="LongButton" v-else @click="leaveTeam"><i class="fa fa-user-times fa-fw white"></i>&nbsp;&nbsp;退出团队</el-button>
                                                        </div>
                                                        <div class="TeamAction" v-else>
                                                            <el-button type="primary" class="LongButton" @click="joinTeam" v-if="!isTeamApplied"><i class="fa fa-user-plus fa-fw white"></i>&nbsp;&nbsp;申请加入</el-button>
                                                            <el-button type="primary" class="LongButton" @click="joinTeam" v-else :disabled="true"><i class="fa fa-user-plus fa-fw white"></i>&nbsp;&nbsp;已申请</el-button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="Company-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;团队成员</div>
                                                <div class="MemberLists" v-if="team.members.length >0">
                                                    <TeamMemberList v-for="member in team.members" :user="member" :title="member.pivot.title" v-on:removeMember="removeTeamMember" v-on:addMember="addTeamMember"></TeamMemberList>
                                                </div>
                                                <div class="Company-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;团队介绍</div>
                                                <div class="CompanyIntro">
                                                    <div class="Company-text">
                                                        {{team.intro}}
                                                    </div>
                                                    <div class="Company-carousel" v-if="team.picture_path">
                                                        <el-carousel height="300px" type="card">
                                                            <el-carousel-item v-for="path in team.picture_path"><img :src="'/storage/' + path" alt="公司图片" height="300"></el-carousel-item>
                                                        </el-carousel>
                                                    </div>
                                                </div>
                                                <TeamManageDialog :show.sync ="teamDialogShow" :team="team" v-on:removeMember="removeTeamMember" v-on:addMember="addTeamMember"></TeamManageDialog>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="following" id="Followers" ref="Followers">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/following'">
                                            <i class="fa fa-address-card fa-fw"></i>&nbsp;关注<span class="Tag-meta">{{user.user_followers_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileFollowing">
                                        <div class="ListHeader">
                                            <div class="SubTabs">
                                                <div class="SubTabs-item Active-item" v-if="followType == 'follower'">
                                                    <span class="SubTab-text" v-if="me !== null && user.id == me.id" @click="followTypeChange('follower')">关注我的</span>
                                                    <span class="SubTab-text" v-else-if="user.gender == '男'" @click="followTypeChange('follower')">关注他的</span>
                                                    <span class="SubTab-text" v-else @click="followChange('follower')">关注她的</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text" v-if="me !== null && user.id == me.id" @click="followTypeChange('follower')">关注我的</span>
                                                    <span class="SubTab-text" v-else-if="user.gender == '男'" @click="followTypeChange('follower')">关注他的</span>
                                                    <span class="SubTab-text" v-else @click="followTypeChange('follower')">关注她的</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="followType == 'following'">
                                                    <span class="SubTab-text" v-if="me !== null && user.id == me.id" @click="followTypeChange('following')">我关注的</span>
                                                    <span class="SubTab-text" v-else-if="user.gender == '男'" @click="followTypeChange('following')">他关注的</span>
                                                    <span class="SubTab-text" v-else @click="followTypeChange('following')">她关注的</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text" v-if="me !== null && user.id == me.id" @click="followTypeChange('following')">我关注的</span>
                                                    <span class="SubTab-text" v-else-if="user.gender == '男'" @click="followTypeChange('following')">他关注的</span>
                                                    <span class="SubTab-text" v-else @click="followTypeChange('following')">她关注的</span>
                                                </div>
                                            </div>
                                            <div class="ListHeader-options">
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeFollowButton == '用户'" :disable="true">用户</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="followStatusChange('用户')">用户</el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeFollowButton == '雇主'" :disable="true">雇主</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="followStatusChange('雇主')">雇主</el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeFollowButton == '公司'" :disable="true">公司</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="followStatusChange('公司')">公司</el-button>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 21">
                                            <div class="FollowingLists" v-if="employerFollowers.length !== 0">
                                                <EmployerFollowList v-for="employer in employerFollowers" :employer="employer" class="FollowList"></EmployerFollowList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有被别的雇主关注</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 12">
                                            <div class="FollowingLists" v-if="followingEmployers.length !== 0">
                                                <EmployerFollowList v-for="employer in followingEmployers" :employer="employer" class="FollowList"></EmployerFollowList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有关注别的雇主</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 11">
                                            <div class="FollowingLists" v-if="userFollowers.length !== 0 && followType== 'follower'">
                                                <UserFollowList class="FollowList" v-for="user in userFollowers" :user="user"></UserFollowList>
                                            </div>
                                            <div class="EmptyState" v-else-if="userFollowers.length == 0 && followType== 'follower'">
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有被别的用户关注</span>
                                                </div>
                                            </div>
                                            <div class="FollowingLists" v-if="followingUsers.length !== 0 && followType== 'following'">
                                                <UserFollowList class="FollowList" v-for="user in followingUsers" :user="user"></UserFollowList>
                                            </div>
                                            <div class="EmptyState" v-else-if="followingUsers.length == 0 && followType== 'following'">
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有关注别的用户</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 13">
                                            <div class="FollowingLists" v-if="followingCompanys.length !== 0">
                                                <CompanyFollowList class="FollowList" v-for="company in followingCompanys" :company="company"></CompanyFollowList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-building EmptyState-icon"></i>
                                                    <span>暂时还没有关注别的公司</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 31">
                                            <div class="FollowingLists" v-if="companyFollowers.length !== 0">
                                                <CompanyFollowList class="FollowList" v-for="company in companyFollowers" :company="company"></CompanyFollowList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-building EmptyState-icon"></i>
                                                    <span>暂时还没有被别的公司关注</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="favourites" id="favourites" ref="favourites">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/user/' + user.id + '/favourites'">
                                            <i class="fa fa-heart fa-fw"></i>&nbsp;收藏<span class="Tag-meta">{{user.favorite_works_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileWorks">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && user.id == me.id">我收藏的兼职</h4>
                                            <h4 class="ListHeader-text" v-else-if="user.gender == '男'">他收藏的兼职</h4>
                                            <h4 class="ListHeader-text" v-else>她收藏的兼职</h4>
                                            <div class="ListHeader-options">
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="favouriteWorkType == '全部'">全部</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getFavouriteWorks('全部')">全部</el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="favouriteWorkType == '进行中'">进行中</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="favouriteWorkType = '进行中'">进行中</el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="favouriteWorkType == '已结束'" >已结束</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="favouriteWorkType = '已结束'">已结束</el-button>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="favouriteWorkType == '全部'">
                                            <div class="WorkLists" v-if="favouriteWorks.length !== 0 ">
                                                <WorkList v-for="work in favouriteWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有收藏的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="favouriteWorkType == '进行中'">
                                            <div class="WorkLists" v-if="applyingFavouriteWorks.length !== 0 ">
                                                <WorkList v-for="work in applyingFavouriteWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没收藏有正在进行兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="favouriteWorkType == '已结束'">
                                            <div class="WorkLists" v-if="finishedFavouriteWorks.length !== 0 ">
                                                <WorkList v-for="work in finishedFavouriteWorks" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没收藏有已结束的兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                            </el-tabs>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="8" class="right-panel">
                    <el-card class="AchievementCard">
                        <div slot="header">
                            <span class="Achievement-Title">个人成就</span>
                        </div>
                        <div class="AchievementContent">
                            <div class="AchievementItem">
                                <div class="Achievement-icon"><i class="fa fa-line-chart fa-fw"></i></div>
                                <div class="Achievement-text">
                                    <el-button type="warning" size="mini">Lv&nbsp;{{sumExp.lv}}</el-button>
                                    <div class="StarIcon">
                                        <div class="SunIcon" v-if="sumExp.sunNum > 0">
                                            <i class="fa fa-sun-o Gold" v-for="n in sumExp.sunNum"></i>
                                        </div>
                                        <div class="MoonIcon" v-if="sumExp.moonNum > 0">
                                            <i class="fa fa-moon-o Gold" v-for="n in sumExp.moonNum"></i>
                                        </div>
                                        <div class="Star-icon" v-if="sumExp.starNum > 0">
                                            <i class="fa fa-star-o Gold" v-for="n in sumExp.starNum"></i>
                                        </div>
                                    </div>
                                    <div class="NextLevel-text" v-if="me !== null && user.id == me.id">(距下级差{{sumExp.nextExp}})</div>
                                </div>
                                <el-popover  placement="right" width="300" title="如何快速升级？" trigger="hover">
                                    <el-button type="text" class="Icon-button" slot="reference"><i class="fa fa-question-circle-o"></i></el-button>
                                    <p>1.完成兼职即可按报酬1：1增加经验值</p>
                                    <p>2.完成兼职后认真评价兼职能得到50-100经验值</p>
                                    <p>3.每天分享兼职可以获得20经验值，最多只能加3次</p>
                                    <p>4.每天登录可以获得10经验值，只加一次</p>
                                </el-popover>
                            </div>
                            <div class="AchievementItem">
                                <div class="Achievement-icon"><i class="fa fa-calendar-check-o fa-fw"></i></div>
                                <div class="Achievement-text">获得&nbsp;{{user.user_exp}}&nbsp;点经验值</div>
                            </div>
                            <div class="AchievementItem">
                                <div class="Achievement-icon"><i class="fa fa-heart fa-fw"></i></div>
                                <div class="Achievement-text">获得&nbsp;{{user.thanks_count}}&nbsp;次感谢</div>
                            </div>
                        </div>
                    </el-card>
                    <KeywordCard role="user" :user="user" style="margin-top: 10px"></KeywordCard>
                </el-col>
            </div>
        </div>
        <CornerButtons></CornerButtons>
        <el-dialog title="创建团队" :visible.sync="createTeamShow" size="tiny">
            <el-form :model="newTeam" ref="newTeamForm" :rules="newTeamRules">
                <el-form-item label="团队名称" prop="name">
                    <el-input v-model="newTeam.name" type="text" placeholder="请在这里输入团队名称"></el-input>
                </el-form-item>
                <el-form-item label="所在城市" prop="city">
                    <el-input v-model="newTeam.city" type="text" placeholder="请在这里输入团队所在城市"></el-input>
                </el-form-item>
                <el-form-item label="擅长类型" prop="industry">
                    <el-input v-model="newTeam.industry" type="text" placeholder="请在这里输入团队擅长的兼职类型"></el-input>
                </el-form-item>
                <el-form-item label="一句话介绍" prop="slogan">
                    <el-input v-model="newTeam.slogan" type="text" placeholder="请在这里输入团队的一句话简介"></el-input>
                </el-form-item>
                <el-form-item label="团队介绍" prop="intro">
                    <el-input v-model="newTeam.intro" type="textarea" :rows="3" placeholder="请在这里输入团队介绍"></el-input>
                </el-form-item>
                <div class="ButtonGroup">
                    <el-button type="primary" @click="postNewTeam">提交</el-button>
                    <el-button @click="createTeamShow = false">取消</el-button>
                </div>
            </el-form>
        </el-dialog>
        <LoginDialog :show.sync ="loginDialogShow"></LoginDialog>
        <ChatDialog :show.sync ="chatDialogShow" :to-user="user" v-if="user"></ChatDialog>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import FeedItem from '../../components/common/FeedItem.vue';
    import WorkList from '../../components/common/WorkList.vue';
    import ReviewList from '../../components/user/ReviewList.vue';
    import UserFollowList from '../../components/common/Follow/UserFollowList.vue';
    import EmployerFollowList from '../../components/common/Follow/EmployerFollowList.vue';
    import CompanyFollowList from '../../components/common/Follow/CompanyFollowList.vue';
    import TeamMemberList from '../../components/common/TeamMemberList.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import TeamManageDialog from '../../components/common/Dialog/TeamManageDialog.vue';
    import LoginDialog from '../../components/common/Dialog/LoginDialog.vue';
    import ChatDialog from '../../components/common/Dialog/ChatDialog.vue';
    import KeywordCard from '../../components/common/Card/KeywordCard.vue';
    import { sumExp } from '../../util/format';
    import axios from 'axios';
    export default {
        name:'Profile',
        components:{Navbar,CornerButtons,FeedItem,WorkList,ReviewList,UserFollowList,EmployerFollowList,CompanyFollowList,TeamMemberList,TeamManageDialog,LoginDialog,ChatDialog,KeywordCard},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                avatarUrl:'',
                uploadConfig:{
                    headers:{
                        Authorization: 'bearer' + localStorage.token
                    }
                },
                detailShow: false,
                activeName: this.$route.params.activetab ? this.$route.params.activetab : 'activities' ,
                activeReviewButton: '全部',
                workType: 'applying',
                passedWorks: [],
                interviewFailedWorks:[],
                rejectedWorks: [],
                applyingWorks: [],
                interviewingWorks: [],
                workingWorks: [],
                reviewingWorks: [],
                finishedWorks: [],
                feeds: {
                    current_page:1,
                    data:[],
                    next_page_url:''
                },
                reviews:  {
                    current_page:1,
                    data:[],
                    next_page_url:''
                },
                goodReviews:[],
                centerReviews:[],
                badReviews:[],
                stars: {
                    total_star: 4.6,
                    five_star: 5,
                    four_star: 4,
                    three_star: 3,
                    two_star: 2,
                    one_star: 1,
                    total_reviews:'',
                    attitude_star: {
                        total_star:4.8,
                        one_star_percent: 0,
                        two_star_percent: 0,
                        three_star_percent: 0,
                        four_star_percent: 0,
                        five_star_percent: 0
                    },
                    description_match: {
                        total_star: 0,
                        one_star_percent: 0,
                        two_star_percent: 0,
                        three_star_percent: 0,
                        four_star_percent: 0,
                        five_star_percent: 0
                    },
                    ability_star:{
                        total_star:0,
                        one_star_percent: 0,
                        two_star_percent: 0,
                        three_star_percent: 0,
                        four_star_percent: 0,
                        five_star_percent: 0
                    }
                },
                team:null,
                newTeam:{
                    name:'',
                    city:'',
                    slogan:'',
                    intro:'',
                    industry:''
                },
                newTeamRules: {
                    name: [
                        {required: true, message: '请输入团队名称', trigger: 'blur'},
                        {min: 3, max: 15, message: '长度在 3 到 15 个字符', trigger: 'blur'}
                    ],
                    city: [
                        {required: true, message: '请输入团队所在城市', trigger: 'blur'}
                    ]
                },
                userFollowers: [],
                employerFollowers: [],
                companyFollowers: [],
                followingUsers: [],
                followingCompanys: [],
                followingEmployers: [],
                followType:'follower',
                activeFollowButton:'用户',
                followStatus: 11,
                favouriteWorks: [],
                applyingFavouriteWorks: [],
                finishedFavouriteWorks:[],
                favouriteWorkType:'全部',
                user: null,
                isTeamOwner:false,
                isTeamMember:false,
                isTeamApplied:false,
                teamDialogShow:false,
                createTeamShow:false,
                isFollowing:false,
                loginDialogShow:false,
                chatDialogShow:false,
                sumExp:null
            }
        },
        computed:{
            cantChat:function () {
                if (this.me != null && this.me.id == this.user.id) {
                    return true;
                }
            }
        },
        methods: {
            getUser:function () {
                return new Promise( (resolve,reject) => {
                    let that = this;
                    axios.get('/api/users/'+ this.$route.params.id).then(function (response) {
                        if (response.data.status ==1) {
                            resolve(response.data);
                            that.user = response.data.user;
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    }).catch ( error => {
                        that.$message.error(error);
                    });
                })
            },
            checkLogin: function () {
            if (!localStorage.user) {
                this.loginShow = true;
            }
        },
            handleTagChange: function (tag, event) {
                if (tag == this.$refs.MyWorks ) {
                    if ( (this.applyingWorks).length == 0)  this.getUserWorks('applying');
                } else if (tag == this.$refs.OtherWorks) {
                    this.workType = 'passed';
                    if ( (this.passedWorks).length == 0)  this.getUserReviews('passed');
                } else if (tag == this.$refs.Reviews) {
                    if ( (this.reviews.data).length == 0)  this.getUserReviews('全部');
                } else if (this.$route.params.activetab == 'favourites') {
                    if ( (this.favouriteWorks).length == 0)  this.getFavouriteWorks();
                } else if (tag == this.$refs.Team) {
                    this.getUserTeam();
                } else if (tag == this.$refs.Followers) {
                    if ( (this.userFollowers).length == 0) {
                        this.getUserFollowing(11);
                        this.getUserFollower(11);
                    }
                }
            },
            getUserWorks: function (type) {
                let that = this;
                if (type == 'passed') {
                    axios.get('/api/user/works/?status_array=[2,3,4]&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.passedWorks.length == 0)
                        that.passedWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                } else if (type == 'applying') {
                    axios.get('/api/user/works?status=0&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.applyingWorks.length == 0)
                        that.applyingWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'applying';
                } else if (type == 'interviewing') {
                    axios.get('/api/user/works?status=1&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.interviewingWorks.length == 0)
                            that.interviewingWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'interviewing';
                } else if (type == 'working') {
                    axios.get('/api/user/works?status=2&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.workingWorks.length == 0)
                            that.workingWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'working';
                }  else if (type == 'reviewing') {
                    axios.get('/api/user/works?status=3&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.reviewingWorks.length == 0)
                            that.reviewingWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'reviewing';
                }  else if (type == 'finished') {
                    axios.get('/api/user/works?status=4&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.finishedWorks.length == 0)
                            that.finishedWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'finished';
                } else if (type == 'rejected') {
                    axios.get('/api/user/works?status=-1&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.rejectedWorks.length == 0)
                            that.rejectedWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'rejected';
                } else if (type == 'interviewFailed') {
                    axios.get('/api/user/works?status=-2&user_id=' + this.$route.params.id).then(function (response) {
                        if (that.interviewFailedWorks.length == 0)
                            that.interviewFailedWorks = response.data.works.data;
                    }).catch( error => {
                        console.log(error);
                    });
                    that.workType = 'interviewFailed';
                }
            },
            getUserReviews: function (type) {
                let that = this;
                this.activeReviewButton = type;
                if (that.reviews.data.length == 0) {
                    axios.get('/api/user/reviews/?user_id=' + this.user.id).then(function (response) {
                        if (response.data.status == 1) {
                            that.reviews = response.data.reviews;
                            that.handleReviewSuccess(response.data.reviews.data);
                        } else {
                            that.$message.error(response.data.msg);
                        }
                    }).catch( (err) => {
                        console.log('getUserReviewsErro',err);
                    });
                }
            },
            loadMoreReviews:function () {
                let that = this;
                let page = this.reviews.current_page + 1;
                axios.get('/api/user/reviews/?user_id=' + this.user.id + '&page=' + page).then(function (response) {
                    if (response.data.status == 1) {
                        that.reviews.data.puser(...response.data.reviews);
                        that.handleReviewSuccess(that.reviews.data);
                        that.reviews.current_page += 1;
                    } else {
                        that.$message.error(response.data.msg);
                    }
                }).catch( (err) => {
                    console.log('getUserReviewsErro',err);
                });
            },
            handleReviewSuccess:function (reviews) {
                let length = reviews.length;
                let total_treat = 0;
                let total_speed =0 ;
                let total_match = 0;
                let treat_one_star_count = 0;
                let treat_two_star_count = 0;
                let treat_three_star_count = 0;
                let treat_four_star_count = 0;
                let treat_five_star_count = 0;
                let speed_one_star_count = 0;
                let speed_two_star_count = 0;
                let speed_three_star_count = 0;
                let speed_four_star_count = 0;
                let speed_five_star_count = 0;
                let match_one_star_count = 0;
                let match_two_star_count = 0;
                let match_three_star_count = 0;
                let match_four_star_count = 0;
                let match_five_star_count = 0;
                this.$_.forEach(reviews, item => {
                    total_treat = total_treat + item.attitude_star;
                    total_speed = total_speed + item.ability_star;
                    total_match = total_match + item.description_match;
                    if (item.attitude_star == 1) {
                        treat_one_star_count += 1;
                    } else if (item.attitude_star == 2) {
                        treat_two_star_count += 1;
                    } else if (item.attitude_star == 3) {
                        treat_three_star_count += 1;
                    } else if (item.attitude_star == 4) {
                        treat_four_star_count += 1;
                    } else {
                        treat_five_star_count += 1;
                    }
                    if (item.ability_star == 1) {
                        speed_one_star_count += 1;
                    } else if (item.ability_star == 2) {
                        speed_two_star_count += 1;
                    } else if (item.ability_star == 3) {
                        speed_three_star_count += 1;
                    } else if (item.ability_star == 4) {
                        speed_four_star_count += 1;
                    } else {
                        speed_five_star_count += 1;
                    }
                    if (item.description_match == 1) {
                        match_one_star_count += 1;
                    } else if (item.description_match == 2) {
                        match_two_star_count += 1;
                    } else if (item.description_match == 3) {
                        match_three_star_count += 1;
                    } else if (item.description_match == 4) {
                        match_four_star_count += 1;
                    } else {
                        match_five_star_count += 1;
                    }
                    if (Number(item.total_star) < 2.3) {
                        this.badReviews.push(item);
                    } else if (Number(item.total_star) > 3.7) {
                        this.goodReviews.push(item);
                    } else {
                        this.centerReviews.push(item);
                    }
                });
                this.stars.attitude_star.total_star = Number((total_treat / reviews.length).toFixed(1));
                this.stars.ability_star.total_star = Number((total_speed / reviews.length).toFixed(1));
                this.stars.description_match.total_star = Number((total_match / reviews.length).toFixed(1));
                this.stars.total_star = Number(((this.stars.attitude_star.total_star + this.stars.ability_star.total_star + this.stars.description_match.total_star) / 3).toFixed(1));
                this.stars.attitude_star.one_star_percent = Number((treat_one_star_count/ length).toFixed(2) * 100);
                this.stars.attitude_star.two_star_percent = Number(((treat_two_star_count/ length)).toFixed(2) * 100);
                this.stars.attitude_star.three_star_percent = Number((treat_three_star_count/ length).toFixed(2) * 100);
                this.stars.attitude_star.four_star_percent = Number((treat_four_star_count/ length).toFixed(2)* 100);
                this.stars.attitude_star.five_star_percent = Number((treat_five_star_count/ length).toFixed(2)* 100);
                this.stars.ability_star.one_star_percent = Number((speed_one_star_count/ length).toFixed(2) * 100);
                this.stars.ability_star.two_star_percent = Number(((speed_two_star_count/ length)).toFixed(2) * 100);
                this.stars.ability_star.three_star_percent = Number((speed_three_star_count/ length).toFixed(2) * 100);
                this.stars.ability_star.four_star_percent = Number((speed_four_star_count/ length).toFixed(2)* 100);
                this.stars.ability_star.five_star_percent = Number((speed_five_star_count/ length).toFixed(2)* 100);
                this.stars.description_match.one_star_percent = Number((match_one_star_count/ length).toFixed(2) * 100);
                this.stars.description_match.two_star_percent = Number(((match_two_star_count/ length)).toFixed(2) * 100);
                this.stars.description_match.three_star_percent = Number((match_three_star_count/ length).toFixed(2) * 100);
                this.stars.description_match.four_star_percent = Number((match_four_star_count/ length).toFixed(2)* 100);
                this.stars.description_match.five_star_percent = Number((match_five_star_count/ length).toFixed(2)* 100);
            },
            followTypeChange: function (type) {
                this.followType = type;
                if (type == 'following') {
                    this.followStatus = 11;
                    this.activeFollowButton = '用户';
                } else {
                    this.followStatus = 11;
                    this.activeFollowButton = '用户';
                }
            },
            followStatusChange: function (ActiveButton) {
                if (ActiveButton == '用户') {
                    this.activeFollowButton = '用户';
                    this.followStatus = 11;
                } else if (ActiveButton == '雇主') {
                    this.activeFollowButton = '雇主';
                    if (this.followType == 'following') {
                        this.followStatus = 12;
                    } else {
                        this.followStatus = 21;
                    }
                } else {
                    this.activeFollowButton = '公司';
                    if (this.followType == 'following') {
                        this.followStatus = 13;
                    } else {
                        this.followStatus = 31;
                    }
                }
            },
            uploadAvatarSuccess(res, file) {
                this.user.pic_path = res.pic_path;
                this.$message.success('更换头像成功');
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            handleCoverSuccess(res, file) {
                this.user.cover_path = res.pic_path;
                this.$message.success('更换背景成功');
            },
            beforeCoverUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            getUserFollowing:function (status) {
                let that = this;
                this.$axios.get('/api/user/followings',{
                    params:{
                        user_id: this.user.id,
                        status: status
                    }
                }).then(function (response) {
                    if (response.data.status == 1) {
                        if (status == 11) {
                            that.followingUsers = response.data.users;
                        } else if (status ==12) {
                            that.followingEmployers = response.data.users;
                        } else {
                            that.followingCompanys= response.data.users;
                        }
                    } else {
                        that.$message.error(response.data.msg);
                    }
                })
            },
            getUserFollower:function (status) {
                let that = this;
                this.$axios.get('/api/user/followers',{
                    params:{
                        user_id: this.user.id,
                        status: status
                    }
                }).then(function (response) {
                    if (response.data.status == 1) {
                        if (status == 11) {
                            that.userFollowers = response.data.users;
                        } else if (status ==21) {
                            that.employerFollowers = response.data.users;
                        } else {
                            that.companyFollowers = response.data.users;
                        }
                    } else {
                        that.$message.error(response.data.msg);
                    }
                })
            },
            getUserFeeds:function () {
                let that = this;
                this.$axios.get('/api/user/actions?user_id=' + this.$route.params.id).then( res => {
                    if (res.data.status == 1) {
                        that.feeds = res.data.feeds;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch ( err => {
                    console.log(error);
                })
            },
            loadMoreFeeds:function () {
                let that = this;
                let page = this.feeds.current_page + 1;
                this.$axios.get('/api/user/actions?user_id=' + this.$route.params.id + '&page=' + page).then( res => {
                    if (res.data.status == 1) {
                        that.feeds.data.push(...res.data.feeds.data);
                        that.feeds.current_page += 1;
                        that.feeds.next_page_url = res.data.feeds.next_page_url;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch ( err => {
                    console.log(err);
                })
            },
            getFavouriteWorks:function () {
                let that = this;
                this.$axios.get('/api/user/favoriteworks?user_id=' + this.$route.params.id).then( res => {
                    if (res.data.status ==1) {
                        that.favouriteWorks = res.data.works;
                        if (res.data.works.length > 0) {
                            this.$_.forEach(res.data.works, item => {
                                if (item.status == 1) {
                                    that.applyingFavouriteWorks.push(item);
                                } else {
                                    that.finishedFavouriteWorks.push(item);
                                }
                            });
                        }
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log(err);
                })
            },
            getUserTeam:function () {
                let that = this;
                this.$axios.get('/api/team?user_id=' + that.$route.params.id).then( res => {
                    if (res.data.status == 1) {
                        that.team = res.data.team;
                        if (localStorage.user && that.team) {
                            that.checkMember(that.team.members);
                        }
                    } else {
                        console.log('getTeamError',res);
                    }
                })
            },
            checkMember:function (members) {
                let that = this;
                let owner = that.$_.find(members,function (member) {
                    return member.id == that.me.id && member.pivot.title == '队长';
                })
                let member = that.$_.find(members,function (member) {
                    return member.id == that.me.id ;
                })
                if (owner !== undefined) that.isTeamOwner = true;
                if (member !== undefined) {
                    that.isTeamMember = true;
                }  else {
                    that.checkTeamApplied();
                }
            },
            joinTeam:function () {
                let that = this;
                this.$axios.post('/api/team/member',{
                    team_id: this.team.id
                }).then( res => {
                    if (res.data.status == 1) {
                        that.$message.success(res.data.msg);
                        that.isTeamApplied = true;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log('joinTeamError',err);
                })
            },
            leaveTeam:function () {
                let that = this;
                this.$axios.delete('/api/team/member/' + this.me.id).then( res => {
                    if (res.data.status == 1) {
                        that.$message.success(res.data.msg);
                        that.isTeamMember = false;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log('leaveTeamError',err);
                })
            },
            checkTeamApplied:function () {
                let that = this;
                this.$axios.get('/api/team/member/appliedstatus?team_id=' + this.team.id).then( res => {
                    if (res.data.status ==1) {
                        that.isTeamApplied = res.data.applied;
                        console.log(that.isTeamApplied)
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log('checkTeamAppliedError',err);
                })
            },
            addTeamMember:function (member) {
                this.team.members.push(member);
                let index = this.$_.findIndex(this.team.un_check_applicants,function (item) {
                    return item.id == member.id
                })
                this.team.un_check_applicants.splice(index,1);
            },
            removeTeamMember:function (member) {
                let index = this.$_.findIndex(this.team.members,function (item) {
                    return item.id == member.id
                })
                this.team.members.splice(index,1);
            },
            postNewTeam:function () {
                let that = this;
                this.$axios.post('/api/team',this.newTeam).then( res => {
                    if (res.data.status == 1) {
                        that.$message.success(res.data.msg);
                        that.team = res.data.team;
                        that.checkMember(that.team.members);
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            followUser:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    this.$axios.post('/api/user/followings',{
                        to_id: that.user.id,
                        status: 11
                    }).then(function (response) {
                        return new Promise(function (resovle, reject) {
                            if (response.data.status == 1) {
                                resovle(response.data);
                                that.$message.success(response.data.msg);
                                that.isFollowing = true;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                                that.isFollowing = false;
                            }
                        })
                    })
                }
            },
            unFollowUser:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    this.$axios.delete('/api/user/followings/' + that.user.id +'?status=11').then(function (response) {
                        return new Promise(function (resovle, reject) {
                            if (response.data.status == 1) {
                                resovle(response.data);
                                that.isFollowing = false;
                                that.$message.success(response.data.msg);
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
            checkFollowstatus:function () {
                if (localStorage.user) {
                    let that = this;
                    this.$axios.get('/api/user/followingcheck?to_id=' + that.user.id + '&status=11').then(function (response) {
                        return new Promise(function (resolve, reject) {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.isFollowing = response.data.follow;
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            },
        },
        watch: {
            followStatus: function (newStatus) {
                if (this.followType == 'following') {
                    if (newStatus == 11 && this.followingUsers.length == 0) {
                        this.getUserFollowing(newStatus);
                    } else if (newStatus == 12 && this.followingEmployers.length == 0) {
                        this.getUserFollowing(newStatus);
                    } else if (newStatus == 13 && this.followingCompanys.length == 0) {
                        this.getUserFollowing(newStatus);
                    }
                } else {
                    if (newStatus == 11 && this.userFollowers.length == 0) {
                        this.getUserFollower(newStatus);
                    } else if (newStatus == 21 && this.employerFollowers.length == 0) {
                        this.getUserFollower(newStatus);
                    } else if (newStatus == 31 && this.companyFollowers.length == 0) {
                        this.getUserFollower(newStatus);
                    }
                }
            }
        },
        created: function () {
            let that = this;
            this.getUser().then(function () {
                that.getUserFeeds();
                that.checkFollowstatus();
                that.sumExp = sumExp(that.user.user_exp);
                that.workType = (that.me && that.me.id == that.user.id) ? 'applying' : 'passed';
                if (that.$route.params.activetab == 'works' ) {
                    if (that.me && that.me.id == that.user.id) {
                        if ( (that.applyingWorks).length == 0)  that.getUserWorks('applying');
                    } else {
                        if ( (that.passedWorks).length == 0)  that.getUserWorks('passed');
                    }
                } else if (that.$route.params.activetab == 'reviews') {
                    if ( (that.reviews.data).length == 0)  that.getUserReviews('全部');
                } else if (that.$route.params.activetab == 'favourites') {
                    if ( (that.favouriteWorks).length == 0)  that.getFavouriteWorks();
                } else if (that.$route.params.activetab == 'team') {
                    that.getUserTeam();
                } else if (that.$route.params.activetab == 'following') {
                    if ( (that.userFollowers).length == 0) {
                        that.getUserFollowing(11);
                        that.getUserFollower(11);
                    }
                }
            })
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

    }
    .right-panel {
        float: right;
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
    .UserCoverEditor {
        position: relative;
    }
    .Cover-button {
        position: absolute;
        top: 24px;
        right: 24px;
        z-index: 1;
    }
    .UserCover {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: #f7f8fa;
        border-top-right-radius: 1px;
        border-top-left-radius: 1px;
        transition: height .3s;
    }
    .UserCover-image, .UserCover-image img {
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .ProfileHeader-wrapper {
        position: relative;
        width: 100%;
        background: #fff;
        box-sizing: border-box;
    }
    .ProfileHeader-main {
        position: relative;
        margin: 0 20px 24px;
    }
    .ProfileHeader-avatar {
        vertical-align: top;
        display: inline-block;
        overflow: hidden;
        border: 4px solid #fff;
        border-radius: 8px;
        position: absolute;
        top: -45px;
        left: 0;
        z-index: 4;
    }
    .EmployerAvatar-mask {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        left: 0;
        z-index: 1;
        transition: opacity .2s ease-in;
    }
    .EmployerAvatar-mask:hover {
        opacity: .6;
    }
    .Mask-mask {
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        opacity: .4;
        box-sizing: border-box;
        background: #000;
    }
    .MaskInner {
        z-index: 4;
        border-radius: 8px;
    }
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #20a0ff;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 160px;
        height: 160px;
        line-height: 160px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
    .ProfileHeader-content {
        padding-left: 32px;
        border-left: 164px solid transparent;
    }
    .ProfileHeader-contentHead {
        position: relative;
        padding-right: 106px;
        margin-bottom: 16px;
    }
    .ProfileHeader-title {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ProfileHeader-name {
        font-size: 26px;
        font-weight: 700;
        line-height: 30px;
    }
    .ProfileHeader-intro {
        margin-left: 12px;
        font-weight:lighter;
        font-size: 18px;
        white-space: nowrap;
    }
    .ProfileHeader-contentBody {
        position: relative;
        width: 524px;
        overflow: hidden;
        transition: height .3s;
    }
    .ProfileHeader-info {
        font-size: 14px;
        line-height: 20px;
        box-sizing: border-box;
    }
    .ProfileHeader-infoItem {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ProfileHeader-infoItem:not(:last-child) {
        margin-bottom: 10px;
    }
    .ProfileHeader-iconWrapper {
        display: inline-block;
        width: 18px;
        height: 18px;
        margin-right: 12px;
        text-align: center;
    }
    .ProfileHeader-divider {
        display: inline-block;
        width: 1px;
        height: 10px;
        margin: 0 8px;
        background: #e7eaf1;
    }
    .ProfileHeader-contentFooter {
        position: relative;
        padding-top: 8px;
    }
    .ExpandButton {
        height: auto;
        padding: 0;
        line-height: inherit;
        background-color: transparent;
        border: none;
        border-radius: 0;
        color: #8590a6;
    }
    .ProfileButtonGroup {
        position: absolute;
        right: 0;
        bottom: 0;
    }
    .FollowIcon {
        color: #ffffff;
    }
    .ProfileHeader-detail {
        width: 100%;
        font-size: 14px;
        line-height: 1.8;
        color: #262626;
    }
    .ProfileHeader-detailItem {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        margin-bottom: 18px;
    }
    .ProfileHeader-detailLabel {
        width: 60px;
        margin-right: 37px;
        font-weight: 700;
    }
    .ProfileHeader-detailValue {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
    }
    .Profile-main {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        width: 1210px;
        min-height: 100vh;
        margin: 10px auto;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: start;
        -ms-flex-align: start;
        align-items: flex-start;
    }
    .ProfileTabs {
        margin-top: -4px;
    }
    .TapTitle {
        font-size: 16px;
        font-weight: 600;
    }
    .Tag-meta {
        margin-left: 6px;
        font-size: 14px;
        font-weight: 400;
        line-height: 20px;
        color: #a1aebf;
    }
    .Button-meta {
        font-size: 13px;
        font-weight: 400;
        margin-left: 2px;
        line-height: 20px;
        color: #a1aebf;
    }
    .is-active i {
        color: #20a0ff;
    }
    .is-active .TapTitle {
        font-weight: 700;
    }
    .ListHeader {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        height: 50px;
        margin: -15px 20px 0;
        border-bottom: 1px solid #f0f2f7;
        box-sizing: border-box;
    }
    .ListHeader-text {
        font-weight: 700;
        margin: 0;
    }
    .ListHeader-options {
        font-size: 14px;
    }
    .TextButton {
        color: #748aa2;
    }
    .ActiveButton {
        color: #20a0ff;
        font-weight:700;
    }
    .DetailStars {
        display: flex;
        margin-left:20px;
        margin-bottom:10px;
    }
    .DetailStars-first {
        margin-left:0px ;
    }
    .Star-title {
        float: left;
        color: #999;
        font-size:14px;
        font-weight:400;
    }
    .Star-title-all {
        font-size:15px;
    }
    .StarList {
        display: flex;
        width: 300px;
        margin:0 10px 10px 10px;
    }
    .StarList:first-child {
        margin-top:10px;
    }
    .StarList-points {
        width: 150px;
    }
    .StarList-percent {
        width: 150px;
    }
    .StarRate {
        margin-left:10px;
        margin-top: 3px;
        font-size: 20px;
    }
    .StarInfo {
        color: #999;
        font-size: 15px;
        margin-left:25px;
    }
    .ListItems {
        display: flex;
        flex-direction: column;
    }
    .FeedItem {
        border:none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        box-shadow: none;
        padding: 10px 10px 20px 10px;
    }
    .FeedItem:first-child {
        margin-top:10px;
    }
    .FeedItem:not(:last-child) {
        border-bottom: 1px solid #f0f2f7;
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
    .ReviewTop {
        margin: 15px 20px 10px 20px;
        padding-bottom: 10px;
        border-bottom: 1px solid #f0f2f7;
    }
    .Review-comprehensive-all {
        display: flex;
        font-size:14px;
        height: 25px;
        line-height: 25px;
        margin-bottom:10px;
    }
    .Review-comprehensive {
        display: flex;
        font-size:14px;
        height: 25px;
        line-height: 25px;
        margin-bottom:10px;
    }
    .ReviewList {
        margin-bottom:20px;
    }
    .ReviewList:not(:last-child) {
        border-bottom: 1px solid #f0f2f7;
    }
    .CompanyInfo {
        margin-left:20px;
    }
    .Company-logo {
        width:120px;
    }
    .CompanyWarp {
        display: flex;
        padding-bottom: 20px;
        margin-top:20px;
        border-bottom: 1px solid #f0f2f7;
    }
    .Company-right {
        width: 100%;
        display: flex;
    }
    .Company-main {
        position: relative;
        margin-left:10px;
        margin-bottom:10px;
        overflow: hidden;
    }
    .TeamAction {
        align-self:center;
        margin-left:auto;
        margin-right: 34px;
    }
    .LongButton {
        width: 120px;
    }
    .Company-head {
        display: flex;
    }
    .Identification {
        padding: 0px 10px;
        margin-left: 20px;
        color: #fff;
        background: #00b38a;
        border: 1px solid #00b38a;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        -ms-border-radius: 20px;
        -o-border-radius: 20px;
        border-radius: 20px;
    }
    .white {
        color: white;
    }
    .Company-word {
        margin-top: 10px;
        font-size: 16px;
        clear: both;
    }
    .Company-info {
        margin-top: 10px;
        display: flex;
    }
    .InfoItem {
        margin-right:20px;
    }
    .Icon-button {
        padding: 0px;
    }
    .Company-title {
        font-size: 16px;
        font-weight: 600;
        margin-top:10px;
        margin-bottom:10px;
    }
    .blue {
        color: #316d9e;
    }
    .CompanyIntro {
        margin-top:10px;
    }
    .Company-carousel {
        margin-top:20px;
    }
    .MemberLists {
        max-height: 340px;
        overflow-y: scroll;
        padding-bottom:10px;
        border-bottom: 1px solid #f0f2f7;
    }
    .SubTabs {
        display: flex;
    }
    .SubTabs-item {
        height: 21px;
        padding: 6px 0 7px;
        font-weight: 500;
        margin-right: 36px;
    }
    .Active-item {
        font-weight: 700;
        /*color: #20a0ff;*/
    }
    .SubTab-text {
        font-weight:inherit;
        cursor: pointer;
        color: inherit;
    }
    .FollowList {
        margin-bottom:5px;
    }
    .FollowList:not(:last-child):after {
        position: absolute;
        left: 0;
        right: 0;
        bottom: 0;
        margin: 0 20px;
        display: block;
        border-bottom: 1px solid #f0f2f7;
        content: "";
    }
    .Achievement-Title {
        overflow: hidden;
        font-weight: 700;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .AchievementContent {

    }
    .AchievementItem {
        padding: 6px 16px;
    }
    .Achievement-icon {
        display: inline-block;
        width: 28px;
    }
    .Achievement-text {
        display: inline-flex;
    }
    .StarIcon {
        font-size: 20px;
        margin-left:5px;
        display: inherit;
    }
    .Gold {
        color: #f9c855;
        margin-left: 2px;
    }
    .NextLevel-text {
        display: block;
        margin-left:5px;
        font-size: 14px;
        color: #8590a6;
    }
</style>