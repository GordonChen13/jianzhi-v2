<template>
    <div class="NotificationList" v-if="['申请了兼职','提出了新的咨询','对评价做出了回应','评价了兼职','取消了申请'].indexOf(notification.type) !==-1">
        <router-link :to="{name:'user',params:{id:notification.actionFrom.id}}">
            {{notification.actionFrom.name}}
        </router-link>
        <span class="Action">{{notification.type}}</span>
        <router-link :to="{name:'work',params:{id:notification.actionTo.id}}">{{notification.actionTo.title}}</router-link>
    </div>
    <div class="NotificationList" v-else-if="['取消关注你了','关注了你'].indexOf(notification.type) !==-1">
        <router-link :to="{name:'user',params:{id:notification.actionFrom.id}}">
            {{notification.actionFrom.name}}
        </router-link>
        <span class="Action">{{notification.type}}</span>
    </div>
    <div class="NotificationList" v-else>
        {{notification.actionFrom.name}}--{{notification.type}}--{{notification.actionTo.title}}
    </div>
</template>

<script>
    export default {
        name:'EmployerNotificationList',
        props:['notification'],
        created:function () {
            console.log(this.notification)
        }
    }
</script>

<style scoped>
    .Action {
        margin-left: 5px;
        margin-right:5px;
    }
    .NotificationList {
        position: relative;
        padding: 15px;
        font-size: 15px;
        line-height: 24px;
        color: #262626;
    }
    .NotificationList:after {
        position: absolute;
        right: 0;
        bottom: 100%;
        left: 0;
        height: 1px;
        padding: 0 16px;
        background-color: #ebeef5;
        content: "";
        background-clip: content-box;
    }
    .NotificationList  a {
        color: #3e7ac2;
    }
</style>