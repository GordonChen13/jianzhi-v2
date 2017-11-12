<template>
    <el-card class="box-card">
        <el-tabs v-model="activeName">
            <el-tab-pane label="登录" name="login">
                <el-alert v-if="showAlert" title="" type="error">{{msg}}
                </el-alert>
                <el-form :model="loginForm" :rules="rules" ref="loginForm" label-width="100px" class="loginForm">
                    <el-form-item label="邮箱" prop="email">
                        <el-input v-model="loginForm.email"></el-input>
                    </el-form-item>
                    <el-form-item label="密码" prop="password">
                        <el-input type="password" v-model="loginForm.password"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="login(loginForm)">登录</el-button>
                        <el-button @click="resetForm('loginForm')">重置</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
            <el-tab-pane label="注册" name="signup">
                <el-alert v-if="showAlert" title="" type="error">{{msg}}
                </el-alert>
                <el-form :model="signupForm" :rules="rules" ref="signupForm" label-width="100px" class="signupForm">
                    <el-form-item label="邮箱" prop="email">
                        <el-input v-model="signupForm.email"></el-input>
                    </el-form-item>
                    <el-form-item label="昵称" prop="name">
                        <el-input v-model="signupForm.name"></el-input>
                    </el-form-item>
                    <el-form-item label="密码" prop="password">
                        <el-input type="password" v-model="signupForm.password"></el-input>
                    </el-form-item>
                    <el-form-item label="确认密码" prop="conformPassword">
                        <el-input type="password" v-model="signupForm.conformPassword"></el-input>
                    </el-form-item>
                    <el-form-item>
                        <el-button type="primary" @click="signup(signupForm)">注册</el-button>
                        <el-button @click="resetForm('signupForm')">重置</el-button>
                    </el-form-item>
                </el-form>
            </el-tab-pane>
        </el-tabs>
    </el-card>
</template>

<style scoped>
    .box-card {
        /*width: 400px;*/
    }
    .loginForm {
        margin-top: 30px;
        margin-right: 50px;
    }
    .signupForm {
        margin-right:50px;
        margin-top:30px;
    }
</style>

<script>
    import axios from 'axios';
    import { mapState,mapActions } from 'vuex';
    export default {
        name:'LoginCard',
        data() {
            return {
                activeName: 'login',
                loginForm:{
                    email:'',
                    password:''
                },
                signupForm:{
                    email:'',
                    password:'',
                    name:'',
                    conformPassword:''
                },
                rules: {
                    email: [
                        {required: true, message: '请输入邮箱', trigger: 'blur'},
                        {type:'email', message: '请输入正确的邮箱', trigger: 'blur'}
                    ],
                    name: [
                        {required: true, message: '请输入昵称', trigger: 'blur'},
                        {min: 2, message: '昵称不能少于两个字', trigger: 'blur'}
                    ],
                    password: [
                        {required: true, message: '请输入密码', trigger: 'blur'},
                        {min: 6, message: '密码太简单，请输入多于6位数的密码', trigger: 'blur'}
                    ],
                    confirmPassword: [
                        {required: true, message: '请输入密码', trigger: 'blur'},
                        {min: 6, message: '密码太简单，请输入多于6位数的密码', trigger: 'blur'}
                    ]
                }
            };
        },
        computed: mapState({
            showAlert: state => state.user.showAlert,
            msg: state => state.user.msg,
        }),
        methods: {
            login(loginForm){
                let that = this;
                this.$store.dispatch('login',loginForm).then(function (data) {
                    if (that.$route.name == 'Login') {
                        that.$router.push({name:'Home'});
                    }
                    axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
                    that.$axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
                });
            },
            signup(signupForm){
                let that = this;
                this.$store.dispatch('signup',signupForm).then(function (data) {
                    if (that.$route.name == 'Login') {
                        that.$message.success('注册成功，请先完善个人信息');
                        that.$router.push({name:'userEdit',params:{id:data.user.id}});
                    }
                    axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
                    that.$axios.defaults.headers.common['Authorization'] = 'Bearer' + data.token;
                });
            },
            resetForm(formName) {
                this.$refs[formName].resetFields();
            }
        }
    };
</script>