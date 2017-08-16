<template>
    <div class="container">
        <div class="Navbar">
            <Navbar></Navbar>
        </div>
        <div class="Main">
            <el-col :span="16" class="left-panel">

            </el-col>
            <el-col :span="8" class="right-panel">

            </el-col>
        </div>
        <CornerButtons></CornerButtons>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    export default {
        name:'Profile',
        components:{Navbar,CornerButtons},
        data() {
            return {

            }
        }
    }
</script>

<style scoped>

</style>