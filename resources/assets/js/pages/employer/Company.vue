<template>
    <div class="container">
        <TopNavbar></TopNavbar>
        <LeftNavbar></LeftNavbar>
        <div class="main">
            <el-row>
                <el-col :span="18">
                    <el-card class="CompanyCard">
                        <div class="Card-title" slot="header">
                            <span>我的公司</span>
                        </div>
                        <div class="CompanyContent" v-if="company">
                            <div class="CompanyWarp">
                                <div class="Company-logo">
                                    <img :src="'/storage/' + company.logo_path" alt="logo" width="120">
                                </div>
                                <div class="Company-right">
                                    <div class="Company-main">
                                        <div class="Company-head">
                                            <router-link to="/company/1">
                                                <span class="Company-title">{{company.name}}</span>
                                            </router-link>
                                            <div class="Identification" v-if="company.certificated">
                                                <i class="fa fa-check-circle-o white"></i>&nbsp;&nbsp;已验证
                                            </div>
                                            <div class="Identification" v-else>未认证</div>
                                        </div>
                                        <div class="Company-word">
                                            {{company.slogan}}
                                        </div>
                                        <div class="Company-info">
                                            <div class="InfoItem"><i class="fa fa-industry fa-fw"></i> <span>{{company.industry}}</span></div>
                                            <div class="InfoItem"><i class="fa fa-user fa-fw"></i> <span>{{company.size}}</span></div>
                                            <div class="InfoItem"><i class="fa fa-map-marker fa-fw"></i> <span>{{company.city}}</span></div>
                                        </div>
                                    </div>
                                </div>
                                <div class="CompanyAction">
                                    <el-button type="primary" class="LongButton" @click="companyDialogShow = true" v-if="isCompanyOwner"><i class="fa fa-user-plus fa-fw white"></i>&nbsp;&nbsp;公司管理</el-button>
                                    <el-button type="danger" class="LongButton" v-else @click="leaveCompany"><i class="fa fa-user-times fa-fw white"></i>&nbsp;&nbsp;离开公司</el-button>
                                </div>
                            </div>
                            <div class="Company-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;公司成员</div>
                            <div class="MemberLists" v-if="company.members.length >0">
                                <CompanyMemberList v-for="member in company.members" :employer="member" role="employer" :title="member.pivot.title" v-on:removeMember="removeCompanyMember" v-on:addMember="addCompanyMember"></CompanyMemberList>
                            </div>
                            <div class="Company-title"><i class="fa fa-bookmark fa-fw blue"></i>&nbsp;公司介绍</div>
                            <div class="CompanyIntro">
                                <div class="Company-text">
                                    {{company.intro}}
                                </div>
                                <div class="Company-carousel" v-if="company.picture_path.length > 0">
                                    <el-carousel height="300px" type="card">
                                        <el-carousel-item v-for="path in company.picture_path"><img :src="'/storage/' + path" alt="公司图片" height="300"></el-carousel-item>
                                    </el-carousel>
                                </div>
                            </div>
                            <CompanyManageDialog :show.sync ="companyDialogShow" :company="company" v-on:removeMember="removeCompanyMember" v-on:addMember="addCompanyMember"></CompanyManageDialog>
                        </div>
                        <div class="EmptyState" v-else>
                            <div class="EmptyState-inner">
                                <i class="fa fa-briefcase EmptyState-icon"></i>
                                <span>您暂时还没有不属于公司一员</span>
                                <span>您可以<el-button type="text" @click="createCompanyShow = true">点击这里输入公司信息</el-button>，或者让你公司的负责人把你拉进去</span>
                            </div>
                        </div>
                    </el-card>
                </el-col>
                <el-col :span="6">
                    <el-row>
                        <ToDos></ToDos>
                    </el-row>
                    <el-row>
                        <NoticeBoard></NoticeBoard>
                    </el-row>
                </el-col>
            </el-row>
        </div>
        <el-dialog title="公司认证" :visible.sync="createCompanyShow" size="tiny">
            <el-form :model="newCompany" ref="newCompanyForm" :rules="newCompanyRules">
                <el-form-item label="公司名称" prop="name">
                    <el-input v-model="newCompany.name" type="text" placeholder="请在这里输入公司名称"></el-input>
                </el-form-item>
                <el-form-item label="所在城市" prop="city">
                    <el-input v-model="newCompany.city" type="text" placeholder="请在这里输入公司所在城市"></el-input>
                </el-form-item>
                <el-form-item label="人数规模" prop="size">
                    <el-input v-model="newCompany.size" type="text" placeholder="请在这里输入公司人数规模"></el-input>
                </el-form-item>
                <el-form-item label="所在行业" prop="industry">
                    <el-input v-model="newCompany.industry" type="text" placeholder="请在这里输入公司所在行业"></el-input>
                </el-form-item>
                <el-form-item label="一句话介绍" prop="slogan">
                    <el-input v-model="newCompany.slogan" type="text" placeholder="请在这里输入公司的一句话简介"></el-input>
                </el-form-item>
                <el-form-item label="公司介绍" prop="intro">
                    <el-input v-model="newCompany.intro" type="textarea" :rows="3" placeholder="请在这里输入公司介绍"></el-input>
                </el-form-item>
                <div class="ButtonGroup">
                    <el-button type="primary" @click="postNewCompany">提交</el-button>
                    <el-button @click="createCompanyShow = false">取消</el-button>
                </div>
            </el-form>
        </el-dialog>
    </div>
