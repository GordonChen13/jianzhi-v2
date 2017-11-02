<template>
    <h1>hahahah</h1>
</template>

<script>
    import LoginDialog from './common/Dialog/LoginDialog.vue';
    import { sumExp,test } from '../util/format';
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
        created:function () {
            var result = sumExp(0);
            test();
            console.log(result);
        }
    }
</script>

<style scoped>

</style>