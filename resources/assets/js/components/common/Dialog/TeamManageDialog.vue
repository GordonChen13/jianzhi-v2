<template>
    <el-dialog title="团队管理" :visible="show" :before-close="handleClose">
        <el-tabs v-model="activeTab" @tab-click="handleTabChange">
            <el-tab-pane label="成员管理" name="member" ref="member">
                <div class="MembersLists">
                    <MemberList v-for="member in team.members" :user="member" :title="member.pivot.title" action="manage" :team="team" v-on:removeMember="removeTeamMember"></MemberList>
                </div>
            </el-tab-pane>
            <el-tab-pane name="apply" ref="apply">
                <span slot="label">申请管理<el-badge :value="team.un_check_applicants.length" style="top:-10px"></el-badge></span>
                <div class="PaneContent" v-if="team.un_check_applicants.length > 0">
                    <div class="MembersLists">
                        <MemberList v-for="member in team.un_check_applicants" :user="member" action="decide" :team="team" v-on:addMember="addTeamMember"></MemberList>
                    </div>
                </div>
                <div class="NoContent" v-else>
                    <div class="EmptyState">
                        <div class="EmptyState-inner">
                            <i class="fa fa-users EmptyState-icon"></i>
                            <span>暂时还没有新的申请</span>
                        </div>
                    </div>
                </div>
            </el-tab-pane>
            <el-tab-pane label="信息修改" name="info" ref="info">
                <div class="ProfileHeader-main">
                    <div class="ProfileHeader-avatar">
                        <img :src="'/storage/' + team.logo_path" alt="团队Logo" width="100px" height="100px">
                        <div class="EmployerAvatar-mask">
                            <div class="Mask-mask MaskInner">
                                <el-upload class="avatar-uploader" action="/api/team/logo" :show-file-list="false"  :data="{team_id:team.id}"
                                           :on-success="uploadAvatarSuccess" :before-upload="beforeAvatarUpload"
                                           :headers="uploadConfig.headers" name="logo" accept=".jpg,.png" >
                                    <img v-if="avatarUrl"  :src="'/storage/' + team.logo_path" class="avatar">
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
                                <span class="ProfileHeader-name">{{team.name}}</span>
                            </h1>
                        </div>
                        <div class="ProfileEdit-fields">
                            <div class="Field">
                                <div class="Field-label">团队名称</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.nameShow">
                                        <div class="Field-text">{{team.name}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.nameShow = !formShow.nameShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newTeam.name" placeholder="请在这里输入团队名称"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('name',newTeam.name)">保存</el-button>
                                            <el-button @click="cancelChange('name')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">团队所在城市</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.cityShow">
                                        <div class="Field-text">{{team.city}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.cityShow = !formShow.cityShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newTeam.city" placeholder="请在这里输入团队所在城市"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('city',newTeam.city)">保存</el-button>
                                            <el-button @click="cancelChange('city')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">擅长类型</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.industryShow">
                                        <div class="Field-text">{{team.industry}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.industryShow = !formShow.industryShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newTeam.industry" placeholder="请在这里输入团队擅长的兼职类型"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('industry',newTeam.industry)">保存</el-button>
                                            <el-button @click="cancelChange('industry')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">一句话介绍</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.sloganShow">
                                        <div class="Field-text">{{team.slogan}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.sloganShow = !formShow.sloganShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newTeam.slogan" placeholder="请在这里输入团队的一句话简介"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('slogan',newTeam.slogan)">保存</el-button>
                                            <el-button @click="cancelChange('slogan')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">团队介绍</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.introShow">
                                        <div class="Field-text">{{team.intro}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.introShow = !formShow.introShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input type="textarea" v-model="newTeam.intro" placeholder="请在这里输入团队介绍"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('intro',newTeam.intro)">保存</el-button>
                                            <el-button @click="cancelChange('intro')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">团队照片</div>
                                <div class="Field-content">
                                    <el-upload ref="pictureUpload" action="/api/team/picture" list-type="picture-card" :on-preview="handlePictureCardPreview"
                                               :headers="uploadConfig.headers" name="picture" :data="{team_id:team.id}"
                                               :multiple="true" :on-success="handleUploadSuccess" :on-error="handleUploadError" accept=".jpg,.png"
                                               :before-upload="beforePictureUpload":on-remove="handlePictureRemove" :file-list="fileList">
                                        <i class="el-icon-plus" style="margin-top:60px;"></i>
                                    </el-upload>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </el-tab-pane>
        </el-tabs>
        <el-dialog v-model="pictureDialogVisible" :modal="false" size="large">
            <img width="100%" :src="dialogImageUrl" alt="">
        </el-dialog>
    </el-dialog>
</template>

<script>
    import MemberList from '../TeamMemberList.vue';
    export default {
        name:'TeamManageDialog',
        props:{
            team:{
                type:Object,
                required:true
            },
            show:{
                type:Boolean,
            }

        },
        components: {MemberList},
        data(){
            return {
                activeTab:'member',
                uploadConfig:{
                    headers:{
                        Authorization: 'bearer' + localStorage.token
                    }
                },
                newTeam:{
                    name:'',
                    city:'',
                    slogan:'',
                    intro:'',
                    industry:''
                },
                formShow: {
                    nameShow:false,
                    sloganShow:false,
                    cityShow:false,
                    introShow:false,
                    industryShow:false
                },
                avatarUrl:null,
                dialogImageUrl:null,
                pictureDialogVisible:false
            }
        },
        computed:{
            fileList:function () {
                if (this.team.picture_path) {
                    return this.team.picture_path.map(function (item,index) {
                        let pic={};
                        pic.name = index;
                        pic.url = "/storage/" + item;
                        return pic;
                    })
                } else {
                    return [];
                }

            }
        },
        methods:{
            handleTabChange:function () {
                
            },
            handleClose() {
                this.$emit('update:show', false);
            },
            removeTeamMember:function (member) {
                this.$emit('removeMember',member);
            },
            addTeamMember:function (member) {
                this.$emit('addMember',member);
            },
            uploadAvatarSuccess(res, file) {
                this.team.logo_path = res.logo_path;
                this.$message.success('更换logo成功');
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
            updateProfile(key,value) {
                let fieldShow = key + 'Show';
                let that =this;
                this.$axios.put('/api/team/' + this.team.id, {
                    key: key,
                    value: value
                }).then(function (response) {
                    if (response.data.status == 0) {
                        that.$message.error(response.data.msg);
                    } else {
                        that.team[key] = value;
                        that.formShow[fieldShow] = !that.formShow[fieldShow];
                        console.log(response.data);
                    }
                })
            },
            cancelChange(key) {
                let fieldShow = key + 'Show';
                this.formShow[fieldShow] = !this.formShow[fieldShow];
            },
            handlePictureCardPreview:function(file) {
                this.dialogImageUrl = file.url;
                this.pictureDialogVisible = true;
            },
            handlePictureRemove:function (file,fileList) {
                let that = this;
                let path = file.url.substring(9);
                this.$axios.delete('/api/team/' + this.team.id + '/picture?delete_path=' + path).then( res => {
                    if (res.data.status = 1) {
                        that.$message.success('已成功删除');
                        that.team.picture_path.splice(file.name,1);
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log('deleteTeamPictrueError',err);
                })
            },
            handleUploadSuccess:function (response, file, fileList) {
                if (fileList.indexOf(file) == fileList.length -1) {
                    this.$message.success('图片上传成功');
                }
                this.team.picture_path.push(response.picture_path);
            },
            handleUploadError:function (error, file, fileList) {
                this.$messgae.error(error.msg);
            },
            beforePictureUpload:function (file) {
                const isJPG = file.type === 'image/jpeg';
                const isPNG = file.type === 'image/png';
                const isLt2M = file.size / 1024 / 1024 < 2;
                if (isJPG + isPNG == 0) {
                    this.$message.error('上传头像图片只能是 JPG 或者PNG 格式!');
                }
                if (!isLt2M) {
                    this.$message.error('上传头像图片大小不能超过 2MB!');
                }
                return (isJPG || isPNG) && isLt2M;
            },
            updateTeamPicture:function () {
                this.$refs.pictureUpload.submit();
            }
        },
        created:function () {
            console.log(this.team,this.show)
        }
    }
</script>

<style scoped>

    .EmptyState {
        padding: 70px 0;
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-align: center;
        -ms-flex-align: center;
        align-items: center;
        -webkit-box-pack: center;
        -ms-flex-pack: center;
        justify-content: center;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        height: 30%;
    }
    .EmptyState-inner {
        font-size: 15px;
        color: #8590a6;
        text-align: center;
    }
    .EmptyState-icon {
        display: block;
        margin: 0 auto;
        font-size: 80px;
        padding-bottom:30px;
        color: rgba(133, 144, 166, 0.3);
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
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
    }
    .avatar {
        width: 178px;
        height: 178px;
        display: block;
    }
    .ProfileHeader-content {
        padding-left: 20px;
        border-left: 104px solid transparent;
    }
    .ProfileHeader-contentHead {
        position: relative;
        padding-right: 106px;
        margin-bottom: 16px;
    }
    .ProfileEdit-fields {
        padding: 0 16px;
        margin: 14px auto;
        font-size: 15px;
        color: #262626;
    }
    .Field {
        padding: 15px 0;
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