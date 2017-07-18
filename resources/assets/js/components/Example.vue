<template>
    <div class="container">

        <p>
            <router-link to="/example">Go to example</router-link>
            <router-link to="/app">Go to home</router-link>
        </p>
        <div class="row">
            <div class="col-md-8 col-md-offset-2">
                <div class="panel panel-default">
                    <div class="panel-heading">Example Component</div>

                    <div class="panel-body">
                        I'm an example component!
                    </div>
                </div>
            </div>
        </div>
        <el-button type="text" @click="dialogVisible = true">点击打开 Dialog</el-button>

        <el-dialog title="提示" v-model="dialogVisible" size="tiny">
            <span>这是一段信息</span>
            <span slot="footer" class="dialog-footer">
    <el-button @click="dialogVisible = false">取 消</el-button>
    <el-button type="primary" @click="dialogVisible = false">确 定</el-button>
  </span>
        </el-dialog>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                dialogVisible: false
            }
        },
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>
