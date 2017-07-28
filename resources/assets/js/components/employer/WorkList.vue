<template>
    <div class="WorkItem">


        <div class="Work-title">{{work.title}}</div>
        <div class="Work-info"></div>
    </div>

</template>

<script>
    export default {
        name:'WorkList',
        prop:['work'],
        data() {
            return {

            }
        },
        created: function () {
            console.log('work',this.work)
        }
    }
</script>

<style scoped>
    .WorkItem {
        border-top: 1px solid #f0f2f7;
        position: relative;
        -ms-flex-negative: 0;
        flex-shrink: 0;
        padding: 12px 16px 10px;
        font-size: 15px;
    }
    .WorkItem:first-child {
        border-top: none;
    }
</style>