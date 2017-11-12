<template>
    <div class="card">
        <el-card class="Notice-card">
            <div class="Card-title" slot="header">
                <span>公告栏</span>
            </div>
            <div class="NoticeList">
                <div class="EmptyState">
                    <div class="EmptyState-inner">
                        <i class="fa fa-newspaper-o EmptyState-icon"></i>
                        <span>暂无公告</span>
                    </div>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>
    export default {
        name: 'NoticeBoard'
    }
</script>

<style scoped>
    .card {
        margin:20px 20px 0px 20px;
    }
    .Notice-card {
        margin-top:20px;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-orient: vertical;
        -webkit-box-direction: normal;
        -ms-flex-direction: column;
        flex-direction: column;
        overflow: hidden;
        box-sizing: border-box;
    }
    .Card-title {
        padding: 0px -10px;
        overflow: hidden;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .NoticeList {
        height:200px;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow-x: hidden;
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