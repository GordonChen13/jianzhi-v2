<template>
    <div class="NotificationList" v-if="['申请了兼职','提出了新的咨询','对评价做出了回应','评价了兼职','取消了申请'].indexOf(notification.type) !==-1">
        uw
    </div>
    <div class="NotificationList" v-else-if="['取消关注你了','关注了你'].indexOf(notification.type) !==-1">
        u
    </div>
    <div class="NotificationList" v-else>
        u
    </div>
</template>

<script>
    export default {
        name:'EmployerNotificationList',
        props:['notification']
    }
</script>

<style scoped>

</style>