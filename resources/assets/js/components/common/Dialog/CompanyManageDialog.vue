<template>
    <el-dialog title="公司管理" :visible="show" :before-close="handleClose">
        <el-tabs v-model="activeTab" @tab-click="handleTabChange">
            <el-tab-pane label="成员管理" name="member" ref="member">
                <div class="MembersLists">
                    <MemberList v-for="member in company.members" :employer="member" :title="member.pivot.title" action="manage" :company="company" v-on:removeMember="removeCompanyMember"></MemberList>
                </div>
            </el-tab-pane>
            <el-tab-pane name="apply" ref="apply">
                <span slot="label">添加成员</span>
                <div class="PaneContent">
                    <el-input placeholder="输入用户昵称进行搜索" icon="search" v-model="query" class="SearchInput"></el-input>
                    <div class="MembersLists" v-if="searchResult.length > 0">
                        <MemberList v-for="member in searchResult" :employer="member" action="join" :company="company" v-on:addMember="addCompanyMember"></MemberList>
                    </div>
                    <div class="NoContent" v-else>
                        <div class="EmptyState">
                            <div class="EmptyState-inner">
                                <i class="fa fa-users EmptyState-icon"></i>
                                <span>找不到该用户</span>
                            </div>
                        </div>
                    </div>
                </div>
            </el-tab-pane>
            <el-tab-pane label="信息修改" name="info" ref="info">
                <div class="ProfileHeader-main">
                    <div class="ProfileHeader-avatar">
                        <img :src="'/storage/' + company.logo_path" alt="公司Logo" width="100px" height="100px">
                        <div class="EmployerAvatar-mask">
                            <div class="Mask-mask MaskInner">
                                <el-upload class="avatar-uploader" action="/api/company/logo" :show-file-list="false"  :data="{company_id:company.id}"
                                           :on-success="uploadAvatarSuccess" :before-upload="beforeAvatarUpload"
                                           :headers="uploadConfig.headers" name="logo" accept=".jpg,.png" >
                                    <img v-if="avatarUrl"  :src="'/storage/' + company.logo_path" class="avatar">
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
                                <span class="ProfileHeader-name">{{company.name}}</span>
                            </h1>
                        </div>
                        <div class="ProfileEdit-fields">
                            <div class="Field">
                                <div class="Field-label">公司名称</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.nameShow">
                                        <div class="Field-text">{{company.name}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.nameShow = !formShow.nameShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newCompany.name" placeholder="请在这里输入公司名称"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('name',newCompany.name)">保存</el-button>
                                            <el-button @click="cancelChange('name')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">公司所在城市</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.cityShow">
                                        <div class="Field-text">{{company.city}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.cityShow = !formShow.cityShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newCompany.city" placeholder="请在这里输入公司所在城市"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('city',newCompany.city)">保存</el-button>
                                            <el-button @click="cancelChange('city')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">所在行业</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.industryShow">
                                        <div class="Field-text">{{company.industry}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.industryShow = !formShow.industryShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newCompany.industry" placeholder="请在这里输入公司擅长的兼职类型"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('industry',newCompany.industry)">保存</el-button>
                                            <el-button @click="cancelChange('industry')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">公司规模</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.sizeShow">
                                        <div class="Field-text">{{company.size}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.sizeShow = !formShow.sizeShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newCompany.size" placeholder="请在这里输入公司规模"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('size',newCompany.size)">保存</el-button>
                                            <el-button @click="cancelChange('size')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">一句话介绍</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.sloganShow">
                                        <div class="Field-text">{{company.slogan}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.sloganShow = !formShow.sloganShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input v-model="newCompany.slogan" placeholder="请在这里输入公司的一句话简介"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('slogan',newCompany.slogan)">保存</el-button>
                                            <el-button @click="cancelChange('slogan')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">公司介绍</div>
                                <div class="Field-content">
                                    <div class="Field-contentInner" v-if="!formShow.introShow">
                                        <div class="Field-text">{{company.intro}}</div>
                                        <el-button class="ModifyButton Field-modify" type="text" @click="formShow.introShow = !formShow.introShow"><i class="fa fa-pencil"></i>修改</el-button>
                                    </div>
                                    <div class="FieldContent-input" v-else>
                                        <div class="Input-group">
                                            <el-input type="textarea" v-model="newCompany.intro" placeholder="请在这里输入公司介绍"></el-input>
                                        </div>
                                        <div class="ButtonGroup">
                                            <el-button type="primary" @click="updateProfile('intro',newCompany.intro)">保存</el-button>
                                            <el-button @click="cancelChange('intro')">取消</el-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="Field">
                                <div class="Field-label">公司照片</div>
                                <div class="Field-content">
                                    <el-upload ref="pictureUpload" action="/api/company/picture" list-type="picture-card" :on-preview="handlePictureCardPreview"
                                               :headers="uploadConfig.headers" name="picture" :data="{company_id:company.id}"
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
            <el-tab-pane label="公司认证" name="certificate" ref="certificate">
                <div class="ProfileHeader-content">
                    <div class="ProfileEdit-fields">
                        <div class="Field">
                            <div class="Field-label">上传营业执照</div>
                            <div class="Field-content">
                                <div class="ProfileHeader-avatar" style="position: relative;">
                                    <img :src="'/storage/' + company.licence_path" alt="公司Logo" width="100px" height="100px" v-if="company.licence_path">
                                    <div class="WhiteBlock" v-else>
                                        <div class="MaskContent">
                                            <i class="fa fa-camera licence-uploader-icon"></i>
                                        </div>
                                    </div>
                                    <div class="EmployerAvatar-mask">
                                        <div class="Mask-mask MaskInner">
                                            <el-upload class="licence-uploader" action="/api/company/licence" :show-file-list="false"  :data="{company_id:company.id}"
                                                       :on-success="uploadLicenceSuccess" :before-upload="beforeLicenceUpload"
                                                       :headers="uploadConfig.headers" name="licence" accept=".jpg,.png" >
                                                <img v-if="licenceUrl"  :src="'/storage/' + company.licence_path" class="licence">
                                                <div class="MaskContent" v-else>
                                                    <i class="fa fa-camera licence-uploader-icon"></i>
                                                </div>
                                            </el-upload>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="Field" v-if="company.licence_path">
                            <div class="Field-label">认证结果</div>
                            <div class="Field-content">
                                <div class="Field-contentInner">
                                    <div class="Field-text" v-if="company.certificated == 1">已通过审核</div>
                                    <div class="Field-text" v-else-if="company.certificated == 0">正在审核中</div>
                                    <div class="Field-text" v-else-if="company.certificated == -1">审核未通过</div>
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
    import MemberList from '../CompanyMemberList.vue';
    import _ from 'lodash';
    export default {
        name:'CompanyManageDialog',
        props:{
            company:{
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
                newCompany:{
                    name:'',
                    slogan:'',
                    industry:'',
                    size:'',
                    city:'',
                    intro:''
                },
                formShow: {
                    nameShow:false,
                    sloganShow:false,
                    cityShow:false,
                    introShow:false,
                    sizeShow:false,
                    industryShow:false
                },
                avatarUrl:null,
                licenceUrl:null,
                dialogImageUrl:null,
                pictureDialogVisible:false,
                searchResult:[],
                query:''
            }
        },
        computed:{
            fileList:function () {
                if (this.company.picture_path.length > 0) {
                    return this.company.picture_path.map(function (item,index) {
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
            removeCompanyMember:function (member) {
                this.$emit('removeMember',member);
            },
            addCompanyMember:function (member) {
                this.$emit('addMember',member);
            },
            uploadAvatarSuccess(res, file) {
                this.company.logo_path = res.logo_path;
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
            uploadLicenceSuccess(res, file) {
                this.company.licence_path = res.licence_path;
                this.$message.success('上传成功');
            },
            beforeLicenceUpload(file) {
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
                this.$axios.put('/api/company/' + this.company.id, {
                    key: key,
                    value: value
                }).then(function (response) {
                    if (response.data.status == 0) {
                        that.$message.error(response.data.msg);
                    } else {
                        that.company[key] = value;
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
                this.$axios.delete('/api/company/' + this.company.id + '/picture?delete_path=' + path).then( res => {
                    if (res.data.status = 1) {
                        that.$message.success('已成功删除');
                        that.company.picture_path.splice(file.name,1);
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log('deleteCompanyPictrueError',err);
                })
            },
            handleUploadSuccess:function (response, file, fileList) {
                if (fileList.indexOf(file) == fileList.length -1) {
                    this.$message.success('图片上传成功');
                }
                this.company.picture_path.push(response.picture_path);
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
            updateCompanyPicture:function () {
                this.$refs.pictureUpload.submit();
            },
            getSearchEmployers:function(query) {
                let that = this;
                if (localStorage.user) {
                    this.$axios.get('/api/employers', {
                        params: {
                            search: query
                        }
                    }).then(response => {
                        return new Promise((resolve, reject) => {
                            if (response.data.status == 1) {
                                resolve(response.data);
                                that.searchResult = response.data.employers;
                                console.log(that.searchResult)
                            } else {
                                reject(response.data);
                                that.$message.error(response.data.msg);
                            }
                        })
                    })
                }
            }
        },
        watch:{
            query:_.debounce(function (newVal) {
                this.getSearchEmployers(newVal);
            },1000)
        },
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
    .SearchInput {
        width:92%;
        margin-left:20px;
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
    .WhiteBlock {
        width: 100px;
        height: 100px;
        background-color: rgba(116, 138, 162, 0.22);
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
    .licence {
        width: 250px;
        height: 250px;
        display: block;
    }
    .licence-uploader-icon {
        font-size: 28px;
        color: #8c939d;
        width: 100px;
        height: 100px;
        line-height: 100px;
        text-align: center;
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