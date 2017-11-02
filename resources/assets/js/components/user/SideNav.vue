<template>
    <div class="side-nav-group">
        <el-card class="SideNav-card">
            <ul class="side-nav">
                <li class="side-nav-li">
                    <router-link :to="'/user/' + user.id + '/activities'" class="side-nav-link">
                        <i class="fa fa-rss-square fa-fw"></i>&nbsp;&nbsp;我的动态
                    </router-link>
                </li>
                <li class="side-nav-li">
                    <router-link :to="'/user/' + user.id + '/works'"  class="side-nav-link">
                        <i class="fa fa-briefcase fa-fw"></i>&nbsp;&nbsp;我的兼职<span class="Tag-meta">{{user.works_count}}</span>
                    </router-link>
                </li>
                <li class="side-nav-li">
                    <router-link :to="'/user/' + user.id + '/reviews'" class="side-nav-link">
                        <i class="fa fa-star-half-o fa-fw"></i>&nbsp;&nbsp;我的评价<span class="Tag-meta">{{user.reviews_count}}</span>
                    </router-link>
                </li>
                <li class="side-nav-li">
                    <router-link :to="'/user/' + user.id + '/team'" class="side-nav-link">
                        <i class="fa fa-users fa-fw"></i>&nbsp;&nbsp;我的团队
                    </router-link>
                </li>
                <li class="side-nav-li">
                    <router-link :to="'/user/' + user.id + '/following'" class="side-nav-link">
                        <i class="fa fa-address-card fa-fw"></i>&nbsp;&nbsp;我的粉丝<span class="Tag-meta">{{user.user_followers_count}}</span>
                    </router-link>
                </li>
                <li class="side-nav-li">
                    <router-link :to="'/user/' + user.id + '/favourites'" class="side-nav-link">
                        <i class="fa fa-heart fa-fw"></i>&nbsp;&nbsp;我的收藏<span class="Tag-meta">{{user.favorite_works_count}}</span>
                    </router-link>
                </li>
            </ul>
        </el-card>
    </div>
</template>

<script>
    export default {
        name:'SideNav',
        data() {
            return {
                user: localStorage.user? JSON.parse(localStorage.user) : null,
            }
        }
    }
</script>

<style scoped>
    .side-nav-group {
        margin-bottom: 10px;
    }
    .side-nav {
        margin:0px 0px 0px -30px;
    }
    .side-nav-li {
        position: relative;
        display: block;
        margin-bottom: 10px;
        list-style: none;
    }
    .side-nav-link {
        position: relative;
        padding: 0 0 0 10px;
        display: block;
        height: 28px;
        line-height: 28px;
        color: #666;
        text-decoration: none;
    }
    .side-nav-link:hover {
        color: #259;
        background: #eff6fa;
        border-radius: 3px;
        text-decoration: none;
    }
    .Tag-meta {
        margin-left: 6px;
        font-size: 14px;
        font-weight: 400;
        line-height: 15px;
        padding: 6px 10px;
        border-radius: 2px;
        background: #f7f8fa;
        color: #8590a6;
        position: absolute;
        right: 15px;
    }
</style>