<template>
    <h1>hahahah</h1>
</template>

<script>
    import LoginDialog from './common/Dialog/LoginDialog.vue';
    export default {
        name:'test',
        components: {LoginDialog},
        created:function  () {
            this.$axios.get('/api/works').then(response =>{
                return new Promise((resolve,reject) => {
                    console.log(response)
                })
            });
            console.log(this.$moment().format("HH:mm"));
            console.log(this.$_.indexOf([1, 2, 1, 2], 2));
        }
    }
</script>

<style scoped>

</style>