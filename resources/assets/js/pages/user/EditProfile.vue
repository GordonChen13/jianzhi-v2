<template>
    <div class="container">
        <div class="Navbar">
            <Navbar active-index="8"></Navbar>
        </div>
        <div class="Main">
            <div class="ProfileHeader" id="ProfileHeader">
                <el-card class="HeaderCard" v-if="employer">
                    <div class="ProfileHeader-userCover">
                        <div class="UserCoverEditor">
                            <el-upload action="/api/photos/cover" :show-file-list="false"  name="cover" accept=".jpg,.png"
                                       :on-success="handleCoverSuccess" :before-upload="beforeCoverUpload" :headers="uploadConfig.headers">
                                <el-button class="Cover-button"><i class="fa fa-pencil fa-fw"></i>更换封面图片</el-button>
                            </el-upload>
                        </div>
                        <div class="UserCover">
                            <img class="UserCover-image" width="1210" :src="'/storage/' + employer.cover_path" alt="用户封面">
                        </div>
                    </div>
                    <div class="ProfileHeader-wrapper">
                        <div class="ProfileHeader-main">
                            <div class="ProfileHeader-avatar">
                                <img :src="'/storage/' + employer.pic_path" alt="用户头像" width="160px" height="160px">
                                <div class="EmployerAvatar-mask">
                                    <div class="Mask-mask MaskInner">
                                        <el-upload class="avatar-uploader" action="/api/photos/avatar" :show-file-list="false"
                                                   :on-success="uploadAvatarSuccess" :before-upload="beforeAvatarUpload"
                                                   :headers="uploadConfig.headers" name="avatar" accept=".jpg,.png">
                                            <img v-if="avatarUrl"  :src="'/storage/' + employer.pic_path" class="avatar">
                                            <div class="MaskContent" v-else>
                                                <i class="fa fa-camera avatar-uploader-icon"></i>
                                            </div>
                                        </el-upload>
                                    </div>
                                </div>
                            </div>
                            <div class="ProfileHeader-content">
                                <div class="ProfileHeader-contentHead">
                                    <h1 class="ProfileHeader-title">
                                        <span class="ProfileHeader-name">{{employer.name}}</span>
                                    </h1>
                                    <div class="Back-action">
                                        <router-link :to="'/user/' + user.id">
                                            <el-button type="text" class="Back-button">返回我的主页&nbsp;&nbsp; <i class="fa fa-angle-right"></i></el-button>
                                        </router-link>
                                    </div>
                                </div>
                                <div class="ProfileEdit-fields">
                                    <div class="Field">
                                        <div class="Field-label">性别</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.genderShow">
                                                <div class="Field-text">{{user.gender}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.genderShow = !show.genderShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Radio-group">
                                                    <el-radio class="radio" v-model="user.gender" label="男">男</el-radio>
                                                    <el-radio class="radio" v-model="user.gender" label="女">女</el-radio>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('gender',user.gender)">保存</el-button>
                                                    <el-button @click="cancelChange('gender')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Field">
                                        <div class="Field-label">年龄</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.ageShow">
                                                <div class="Field-text">{{user.age}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.ageShow = !show.ageShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Input-group">
                                                    <el-input-number v-model="user.age" :min="1" :max="100"></el-input-number>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('age',user.age)">保存</el-button>
                                                    <el-button @click="cancelChange('age')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Field">
                                        <div class="Field-label">城市</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.cityShow">
                                                <div class="Field-text">{{user.city}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.cityShow = !show.cityShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Input-group">
                                                    <el-input v-model="user.city"></el-input>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('city',user.city)">保存</el-button>
                                                    <el-button @click="cancelChange('city')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Field">
                                        <div class="Field-label">学校</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.schoolShow">
                                                <div class="Field-text">{{user.school}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.schoolShow = !show.schoolShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Input-group">
                                                    <el-input v-model="user.school"></el-input>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('school',user.school)">保存</el-button>
                                                    <el-button @click="cancelChange('school')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Field">
                                        <div class="Field-label">专业</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.majorShow">
                                                <div class="Field-text">{{user.major}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.majorShow = !show.majorShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Input-group">
                                                    <el-input v-model="user.major"></el-input>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('major',user.major)">保存</el-button>
                                                    <el-button @click="cancelChange('major')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Field">
                                        <div class="Field-label">一句话简介</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.simple_introShow">
                                                <div class="Field-text">{{user.simple_intro}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.simple_introShow = !show.simple_introShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Input-group">
                                                    <el-input v-model="user.simple_intro"></el-input>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('simple_intro',user.simple_intro)">保存</el-button>
                                                    <el-button @click="cancelChange('simple_intro')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="Field">
                                        <div class="Field-label">自我介绍</div>
                                        <div class="Field-content">
                                            <div class="Field-contentInner" v-if="!show.introductionShow">
                                                <div class="Field-text">{{user.introduction}}</div>
                                                <el-button class="ModifyButton Field-modify" type="text" @click="show.introductionShow = !show.introductionShow"><i class="fa fa-pencil"></i>修改</el-button>
                                            </div>
                                            <div class="FieldContent-input" v-else>
                                                <div class="Input-group">
                                                    <el-input type="textarea" v-model="user.introduction"></el-input>
                                                </div>
                                                <div class="ButtonGroup">
                                                    <el-button type="primary" @click="updateProfile('introduction',user.introduction)">保存</el-button>
                                                    <el-button @click="cancelChange('introduction')">取消</el-button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </el-card>
            </div>
        </div>
        <CornerButtons></CornerButtons>
    </div>
</template>

<script>
    import Navbar from '../../components/user/Navbar.vue';
    import CornerButtons from '../../components/common/CornerButtons.vue';
    import axios from 'axios';
    export default {
        name:'Profile',
        components:{Navbar,CornerButtons},
        data() {
            return {
                avatarUrl:'',
                user: JSON.parse(localStorage.user),
                employer:null,
                uploadConfig:{
                    headers:{
                        Authorization: 'bearer' + localStorage.token
                    }
                },
                show: {
                    genderShow:false,
                    ageShow:false,
                    cityShow:false,
                    schoolShow:false,
                    majorShow:false,
                    simple_introShow:false,
                    introductionShow:false
                }
            }
        },
        methods: {
            uploadAvatarSuccess(res, file) {
                this.employer.pic_path = res.pic_path;
                this.$message.success('更换头像成功');
            },
            beforeAvatarUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            handleCoverSuccess(res, file) {
                this.employer.cover_path = res.pic_path;
                this.$message.success('更换背景成功');
            },
            beforeCoverUpload(file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;

                if (!isJPG && !isPNG) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            updateProfile(key,value) {
                let fieldShow = key + 'Show';
                let that =this;
                axios.put('/api/users/' + this.user.id, {
                    key: key,
                    value: value
                }).then(function (response) {
                    if (response.data.status == 0) {
                        this.$message.error(response.data.msg);
                    } else {
                        that.user = response.data.user;
                        localStorage.user = JSON.stringify(response.data.user);
                        that.show[fieldShow] = !that.show[fieldShow];
                        console.log(response.data);
                    }
                })
            },
            cancelChange(key) {
                let fieldShow = key + 'Show';
                console.log([this.user[key],JSON.parse(localStorage.user)[key]]);
                this.user[key] = JSON.parse(localStorage.user)[key];
                this.show[fieldShow] = !this.show[fieldShow];
            }
        },
        created: function () {
            let that = this;
            axios.get('/api/employers/'+ this.$route.params.id).then(function (response) {
                that.employer = response.data.employer;
            });
        }
    }
</script>

<style scoped>
    .Main {
        margin:71px auto;
        width: 1210px;
        height:100%;
    }
    .HeaderCard {
        margin-top:10px;
    }
    .ProfileHeader {
        margin-top:10px;
        position: relative;
        width: 100%;
    }
    .ProfileHeader-userCover {
        margin:-20px -20px 0 -20px;
    }
    .UserCoverEditor {
        position: relative;
    }
    .Cover-button {
        position: absolute;
        top: 24px;
        right: 24px;
        z-index: 1;
    }
    .UserCover {
        position: relative;
        height: 240px;
        overflow: hidden;
        background: #f7f8fa;
        border-top-right-radius: 1px;
        border-top-left-radius: 1px;
        transition: height .3s;
    }
    .UserCover-image, .UserCover-image img {
        width: 100%;
        height: 100%;
        -o-object-fit: cover;
        object-fit: cover;
    }
    .ProfileHeader-wrapper {
        position: relative;
        width: 100%;
        background: #fff;
        box-sizing: border-box;
    }
    .ProfileHeader-main {
        position: relative;
        margin: 0 20px 24px;
    }
    .ProfileHeader-avatar {
        vertical-align: top;
        display: inline-block;
        overflow: hidden;
        border: 4px solid #fff;
        border-radius: 8px;
        position: absolute;
        top: -25px;
        left: 0;
        z-index: 4;
    }
    .EmployerAvatar-mask {
        position: absolute;
        top: 0;
        right: 0;
        bottom: 0;
        opacity: 0;
        left: 0;
        z-index: 1;
        transition: opacity .2s ease-in;
    }
    .EmployerAvatar-mask:hover {
        opacity: .6;
    }
    .Mask-mask {
        position: absolute;
        z-index: -1;
        width: 100%;
        height: 100%;
        opacity: .4;
        box-sizing: border-box;
        background: #000;
    }
    .MaskInner {
        z-index: 4;
        border-radius: 8px;
    }
    .avatar-uploader .el-upload {
        border: 1px dashed #d9d9d9;
        border-radius: 6px;
        cursor: pointer;
        position: relative;
        overflow: hidden;
    }
    .avatar-uploader .el-upload:hover {
        border-color: #20a0ff;
    }
    .avatar-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 160px;
        height: 160px;
        line-height: 160px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
    .ProfileHeader-content {
        padding-top: 16px;
        padding-left: 32px;
        border-left: 164px solid transparent;
    }
    .ProfileHeader-contentHead {
        position: relative;
        padding-right: 106px;
        margin-bottom: 16px;
    }
    .ProfileEdit-fields {
        width: 1000px;
        padding: 0 16px;
        margin: 14px auto;
        font-size: 15px;
        color: #262626;
    }
    .Field {
        padding: 30px 0;
        border-bottom: 1px solid #ebeef5;
    }
    .Field-label {
        position: absolute;
        font-size: 15px;
        font-weight: 700;
        line-height: 36px;
        color: #333;
    }
    .Field-content {
        padding: 0 64px 0 140px;
        line-height: 36px;
    }
    .Field:hover .Field-modify{
        opacity: 1;
    }
    .Field-contentInner {
        display: flex;
    }
    .FieldContent-input {
        display: flex;
        flex-direction: column;
    }
    .Input-group {
        width: 400px;
    }
    .ButtonGroup {
        margin-top: 10px;
        vertical-align: center;
    }
    .Field-modify {
        opacity: 0;
        margin-left: 16px;
        line-height: inherit;
        transition: opacity .1s;
    }
    .ModifyButton {
        height: auto;
        margin-left:20px;
        padding: 0;
        line-height: inherit;
        background-color: transparent;
        border: none;
        border-radius: 0;
        font-size: 15px;
    }
    .ProfileHeader-title {
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .ProfileHeader-name {
        font-size: 26px;
        font-weight: 700;
        line-height: 30px;
    }
    .Back-action {

    }
    .Back-button {
        position: absolute;
        top: 50%;
        right: 0;
        -webkit-transform: translateY(-50%);
        transform: translateY(-50%);
    }
</style>