<template>
    <div class="side-nav-group">
        <el-card class="SideNav-card">
            <ul class="side-nav">
                <li class="side-nav-li"><a href="" class="side-nav-link"><i class="fa fa-jpy fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;我的收入</a></li>
                <li class="side-nav-li"><a href="" class="side-nav-link"><i class="fa fa-heart fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;我的收藏</a></li>
                <li class="side-nav-li"><a href="" class="side-nav-link"><i class="fa fa-users fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;我关注的人</a></li>
                <li class="side-nav-li"><a href="" class="side-nav-link"><i class="fa fa-briefcase fa-fw" aria-hidden="true"></i>&nbsp;&nbsp;邀请我做的兼职</a></li>
            </ul>
        </el-card>
    </div>
</template>

<script>
    export default {
        name:'SideNav',
        data() {
            return {

            }
        }
    }
</script>

<style scoped>
    .side-nav-group {
        margin-bottom: 10px;
    }
    .SideNav-card {
        position: fixed;
        width:403px;
    }
    .side-nav {
        margin:0px 0px 0px -30px;
    }
    .side-nav-li {
        position: relative;
        display: block;
        margin-bottom: 3px;
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
</style>