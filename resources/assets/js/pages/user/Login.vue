<template>
    <div class="container center">
        <LoginCard></LoginCard>
    </div>
</template>

<style scoped>
    .center {
        position: absolute;
        top:50%;
        left:50%;
        -webkit-transform: translate(-50%,-50%);
        -moz-transform: translate(-50%,-50%);
        -ms-transform: translate(-50%,-50%);
        -o-transform: translate(-50%,-50%);
        transform: translate(-50%,-50%);
    }
</style>

<script>
    import LoginCard from '../../components/user/LoginCard.vue';
    export default {
        name:'LoginPage',
        components:{LoginCard},
        data() {
            return {

            }
        }
    }
</script>