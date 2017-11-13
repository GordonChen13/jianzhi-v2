<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main" v-if="employer">
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
                            <img class="UserCover-image" width="1210" :src="'/storage/' + employer.cover_path" alt="用户封面">
                        </div>
                    </div>
                    <div class="ProfileHeader-wrapper">
                        <div class="ProfileHeader-main">
                            <div class="ProfileHeader-avatar">
                                <img :src="'/storage/' + employer.pic_path" alt="用户头像" width="160px" height="160px">
                                <div class="EmployerAvatar-mask">
                                    <div class="Mask-mask MaskInner">
                                        <el-upload class="avatar-uploader" action="/api/photos/avatar" :show-file-list="false"
                                                   :on-success="uploadAvatarSuccess" :before-upload="beforeAvatarUpload"
                                                   :headers="uploadConfig.headers" name="avatar" accept=".jpg,.png">
                                            <img v-if="avatarUrl" :src="employer.pic_path" class="avatar">
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
                                        <span class="ProfileHeader-name">{{employer.name}}</span>
                                        <span class="ProfileHeader-intro">{{employer.simple_intro}}</span>
                                    </h1>
                                </div>
                                <div class="ProfileHeader-contentBody">
                                    <div class="Company-data">
                                    <ul>
                                        <li><strong>{{employer.applying_works_count}}个</strong>
                                            <span class="Company-tip">在招兼职
                                                <el-tooltip content="该雇主正在招聘的兼职" placement="bottom">
                                                    <el-button type="text" class="Icon-button"><i class="fa fa-question-circle-o"></i></el-button>
                                                </el-tooltip>
                                            </span>
                                        </li>
                                        <li><strong style="margin-top:6px;">100%</strong>
                                            <span class="Company-tip">申请处理及时率
                                                <el-tooltip content="该雇主在7天内处理兼职申请的比例" placement="bottom">
                                                    <el-button type="text" class="Icon-button"><i class="fa fa-question-circle-o"></i></el-button>
                                                </el-tooltip>
                                            </span>
                                        </li>
                                        <li><strong>1天</strong>
                                            <span class="Company-tip">申请处理时间
                                                <el-tooltip content="该雇主处理申请平均用时" placement="bottom">
                                                    <el-button type="text" class="Icon-button"><i class="fa fa-question-circle-o"></i></el-button>
                                                </el-tooltip>
                                            </span>
                                        </li>
                                        <li><strong>{{employer.reviews_count}}个</strong>
                                            <span class="Company-tip">兼职评价
                                                <el-tooltip content="该雇主的兼职得到的评价" placement="bottom">
                                                    <el-button type="text" class="Icon-button"><i class="fa fa-question-circle-o"></i></el-button>
                                                </el-tooltip>
                                            </span>
                                        </li>
                                        <li><strong>今天</strong>
                                            <span class="Company-tip">最近登录
                                                <el-tooltip content="该雇主最近登录时间" placement="bottom">
                                                    <el-button type="text" class="Icon-button"><i class="fa fa-question-circle-o"></i></el-button>
                                                </el-tooltip>
                                            </span>
                                        </li>
                                    </ul>
                                </div>
                                    <div class="ProfileHeader-info" v-if="!detailShow">
                                        <div class="ProfileHeader-infoItem">
                                            <div class="ProfileHeader-iconWrapper">
                                                <i class="fa fa-map-marker"></i>
                                            </div>
                                            {{employer.city}}
                                            <div class="ProfileHeader-divider"></div>
                                            <div class="ProfileHeader-iconWrapper" v-if="employer.gender == '男' ">
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
                                            {{employer.school}}
                                            <div class="ProfileHeader-divider"></div>
                                            {{employer.major}}
                                        </div>
                                    </div>
                                    <div class="ProfileHeader-detail" v-else>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">居住地</span>
                                            <div class="ProfileHeader-detailValue"><span>现居{{employer.city}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">年龄</span>
                                            <div class="ProfileHeader-detailValue"><span>{{employer.age}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">邮箱</span>
                                            <div class="ProfileHeader-detailValue"><span>{{employer.email}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">教育经历</span>
                                            <div class="ProfileHeader-detailValue"><span>{{employer.school}}--{{employer.major}}</span></div>
                                        </div>
                                        <div class="ProfileHeader-detailItem">
                                            <span class="ProfileHeader-detailLabel">个人介绍</span>
                                            <div class="ProfileHeader-detailValue"><span>{{employer.introduction}}</span></div>
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
                                    <router-link :to="'/employer/' + employer.id + '/edit'" v-if="me !== null && me.id == employer.id">
                                        <el-button class="EditButton ProfileButtonGroup" type="primary">
                                            修改个人资料
                                        </el-button>
                                    </router-link>
                                    <div class="ProfileButtonGroup" v-else-if="employer.gender == '男' ">
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
                                        <router-link :to="'/employer/' + employer.id + '/activities'">
                                            <i class="fa fa-rss-square fa-fw"></i>&nbsp;动态
                                        </router-link>
                                    </span>
                                    <div class="ProfileActivities">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && employer.id == me.id">我的动态</h4>
                                            <h4 class="ListHeader-text" v-else-if="employer.gender == '男'">他的动态</h4>
                                            <h4 class="ListHeader-text" v-else>她的动态</h4>
                                        </div>
                                        <div class="ListContent">
                                            <div class="FeedItems ListItems" v-if="feeds.data.length !== 0 ">
                                                <FeedItem class="FeedItem" v-for="feed in feeds.data" :body-style="{ padding: '10px 10px 0px'}" :from="employer" :feed="feed" :show-name="false"></FeedItem>
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
                                <el-tab-pane name="works" id="Works" ref="Works">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/employer/' + employer.id + '/works'">
                                            <i class="fa fa-briefcase fa-fw"></i>&nbsp;兼职<span class="Tag-meta">{{employer.checked_works_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileWorks">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && employer.id == me.id">我发布的兼职</h4>
                                            <h4 class="ListHeader-text" v-else-if="employer.gender == '男'">他发布的兼职</h4>
                                            <h4 class="ListHeader-text" v-else>她发布的兼职</h4>
                                            <div class="ListHeader-options">
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeWorkButton == '全部'">全部</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerWorks('全部')">全部</el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeWorkButton == '进行中'">进行中</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerWorks('进行中')">进行中</el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeWorkButton == '已结束'" >已结束</el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerWorks('已结束')">已结束</el-button>
                                            </div>
                                        </div>
                                        <div class="ListContent">
                                            <div class="WorkLists ListItems" v-if="works.data.length !== 0 ">
                                                <WorkList v-for="work in works.data" :work="work" class="FeedItem" :body-style="{ padding: '10px 20px 20px 20px'}"></WorkList>
                                                <el-button type="primary" @click="loadMoreWorks(activeWorkButton)" v-if="works.next_page_url">加载更多</el-button>
                                                <el-button type="primary" :disabled="true" v-else>已全部加载完</el-button>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-briefcase EmptyState-icon"></i>
                                                    <span>暂时还没有兼职</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="reviews" id="Reviews" ref="Reviews">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/employer/' + employer.id + '/reviews'">
                                            <i class="fa fa-star-half-o fa-fw"></i>&nbsp;评价<span class="Tag-meta">{{employer.reviews_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileReviews">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && employer.id == me.id">我得到的评价</h4>
                                            <h4 class="ListHeader-text" v-else-if="employer.gender == '男'">他得到的评价</h4>
                                            <h4 class="ListHeader-text" v-else>她得到的评价</h4>
                                            <div class="ListHeader-options">
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '全部'">全部 <span class="Button-meta">{{reviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerReviews('全部')">全部<span class="Button-meta">{{reviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '好评'">好评<span class="Button-meta">{{goodReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerReviews('好评')">好评<span class="Button-meta">{{goodReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '中评'" >中评<span class="Button-meta">{{centerReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerReviews('中评')">中评<span class="Button-meta">{{centerReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton ActiveButton" v-if="activeReviewButton == '差评'" >差评<span class="Button-meta">{{badReviews.length}}</span></el-button>
                                                <el-button  type="text" class="TextButton" v-else  @click="getEmployerReviews('差评')">差评<span class="Button-meta">{{badReviews.length}}</span></el-button>
                                            </div>
                                        </div>
                                        <div class="ListContent">
                                            <div class="ReviewState" v-if="reviews.length !== 0 ">
                                                <div class="ReviewTop">
                                                    <div class="Review-comprehensive-all">
                                                        <span class="Star-title-all" style="color:inherit;">综合评分&nbsp;:</span>
                                                        <el-rate v-model="stars.total_star" class="StarRate" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                        <span class="StarInfo">（&nbsp;来自{{employer.reviews_count}}份评价&nbsp;）</span>
                                                    </div>
                                                    <div class="Review-comprehensive">
                                                        <el-popover  placement="bottom"  trigger="hover">
                                                            <div class="DetailStars DetailStars-first" slot="reference">
                                                                <span class="Star-title">薪资待遇:&nbsp;</span>
                                                                <el-rate v-model="stars.treat_star.total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                            </div>
                                                            <div class="StarLists">
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.five_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.treat_star.five_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.four_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.treat_star.four_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.three_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.treat_star.three_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.two_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.treat_star.two_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.one_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.treat_star.one_star_percent" class="StarList-percent"></el-progress>
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
                                                        <el-popover  placement="bottom"  trigger="hover">
                                                            <div class="DetailStars" slot="reference">
                                                                <span class="Star-title">工资发放速度:&nbsp;</span>
                                                                <el-rate v-model="stars.pay_speed.total_star" disabled show-text text-color="#ff9900" text-template="{value}"></el-rate>
                                                            </div>
                                                            <div class="StarLists">
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.five_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.pay_speed.five_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.four_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.pay_speed.four_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.three_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.pay_speed.three_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.two_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.pay_speed.two_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                                <el-row class="StarList">
                                                                    <el-rate v-model="stars.one_star" disabled class="StarList-points"></el-rate>
                                                                    <el-progress :text-inside="true" :stroke-width="18" :percentage="stars.pay_speed.one_star_percent" class="StarList-percent"></el-progress>
                                                                </el-row>
                                                            </div>
                                                        </el-popover>
                                                    </div>
                                                </div>
                                                <div class="ReviewLists" v-if="activeReviewButton == '全部'">
                                                    <ReviewList v-for="review in reviews" :review="review" class="ReviewList"></ReviewList>
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
                                <el-tab-pane name="company" id="Company" ref="Company">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/employer/' + employer.id + '/company'">
                                            <i class="fa fa-building fa-fw"></i>&nbsp;公司
                                        </router-link>
                                    </span>
                                    <div class="ProfileCompanys">
                                        <div class="ListHeader">
                                            <h4 class="ListHeader-text" v-if="me !== null && employer.id == me.id">我属于的公司</h4>
                                            <h4 class="ListHeader-text" v-else-if="employer.gender == '男'">他属于的公司</h4>
                                            <h4 class="ListHeader-text" v-else>她属于的公司</h4>
                                        </div>
                                        <div class="ListContent">
                                            <div class="EmptyState" v-if="!company">
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>个人发布者，不属于公司</span>
                                                </div>
                                            </div>
                                            <div class="CompanyInfo" v-else>
                                                <div class="CompanyWarp">
                                                    <div class="Company-logo">
                                                        <img :src="'/storage/' + company.logo_path" alt="logo" width="120">
                                                    </div>
                                                    <div class="Company-right">
                                                        <div class="Company-main">
                                                            <div class="Company-head">
                                                                <router-link to="/company/1">
                                                                    <span class="Company-title">{{company.name}}</span>
                                                                </router-link>
                                                                <div class="Identification" v-if="company.certificated">
                                                                    <i class="fa fa-check-circle-o white"></i>&nbsp;&nbsp;已验证
                                                                </div>
                                                                <div class="Identification" v-else>未认证</div>
                                                            </div>
                                                            <div class="Company-word">
                                                                {{company.slogan}}
                                                            </div>
                                                            <div class="Company-info">
                                                                <div class="InfoItem"><i class="fa fa-industry fa-fw"></i> <span>{{company.industry}}</span></div>
                                                                <div class="InfoItem"><i class="fa fa-user fa-fw"></i> <span>{{company.size}}</span></div>
                                                                <div class="InfoItem"><i class="fa fa-map-marker fa-fw"></i> <span>{{company.city}}</span></div>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                                <div class="Company-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;团队介绍</div>
                                                <div class="CompanyIntro">
                                                    <div class="Company-text">
                                                        {{company.intro}}
                                                    </div>
                                                    <div class="Company-carousel">
                                                        <el-carousel height="300px" type="card">
                                                            <el-carousel-item v-for="path in company.picture_path"><img :src="'/storage/' + path" alt="团队图片" height="300"></el-carousel-item>
                                                        </el-carousel>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </el-tab-pane>
                                <el-tab-pane name="following" id="Followers" ref="Followers">
                                    <span class="TapTitle" slot="label">
                                        <router-link :to="'/employer/' + employer.id + '/following'">
                                            <i class="fa fa-address-card fa-fw"></i>&nbsp;关注<span class="Tag-meta">{{employer.user_followers_count}}</span>
                                        </router-link>
                                    </span>
                                    <div class="ProfileFollowing">
                                        <div class="ListHeader">
                                            <div class="SubTabs">
                                                <div class="SubTabs-item Active-item" v-if="followType == 'follower'">
                                                    <span class="SubTab-text" v-if="me !== null && employer.id == me.id" @click="followTypeChange('follower')">关注我的</span>
                                                    <span class="SubTab-text" v-else-if="employer.gender == '男'" @click="followTypeChange('follower')">关注他的</span>
                                                    <span class="SubTab-text" v-else @click="followTypeChange('follower')">关注她的</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text" v-if="me !== null && employer.id == me.id" @click="followTypeChange('follower')">关注我的</span>
                                                    <span class="SubTab-text" v-else-if="employer.gender == '男'" @click="followTypeChange('follower')">关注他的</span>
                                                    <span class="SubTab-text" v-else @click="followTypeChange('follower')">关注她的</span>
                                                </div>
                                                <div class="SubTabs-item Active-item" v-if="followType == 'following'">
                                                    <span class="SubTab-text" v-if="me !== null && employer.id == me.id" @click="followTypeChange('following')">我关注的</span>
                                                    <span class="SubTab-text" v-else-if="employer.gender == '男'" @click="followTypeChange('following')">他关注的</span>
                                                    <span class="SubTab-text" v-else @click="followTypeChange('following')">她关注的</span>
                                                </div>
                                                <div class="SubTabs-item" v-else>
                                                    <span class="SubTab-text" v-if="me !== null && employer.id == me.id" @click="followTypeChange('following')">我关注的</span>
                                                    <span class="SubTab-text" v-else-if="employer.gender == '男'" @click="followTypeChange('following')">他关注的</span>
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
                                            <div class="FollowingLists" v-if="followingUsers.length !== 0">
                                                <div class="EmployerAction" v-if="me && me.id == employer.id">
                                                    <UserFollowList v-for="user in followingUsers" :user="user" role="employer" class="FollowList"></UserFollowList>
                                                </div>
                                                <div class="UserAction" v-else>
                                                    <UserFollowList v-for="user in followingUsers" :user="user" class="FollowList"></UserFollowList>
                                                </div>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有关注别的用户</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 12">
                                            <div class="FollowingLists" v-if="userFollowers.length !== 0">
                                                <div class="EmployerAction" v-if="me && me.id == employer.id">
                                                    <UserFollowList v-for="user in userFollowers" :user="user" role="employer" class="FollowList"></UserFollowList>
                                                </div>
                                                <div class="UserAction" v-else>
                                                    <UserFollowList v-for="user in userFollowers" :user="user" class="FollowList"></UserFollowList>
                                                </div>
                                            </div>
                                            <div class="EmptyState" v-else>
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有被别的用户关注</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 22">
                                            <div class="FollowingLists" v-if="employerFollowers.length !== 0 && followType== 'follower'">
                                                <EmployerFollowList class="FollowList" v-for="employer in employerFollowers" :employer="employer"></EmployerFollowList>
                                            </div>
                                            <div class="EmptyState" v-else-if="employerFollowers.length == 0  && followType== 'follower'">
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有被别的雇主关注</span>
                                                </div>
                                            </div>
                                            <div class="FollowingLists" v-if="followingEmployers.length !== 0 && followType== 'following'">
                                                <EmployerFollowList class="FollowList" v-for="employer in followingEmployers" :employer="employer"></EmployerFollowList>
                                            </div>
                                            <div class="EmptyState" v-else-if="followingEmployers.length == 0 && followType== 'following'">
                                                <div class="EmptyState-inner">
                                                    <i class="fa fa-users EmptyState-icon"></i>
                                                    <span>暂时还没有关注别的雇主</span>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="ListContent" v-if="followStatus == 23">
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
                                        <div class="ListContent" v-if="followStatus == 32">
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
                                    <div class="NextLevel-text" v-if="me !== null && employer.id == me.id">(距下级差{{sumExp.nextExp}})</div>
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
                                <div class="Achievement-text">获得&nbsp;{{employer.employer_exp}}&nbsp;点经验值</div>
                            </div>
                            <div class="AchievementItem">
                                <div class="Achievement-icon"><i class="fa fa-heart fa-fw"></i></div>
                                <div class="Achievement-text">获得&nbsp;{{employer.thanks_count}}&nbsp;次感谢</div>
                            </div>
                        </div>
                    </el-card>
                    <KeywordCard role="employer" :employer="employer" style="margin-top: 10px"></KeywordCard>
                </el-col>
            </div>
        </div>
        <CornerButtons></CornerButtons>
        <LoginDialog :show.sync ="loginDialogShow"></LoginDialog>
        <ChatDialog :show.sync ="chatDialogShow" :to-user="employer" v-if="employer"></ChatDialog>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import FeedItem from '../../components/common/FeedItem.vue';
    import WorkList from '../../components/common/WorkList.vue';
    import ReviewList from '../../components/employer/ReviewList.vue';
    import UserFollowList from '../../components/common/Follow/UserFollowList.vue';
    import EmployerFollowList from '../../components/common/Follow/EmployerFollowList.vue';
    import CompanyFollowList from '../../components/common/Follow/CompanyFollowList.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import LoginDialog from '../../components/common/Dialog/LoginDialog.vue';
    import ChatDialog from '../../components/common/Dialog/ChatDialog.vue';
    import KeywordCard from '../../components/common/Card/KeywordCard.vue';
    import axios from 'axios';
    import {sumExp} from '../../util/format';

    export default {
        name:'Profile',
        components:{Navbar,CornerButtons,FeedItem,WorkList,ReviewList,UserFollowList,EmployerFollowList,CompanyFollowList,LoginDialog,ChatDialog,KeywordCard},
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
                activeWorkButton: '全部',
                activeReviewButton: '全部',
                works: {
                    current_page:1,
                    data:[],
                    next_page_url:'',
                },
                feeds: {
                    current_page:1,
                    data:[],
                    next_page_url:''
                },
                reviews: [],
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
                    treat_star: {
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
                    pay_speed:{
                        total_star:0,
                        one_star_percent: 0,
                        two_star_percent: 0,
                        three_star_percent: 0,
                        four_star_percent: 0,
                        five_star_percent: 0
                    }
                },
                company: {},
                userFollowers: [],
                employerFollowers: [],
                companyFollowers: [],
                followingUsers: [],
                followingCompanys: [],
                followingEmployers: [],
                followType:'follower',
                activeFollowButton:'用户',
                followStatus: 12,
                favouriteWorks: [],
                employer: null,
                isFollowing:false,
                loginDialogShow:false,
                chatDialogShow:false,
                sumExp:null
            }
        },
        computed:{
            cantChat:function () {
                if (this.me != null && this.me.id == this.employer.id) {
                    return true;
                }
            }
        },
        methods: {
            getEmployer:function () {
                return new Promise( (resolve,reject) => {
                    let that = this;
                    axios.get('/api/employers/'+ this.$route.params.id).then(function (response) {
                        if (response.data.status ==1) {
                            resolve(response.data);
                            that.employer = response.data.employer;
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
                if (tag == this.$refs.Works ) {
                    if ( (this.works.data).length == 0)  this.getEmployerWorks('全部');
                } else if (tag == this.$refs.Reviews) {
                    if ( (this.reviews).length == 0)  this.getEmployerReviews('全部');
                } else if (tag == this.$refs.Company) {
                    this.getEmployerCompany();
                } else if (tag == this.$refs.Followers) {
                    if ( (this.userFollowers).length == 0)  this.getEmployerFollower(12);
                }
            },
            getEmployerWorks: function (type) {
                let that = this;
                if (type == '全部') {
                    axios.get('/api/works/?employer_id=' + this.employer.id + '&status=非审核').then(function (response) {
                        that.works = response.data.works;
                    });
                    that.activeWorkButton = '全部';
                } else if (type == '进行中') {
                    axios.get('/api/works/?employer_id=' + this.employer.id + '&status=1').then(function (response) {
                        console.log(response.data);
                        that.works = response.data.works;
                    });
                    that.activeWorkButton = '进行中';
                } else {
                    axios.get('/api/works/?employer_id=' + this.employer.id + '&status=已结束').then(function (response) {
                        console.log(response.data);
                        that.works = response.data.works;
                    });
                    that.activeWorkButton = '已结束';
                }
            },
            loadMoreWorks: function (type) {
                let that = this;
                let page = this.works.current_page + 1;
                if (type == '全部') {
                    axios.get('/api/works/?employer_id=' + this.employer.id + '&status=非审核' + '&page=' + page).then(function (response) {
                        if (response.data.status == 1) {
                            console.log(response.data)
                            that.works.data.push(...response.data.works.data);
                            that.works.current_page = response.data.works.current_page;
                            that.works.next_page_url = response.data.works.next_page_url;
                        } else {
                            that.$message.error(response.data.msg);
                        }
                    }).catch( (err) => {
                        console.log('loadMoreWorksError',err);
                    });
                } else if (type == '进行中') {
                    axios.get('/api/works/?employer_id=' + this.employer.id + '&status=非审核' + '&page=' + page).then(function (response) {
                        if (response.data.status == 1) {
                            that.works.data.push(...response.data.works.data);
                            that.works.current_page = response.data.works.current_page;
                            that.works.next_page_url = response.data.works.next_page_url;
                        } else {
                            that.$message.error(response.data.msg);
                        }
                    }).catch( (err) => {
                        console.log('loadMoreWorksError',err);
                    });
                } else {
                    axios.get('/api/works/?employer_id=' + this.employer.id + '&status=非审核' + '&page=' + page).then(function (response) {
                        if (response.data.status == 1) {
                            that.works.data.push(...response.data.works.data);
                            that.works.current_page = response.data.works.current_page;
                            that.works.next_page_url = response.data.works.next_page_url;
                        } else {
                            that.$message.error(response.data.msg);
                        }
                    }).catch( (err) => {
                        console.log('loadMoreWorksError',err);
                    });
                }
            },
            getEmployerReviews: function (type) {
                let that = this;
                this.activeReviewButton = type;
                if (that.reviews.length == 0) {
                    axios.get('/api/employer/reviews/?employer_id=' + this.employer.id).then(function (response) {
                        that.reviews = response.data.reviews;
                        that.handleReviewSuccess(response.data.reviews);
                    });
                }
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
                    total_treat = total_treat + item.treat_star;
                    total_speed = total_speed + item.pay_speed;
                    total_match = total_match + item.description_match;
                    if (item.treat_star == 1) {
                        treat_one_star_count += 1;
                    } else if (item.treat_star == 2) {
                        treat_two_star_count += 1;
                    } else if (item.treat_star == 3) {
                        treat_three_star_count += 1;
                    } else if (item.treat_star == 4) {
                        treat_four_star_count += 1;
                    } else {
                        treat_five_star_count += 1;
                    }
                    if (item.pay_speed == 1) {
                        speed_one_star_count += 1;
                    } else if (item.pay_speed == 2) {
                        speed_two_star_count += 1;
                    } else if (item.pay_speed == 3) {
                        speed_three_star_count += 1;
                    } else if (item.pay_speed == 4) {
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
                this.stars.treat_star.total_star = Number((total_treat / reviews.length).toFixed(1));
                this.stars.pay_speed.total_star = Number((total_speed / reviews.length).toFixed(1));
                this.stars.description_match.total_star = Number((total_match / reviews.length).toFixed(1));
                this.stars.total_star = Number(((this.stars.treat_star.total_star + this.stars.pay_speed.total_star + this.stars.description_match.total_star) / 3).toFixed(1));
                this.stars.treat_star.one_star_percent = Number((treat_one_star_count/ length).toFixed(2) * 100);
                this.stars.treat_star.two_star_percent = Number(((treat_two_star_count/ length)).toFixed(2) * 100);
                this.stars.treat_star.three_star_percent = Number((treat_three_star_count/ length).toFixed(2) * 100);
                this.stars.treat_star.four_star_percent = Number((treat_four_star_count/ length).toFixed(2)* 100);
                this.stars.treat_star.five_star_percent = Number((treat_five_star_count/ length).toFixed(2)* 100);
                this.stars.pay_speed.one_star_percent = Number((speed_one_star_count/ length).toFixed(2) * 100);
                this.stars.pay_speed.two_star_percent = Number(((speed_two_star_count/ length)).toFixed(2) * 100);
                this.stars.pay_speed.three_star_percent = Number((speed_three_star_count/ length).toFixed(2) * 100);
                this.stars.pay_speed.four_star_percent = Number((speed_four_star_count/ length).toFixed(2)* 100);
                this.stars.pay_speed.five_star_percent = Number((speed_five_star_count/ length).toFixed(2)* 100);
                this.stars.description_match.one_star_percent = Number((match_one_star_count/ length).toFixed(2) * 100);
                this.stars.description_match.two_star_percent = Number(((match_two_star_count/ length)).toFixed(2) * 100);
                this.stars.description_match.three_star_percent = Number((match_three_star_count/ length).toFixed(2) * 100);
                this.stars.description_match.four_star_percent = Number((match_four_star_count/ length).toFixed(2)* 100);
                this.stars.description_match.five_star_percent = Number((match_five_star_count/ length).toFixed(2)* 100);
            },
            followTypeChange: function (type) {
                this.followType = type;
                if (type == 'following') {
                    this.followStatus = 21;
                    this.activeFollowButton = '用户';
                } else {
                    this.followStatus = 12;
                    this.activeFollowButton = '用户';
                }
            },
            followStatusChange: function (ActiveButton) {
                if (ActiveButton == '用户') {
                    this.activeFollowButton = '用户';
                    if (this.followType == 'following') {
                        this.followStatus = 21;
                        console.log(this.followStatus)
                    } else {
                        this.followStatus = 12;
                    }
                } else if (ActiveButton == '雇主') {
                    this.activeFollowButton = '雇主';
                    this.followStatus = 22;
                } else {
                    this.activeFollowButton = '公司';
                    if (this.followType == 'following') {
                        this.followStatus = 23;
                    } else {
                        this.followStatus = 32;
                    }
                }
            },
            uploadAvatarSuccess(res, file) {
                this.employer.pic_path = res.pic_path;
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
                this.employer.cover_path = res.pic_path;
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
            getEmployerFollowing:function (status) {
                let that = this;
                this.$axios.get('/api/user/followings',{
                    params:{
                        user_id: this.employer.id,
                        status: status
                    }
                }).then(function (response) {
                    if (response.data.status == 1) {
                        if (status == 21) {
                            that.followingUsers = response.data.users;
                        } else if (status ==22) {
                            that.followingEmployers = response.data.users;
                        } else {
                            that.followingCompanys= response.data.users;
                        }
                    } else {
                        that.$message.error(response.data.msg);
                    }
                })
            },
            getEmployerFollower:function (status) {
                let that = this;
                this.$axios.get('/api/user/followers',{
                    params:{
                        user_id: this.employer.id,
                        status: status
                    }
                }).then(function (response) {
                    if (response.data.status == 1) {
                        if (status == 12) {
                            that.userFollowers = response.data.users;
                        } else if (status ==22) {
                            that.employerFollowers = response.data.users;
                        } else {
                            that.companyFollowers = response.data.users;
                        }
                    } else {
                        that.$message.error(response.data.msg);
                    }
                })
            },
            getEmployerCompany:function () {
                let that = this;
                this.$axios.get('/api/company?employer_id=' + that.$route.params.id).then( res => {
                    if (res.data.status == 1) {
                        that.company = res.data.company;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            getEmployerFeeds:function () {
                let that = this;
                this.$axios.get('/api/employer/actions?employer_id=' + this.$route.params.id).then( res => {
                    if (res.data.status == 1) {
                        that.feeds = res.data.feeds;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch (err => {
                    console.log(error);
                })
            },
            loadMoreFeeds:function () {
                let that = this;
                let page = this.feeds.current_page + 1;
                this.$axios.get('/api/employer/actions?employer_id=' + this.$route.params.id + '&page=' + page).then( res => {
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
            followUser:function () {
                this.checkLogin();
                let that = this;
                if (localStorage.user) {
                    this.$axios.post('/api/user/followings',{
                        to_id: that.employer.id,
                        status: 12
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
                    this.$axios.delete('/api/user/followings/' + that.employer.id +'?status=12').then(function (response) {
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
            checkFollowStatus:function () {
                if (localStorage.user) {
                    let that = this;
                    this.$axios.get('/api/user/followingcheck?to_id=' + that.employer.id + '&status=12').then(function (response) {
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
                    if (newStatus == 21 && this.followingUsers.length == 0) {
                        this.getEmployerFollowing(newStatus);
                    } else if (newStatus == 22 && this.followingEmployers.length == 0) {
                        this.getEmployerFollowing(newStatus);
                    } else if (newStatus == 23 && this.followingCompanys.length == 0) {
                        this.getEmployerFollowing(newStatus);
                    }
                } else {
                    if (newStatus == 12 && this.userFollowers.length == 0) {
                        this.getEmployerFollower(newStatus);
                    } else if (newStatus == 22 && this.employerFollowers.length == 0) {
                        this.getEmployerFollower(newStatus);
                    } else if (newStatus == 32 && this.companyFollowers.length == 0) {
                        this.getEmployerFollower(newStatus);
                    }
                }
            }
        },
        created: function () {
            let that = this;
            this.getEmployer().then(function () {
                that.getEmployerFeeds();
                that.checkFollowStatus();
                that.sumExp = sumExp(that.employer.employer_exp);
                if (that.$route.params.activetab == 'works' ) {
                    if ( (that.works.data).length == 0)  that.getEmployerWorks('全部');
                } else if (that.$route.params.activetab == 'reviews') {
                    if ( (that.reviews).length == 0)  that.getEmployerReviews('全部');
                } else if (that.$route.params.activetab == 'company') {
                     that.getEmployerCompany();
                } else if (that.$route.params.activetab == 'following') {
                    if ( (that.userFollowers).length == 0)  that.getEmployerFollower(12);
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
        width: 550px;
    }
    .Company-main {
        position: relative;
        margin-left:10px;
        margin-bottom:10px;
        overflow: hidden;
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
    .Company-data {
        height: 63px;
        background: #fafafa;
        margin-bottom: 20px;
        overflow: hidden;
    }
    .Company-data ul {
        display: flex;
        margin-left: -1px;
        padding: 8px 0;
        font-size: 0;
        list-style: none;
    }
    .Company-data li {
        display: flex;
        display: -webkit-flex;
        flex-direction: column;
        align-items: center;
        width: 120px;
        border-left: 1px solid #ededed;

    }
    .Company-data li strong {
        font-size: 18px;
        font-weight: 100;
        color: #555;
    }
    .Company-data li span {
        line-height: 18px;
        font-size: 12px;
        color: #b7b7b7;
        margin-top:2px;
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
        margin-left:2px;
    }
    .Gold {
        color: #f9c855;
    }
    .NextLevel-text {
        display: inline-block;
        margin-left:5px;
        font-size: 14px;
        color: #8590a6;
    }
</style>