</template>

<script>
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import LeftNavbar from '../../components/employer/LeftNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import ToDos from '../../components/employer/ToDos.vue';
    import CompanyMemberList from '../../components/common/CompanyMemberList.vue';
    import CompanyManageDialog from '../../components/common/Dialog/CompanyManageDialog.vue';
    export default {
        name:'CompanyPage',
        components: {TopNavbar,LeftNavbar,NoticeBoard,ToDos,CompanyMemberList,CompanyManageDialog},
        data() {
            return {
                me: localStorage.user ? JSON.parse(localStorage.user) : null,
                company:null,
                isCompanyOwner: false,
                companyDialogShow:false,
                createCompanyShow:false,
                newCompany:{
                    name:'',
                    slogan:'',
                    industry:'',
                    size:'',
                    city:'',
                    intro:''
                },
                newCompanyRules: {
                    name: [
                        {required: true, message: '请输入公司名称', trigger: 'blur'},
                        {min: 3, max: 15, message: '长度在 3 到 15 个字符', trigger: 'blur'}
                    ],
                    city: [
                        {required: true, message: '请输入公司所在城市', trigger: 'blur'}
                    ]
                },
            }
        },
        methods: {
            getCompany:function () {
                let that = this;
                this.$axios.get('/api/company?employer_id=' + this.me.id,{
                    headers:{
                        'Authorization':'bearer' + localStorage.token
                    }
                }).then( response => {
                    return new Promise( (resolve,reject) => {
                        if (response.data.status ==1) {
                            resolve(response.data);
                            that.company = response.data.company;
                            console.log(that.company)
                            that.checkMember(that.company.members);
                        } else {
                            reject(response.data);
                            that.$message.error(response.data.msg);
                        }
                    })
                })
            },
            leaveCompany:function () {
                let that = this;
                this.$axios.delete('/api/company/member/' + this.me.id + '?company_id=' + this.company.id).then( res => {
                    if (res.data.status == 1) {
                        that.$message.success(res.data.msg);
                        that.isCompanyMember = false;
                        that.company = null;
                    } else {
                        that.$message.error(res.data.msg);
                    }
                }).catch( err => {
                    console.log('leaveCompanyError',err);
                })
            },
            addCompanyMember:function (member) {
                this.company.members.push(member);
            },
            removeCompanyMember:function (member) {
                let index = this.$_.findIndex(this.company.members,function (item) {
                    return item.id == member.id
                })
                this.company.members.splice(index,1);
            },
            postNewCompany:function () {
                let that = this;
                this.$axios.post('/api/company',this.newCompany).then( res => {
                    if (res.data.status == 1) {
                        that.$message.success(res.data.msg);
                        that.company = res.data.company;
                        that.checkMember(that.company.members);
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
            checkMember:function (members) {
                let that = this;
                let owner = that.$_.find(members,function (member) {
                    return member.id == that.me.id && member.pivot.title == 'owner';
                })
                if (owner !== undefined) that.isCompanyOwner = true;
            },
            postNewCompany:function () {
                let that = this;
                this.$axios.post('/api/company',this.newCompany).then( res => {
                    if (res.data.status == 1) {
                        that.$message.success(res.data.msg);
                        that.company = res.data.company;
                        that.checkMember(that.company.members);
                    } else {
                        that.$message.error(res.data.msg);
                    }
                })
            },
        },
        created:function () {
            this.getCompany();
        }
    }
</script>

<style scoped>
    .main {
        margin-top: 60px;
        position: relative;
        margin-left: 220px;
        background-color: #F5F5F6;
    }
    .CompanyCard {
        margin-top: 20px;
    }
    .Card-title {
        padding: 0px -10px;
        overflow: hidden;
        -webkit-box-flex: 1;
        -ms-flex: 1;
        flex: 1;
        font-weight: 500;
        text-overflow: ellipsis;
        white-space: nowrap;
    }
    .EmptyState {
        padding: 0px 0;
        margin-top:20px;
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
        display: flex;
        flex-direction: column;
    }
    .EmptyState-icon {
        display: block;
        margin: 0 auto;
        font-size: 80px;
        padding-bottom:30px;
        color: rgba(133, 144, 166, 0.3);
    }
    .CompanyInfo {
        margin-left:20px;
    }
    .Company-logo {
        width:120px;
    }
    .CompanyWarp {
        display: flex;
        padding-bottom: 20px;
        margin-top:20px;
        border-bottom: 1px solid #f0f2f7;
    }
    .Company-right {
        width: 550px;
    }
    .Company-main {
        position: relative;
        margin-left:10px;
        margin-bottom:10px;
        overflow: hidden;
    }
    .Company-head {
        display: flex;
    }
    .Identification {
        padding: 0px 10px;
        margin-left: 20px;
        color: #fff;
        background: #00b38a;
        border: 1px solid #00b38a;
        -webkit-border-radius: 20px;
        -moz-border-radius: 20px;
        -ms-border-radius: 20px;
        -o-border-radius: 20px;
        border-radius: 20px;
    }
    .white {
        color: white;
    }
    .Company-word {
        margin-top: 10px;
        font-size: 16px;
        clear: both;
    }
    .Company-info {
        margin-top: 10px;
        display: flex;
    }
    .InfoItem {
        margin-right:20px;
    }
    .CompanyAction {
        margin-left:auto;
        margin-top: 20px;
        margin-right: 50px;
    }
    .Company-data ul {
        display: flex;
        margin-left: -1px;
        padding: 8px 0;
        font-size: 0;
        list-style: none;
    }
    .Company-data li {
        display: flex;
        display: -webkit-flex;
        flex-direction: column;
        align-items: center;
        width: 120px;
        border-left: 1px solid #ededed;

    }
    .Company-data li strong {
        font-size: 18px;
        font-weight: 100;
        color: #555;
    }
    .Company-data li span {
        line-height: 18px;
        font-size: 12px;
        color: #b7b7b7;
        margin-top:2px;
    }
    .Icon-button {
        padding: 0px;
    }
    .Company-title {
        font-size: 16px;
        font-weight: 600;
        margin-top:10px;
        margin-bottom:10px;
    }
    .blue {
        color: #316d9e;
    }
    .CompanyIntro {
        margin-top:10px;
    }
    .Company-carousel {
        margin-top:20px;
    }
    .MemberLists {
        max-height: 340px;
        overflow-y: scroll;
        padding-bottom:10px;
        border-bottom: 1px solid #f0f2f7;
    }
</style>