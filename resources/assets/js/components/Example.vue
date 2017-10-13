<template>
    <h1>hahahah</h1>
</template>

<script>
    import LoginDialog from './common/Dialog/LoginDialog.vue';
    export default {
        name:'test',
        components: {LoginDialog},
        data() {
            return {
                user:{
                    id:1
                }
            }
        },
        channel:'user.1',
        echo: {
//            App\Events\User\ApplyWork
            'ApplyPasssed': (payload,vm) => {
                console.log(payload,vm)
            }
        }
    }
</script>

<style scoped>

</style>