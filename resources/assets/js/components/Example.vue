<template>
    <LoginDialog :login-show="true"></LoginDialog>
</template>

<script>
    import LoginDialog from './common/Dialog/LoginDialog.vue';
    export default {
        name:'test',
        components: {LoginDialog}
    }
</script>

<style scoped>

</style>