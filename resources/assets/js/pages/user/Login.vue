<template>
    <div class="container" id="particles">
        <LoginCard class="center"></LoginCard>
    </div>
</template>

<script>
    import LoginCard from '../../components/user/LoginCard.vue';
    import particles from 'particles.js';
    export default {
        name:'LoginPage',
        components:{LoginCard},
        data() {
            return {

            }
        },
        methods:{

        },
        mounted:function () {
            particlesJS.load('particles','/js/particles.json');
        }
    }
</script>

<style scoped>
    .container {

    }
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
    #particles{
        position: absolute;
        width: 100%;
        height: 100%;
        background-image: url("/images/login-background.jpg");
        background-repeat: no-repeat;
        background-size: cover;
        background-position: 50% 50%;
    }
</style>