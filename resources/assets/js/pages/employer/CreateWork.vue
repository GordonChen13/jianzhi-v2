<template>
    <div class="container">
        <TopNavbar></TopNavbar>
        <LeftNavbar></LeftNavbar>
        <div class="main">
            <el-row>
                <el-col :span="18">
                    <div class="workcard">
                        <el-card>
                            <el-steps :active="active" process-status="process" finish-status="success" class="steps" :center="true">
                                <el-step>
                                    <span slot="title" class="step" @click="active=0">兼职内容</span>
                                </el-step>
                                <el-step>
                                    <span slot="title" class="step" @click="active=1">薪资待遇</span>
                                </el-step>
                                <el-step>
                                    <span slot="title" class="step" @click="active=2">工作要求</span>
                                </el-step>
                                <el-step>
                                    <span slot="title" class="step" @click="active=3">面试信息</span>
                                </el-step>
                            </el-steps>

                            <el-form ref="form" :rules="rules" :model="work" label-width="150px" class="workform">
                                <el-alert v-if="work.showAlert" title="" type="error" style="margin: 10px 20px;">{{work.msg}}
                                </el-alert>
                                <div class="step1" v-show="active==0">
                                    <el-row>
                                        <el-col :span="18">
                                            <el-form-item label="兼职名称"  prop="title">
                                                <el-input v-model="work.title"></el-input>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="18">
                                            <el-form-item label="兼职种类" prop="tags">
                                                <el-select v-model="work.tags" multiple filterable remote placeholder="请输入关键词"
                                                           :remote-method="queryTags" :loading="tagsLoading" style="width: 100%">
                                                    <el-option v-for="tag in tags" :key="tag.id" :label="tag.display_name" :value="tag.id">
                                                    </el-option>
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="10">
                                            <el-form-item label="工作日期" prop="daterange">
                                                <el-date-picker v-model="work.daterange" type="daterange" placeholder="选择日期范围">
                                                </el-date-picker>
                                            </el-form-item>
                                        </el-col>
                                        <el-col :span="10">
                                            <el-form-item label="工作时间" prop="timerange">
                                                <el-time-picker is-range v-model="work.timerange" placeholder="选择时间范围"  :picker-options="{step: '00:15',format: 'HH:mm'}">
                                                </el-time-picker>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="18">
                                            <el-form-item label="工作地区" prop="location">
                                                <el-cascader :options="citys" v-model="work.location" expand-trigger="hover">
                                                </el-cascader>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="18">
                                            <el-form-item label="详细地址" prop="address">
                                                <el-input v-model="work.address" placeholder="请输入详细地址"></el-input>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="18">
                                            <el-form-item label="联系方式"  prop="contact">
                                                <el-input v-model="work.contact"></el-input>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-button-group style="margin-left: 40%;">
                                        <el-button type="primary" icon="arrow-left" :disabled="true" @click="active--">上一页</el-button>
                                        <el-button type="primary"  @click="active++">下一页<i class="el-icon-arrow-right el-icon--right"></i></el-button>
                                    </el-button-group>
                                </div>
                                <div class="step2" v-show="active==1">
                                    <el-row>
                                        <el-col :span="20">
                                            <el-form-item label="兼职工资" prop="pay_amount">
                                                <el-input placeholder="请输入兼职工资" v-model="work.pay_amount" style="width:60%">
                                                    <el-select v-model="work.settlement_type" slot="append" placeholder="结算方式" style="width:150px;" @change="handlePayChange">
                                                        <el-option label="元/天" value="元/天"></el-option>
                                                        <el-option label="元/小时" value="元/小时"></el-option>
                                                        <el-option label="元/次" value="元/次"></el-option>
                                                        <el-option label="面谈" value="面谈"></el-option>
                                                    </el-select>
                                                </el-input>
                                            </el-form-item>
                                            <el-form-item label="提成"  prop="has_commission">
                                                <el-switch v-model="work.has_commission" on-text="有" off-text="没有" style="margin-top: 7px;"></el-switch>
                                            </el-form-item>
                                            <el-form-item label="提成方式" prop="commission" v-if="work.has_commission">
                                                <el-input  v-model="work.commission"></el-input>
                                            </el-form-item>
                                            <el-form-item label="包工作餐">
                                                <el-checkbox-group v-model="work.lunch">
                                                    <el-checkbox label="早餐"></el-checkbox>
                                                    <el-checkbox label="午餐"></el-checkbox>
                                                    <el-checkbox label="晚餐"></el-checkbox>
                                                </el-checkbox-group>
                                            </el-form-item>
                                            <el-form-item label="工资支付方式" prop="pay_type">
                                                <el-select v-model="work.pay_type" placeholder="支付方式" style="width:150px;">
                                                    <el-option label="微信支付" value="微信支付"></el-option>
                                                    <el-option label="支付宝" value="支付宝"></el-option>
                                                    <el-option label="银行卡转账" value="银行卡转账"></el-option>
                                                    <el-option label="现金支付" value="现金支付"></el-option>
                                                </el-select>
                                            </el-form-item>
                                            <el-form-item label="工资发放时间" prop="pay_time">
                                                <el-select v-model="work.pay_time" placeholder="工资发放时间" style="width:150px;">
                                                    <el-option label="当天结算" value="当天结算"></el-option>
                                                    <el-option label="一周内结算" value="一周内结算"></el-option>
                                                    <el-option label="一月内结算" value="一月内结算"></el-option>
                                                    <el-option label="一月后结算" value="一月后结算"></el-option>
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-button-group style="margin-left: 40%;">
                                        <el-button type="primary" icon="arrow-left" @click="active--">上一页</el-button>
                                        <el-button type="primary"  @click="active++">下一页<i class="el-icon-arrow-right el-icon--right"></i></el-button>
                                    </el-button-group>
                                </div>
                                <div class="step3" v-show="active==2">
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="需要人数"  prop="need_num">
                                                <el-input v-model="work.need_num"></el-input>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-form-item label="性别要求" prop="gender">
                                        <el-select v-model="work.gender" placeholder="性别要求" style="width:150px;">
                                            <el-option label="男" value="男"></el-option>
                                            <el-option label="女" value="女"></el-option>
                                            <el-option label="不限" value="不限"></el-option>
                                        </el-select>
                                    </el-form-item>
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="技能要求" prop="skills">
                                                <el-select v-model="work.skills" multiple filterable remote placeholder="请输入关键词"
                                                        :remote-method="querySkills" :loading="skillsLoading" style="width: 100%">
                                                    <el-option v-for="skill in skills" :key="skill.id" :label="skill.display_name" :value="skill.id">
                                                    </el-option>
                                                </el-select>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="工作要求"  prop="requirements">
                                                <el-input type="textarea" :rows="3" v-model="work.requirements"></el-input>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="工作内容"  prop="description">
                                                <el-input type="textarea" :rows="3" v-model="work.description"></el-input>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <el-button-group style="margin-left: 40%;">
                                        <el-button type="primary" icon="arrow-left" @click="active--">上一页</el-button>
                                        <el-button type="primary"  @click="active++">下一页<i class="el-icon-arrow-right el-icon--right"></i></el-button>
                                    </el-button-group>
                                </div>
                                <div class="step4" v-show="active==3">
                                    <el-row>
                                        <el-col :span="12">
                                            <el-form-item label="面试"  prop="need_interview">
                                                <el-switch v-model="work.need_interview" on-text="需要" off-text="无需"></el-switch>
                                            </el-form-item>
                                        </el-col>
                                    </el-row>
                                    <div class="interviewInfo" v-show="work.need_interview">
                                        <el-row>
                                            <el-col :span="12">
                                                <el-form-item label="面试时间">
                                                    <el-date-picker v-model="work.interview_time" type="datetime" placeholder="选择面试时间">
                                                    </el-date-picker>
                                                </el-form-item>
                                            </el-col>
                                        </el-row>
                                        <el-row>
                                            <el-col :span="12">
                                                <el-form-item label="面试地点">
                                                    <el-input  v-model="work.interview_place"></el-input>
                                                </el-form-item>
                                            </el-col>
                                        </el-row>
                                    </div>
                                    <el-button-group style="margin-left: 40%;">
                                        <el-button type="primary" icon="arrow-left" @click="active--">上一页</el-button>
                                        <el-button type="primary"  @click="submit(work)">提交</el-button>
                                    </el-button-group>
                                </div>
                            </el-form>
                        </el-card>
                    </div>
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
    </div>
</template>

<script>
    import axios from 'axios';
    import TopNavbar from '../../components/employer/TopNavbar.vue';
    import LeftNavbar from '../../components/employer/LeftNavbar.vue';
    import NoticeBoard from '../../components/employer/NoticeBoard.vue';
    import ToDos from '../../components/employer/ToDos.vue';
    import {mapState} from 'vuex';
    export default {
        name:'CreatWork',
        components:{TopNavbar,LeftNavbar,NoticeBoard,ToDos},
        data() {
            return {
                work: {
                    showAlert: null,
                    id:'',
                    title:'',
                    daterange:[new Date(), new Date()],
                    start_date:'',
                    end_date:'',
                    timerange:[new Date(2016, 9, 10, 10, 0), new Date(2016, 9, 10, 18, 0)],
                    start_time:'',
                    end_time:'',
                    location:[],
                    city:'',
                    district:'',
                    address:'',
                    pay_amount:'',
                    settlement_type:'',
                    pay_type:'',
                    pay_time:'',
                    has_commission:false,
                    commission:'',
                    lunch:[],
                    gender:'',
                    contact:'',
                    need_num:'',
                    hired_num:'',
                    need_interview:true,
                    interview_time:'',
                    interview_place:'',
                    requirements:'',
                    description:'',
                    status: null,
                    check_failed_msg: null,
                    page_view: null,
                    employer_id: null,
                    created_at: null,
                    updated_at: null,
                    skills:[],
                    tags:[]
                },
                active:0,
                process:null,
                selectedLocation:null,
                tagsLoading:false,
                skills:[],
                skillsLoading:false,
                rules: {
                    title: [{required: true, message: '请输入标题', trigger: 'blur'}],
                    tags: [{required:true,message:'请输入兼职种类',trigger:'blur'}],
                    daterange: [{type:'array',required:true,message:'请输入工作日期',trigger:'blur'}],
                    timerange: [{type:'array',required:true,message:'请输入工作时间',trigger:'blur'}],
                    location: [{type:'array',required:true,message:'请输入工作地点',trigger:'blur'}],
                    address: [{required:true,message:'请输入工作地点',trigger:'blur'}],
                    contact: [{required:true,message:'请输入联系方式',trigger:'blur'}],
                    pay_amount: [{required:true,message:'请输入工作报酬',trigger:'blur'}],
                    pay_type: [{required:true,message:'请选择支付方式',trigger:'blur'}],
                    pay_time: [{required:true,message:'请选择结帐时间',trigger:'blur'}],
                    need_num: [{required:true,message:'请输入需要人数（数字）',trigger:'blur'}],
                    gender: [{required:true,message:'请选择性别要求',trigger:'blur'}],
                    requirements: [{required:true,message:'请输入工作要求',trigger:'blur'}],
                    description: [{required:true,message:'请输入工作内容',trigger:'blur'}]
                },
                citys:[{
                    value:'北京', label:'北京', children:[{value:'东城区', label:'东城区'},{value:'西城区', label:'西城区'},{value:'崇文区', label:'崇文区'},{value:'宣武区', label:'宣武区'},{value:'朝阳区', label:'朝阳区'},{value:'丰台区', label:'丰台区'},{value:'石景山区', label:'石景山区'},{value:'海淀区', label:'海淀区'},{value:'门头沟区', label:'门头沟区'},{value:'房山区', label:'房山区'}, {value:'通州区', label:'通州区'},{value:'顺义区', label:'顺义区'},{value:'昌平区', label:'昌平区'},{value:'大兴区', label:'大兴区'},{value:'怀柔区', label:'怀柔区'},{value:'平谷区', label:'平谷区'},{value:'密云县', label:'密云县'},{value:'延庆县', label:'延庆县'}]
                },{value:'上海', label:'上海', children:[{value:'黄浦区', label:'黄浦区'},{value:'卢湾区', label:'卢湾区'},{value:'徐汇区', label:'徐汇区'},{value:'长宁区', label:'长宁区'},{value:'静安区', label:'静安区'},{value:'普陀区', label:'普陀区'},{value:'闸北区', label:'闸北区'},{value:'虹口区', label:'虹口区'},{value:'杨浦区', label:'杨浦区'},{value:'闵行区', label:'闵行区'},{value:'宝山区', label:'宝山区'}, {value:'嘉定区', label:'嘉定区'},{value:'浦东新区', label:'浦东新区'},{value:'金山区', label:'金山区'},{value:'松江区', label:'松江区'},{value:'青浦区', label:'青浦区'},{value:'南汇区', label:'南汇区'},{value:'奉贤区', label:'奉贤区'},{value:'崇明县', label:'崇明县'}]
                },{value:'广州', label:'广州', children:[{value:'东山区', label:'东山区'},{value:'荔湾区', label:'荔湾区'},{value:'越秀区', label:'越秀区'},{value:'海珠区', label:'海珠区'},{value:'天河区', label:'天河区'},{value:'芳村区', label:'芳村区'},{value:'白云区', label:'白云区'},{value:'黄埔区', label:'黄埔区'},{value:'番禺区', label:'番禺区'},{value:'花都区', label:'花都区'},{value:'增城市', label:'增城市'},{value:'从化市', label:'从化市'}]
                },{value:'深圳', label:'深圳', children:[{value:'罗湖区', label:'罗湖区'},{value:'福田区', label:'福田区'},{value:'南山区', label:'南山区'},{value:'宝安区', label:'宝安区'},{value:'龙岗区', label:'龙岗区'},{value:'盐田区', label:'盐田区'},]
                }]
            };
        },
        computed:mapState({
            user: state => state.user,
            token: state => state.token,
            tags: state => state.tags
        }),
        methods: {
            handlePayChange(value) {
                if (value == '面谈') this.work.pay_amount = '面谈';
            },
            queryTags(query) {
                if (query !== '') {
                    this.tagsLoading = true;
                    this.$store.dispatch('GetTags',query);
                    this.tagsLoading = false;
                } else {
                    this.$store.state.tags = this.$store.state.HotTags;
                }
            },
            querySkills(query) {
                if (query !== '') {
                    this.skillsLoading = true;
                    let that = this;
                    axios.get('/api/skills?search='+query).then(function (response) {
                        that.skills = response.data.skills;
                        console.log(that.skills);
                    });
                    this.skillsLoading = false;
                } else {
                    this.skills = [];
                }
            },
            submit(work) {
                work.start_date = this.$moment(work.daterange[0]).format('YYYY-MM-DD');
                work.end_date = this.$moment(work.daterange[1]).format('YYYY-MM-DD');
                work.start_time = this.$moment(work.timerange[0]).format('HH:mm:ss');
                work.end_time = this.$moment(work.timerange[1]).format('HH:mm:ss');
                if (work.need_interview) {
                    work.interview_time = this.$moment(work.interview_time).format('YYYY-MM-DD HH:mm:ss');}
                work.city = work.location[0];
                if (work.has_commission =='有提成') {
                    work.has_commission = true;
                } else {
                    work.has_commission = false;
                }
                work.district = work.location[1];
                work.tags = work.tags.toString();
                work.skills = work.skills.toString();
                work.lunch = work.lunch.toString();
                work.employer_id = this.$store.state.user.id;
                let that = this;
                this.$refs.form.validate( function (valid) {
                    if (valid) {
                        console.log(work);
                        that.$store.dispatch('workStore',work).then(function (data) {
                            that.$message.success('发布成功，请等待审核通过');
                            that.$router.push({name:'employerWorks'});
                        }).catch(function (error) {
                            console.log('PostNewWorkError',error);
                        });
                    }
                })

            }
        },
        mounted: function () {
            this.$store.dispatch('GetHotTags');
        }

    }
</script>

<style scoped>
    .main {
        margin-left: 200px;
        margin-top: 60px;
        background-color: #F5F5F6;
    }
    .workcard {
        margin: 20px 0px 0px 20px;
    }
    .steps {
        margin:30px auto;
        width: 75%;
    }
    .step {
        margin-left: -10px;
        cursor: pointer;
    }
    .workform {
        width: 80%;
        margin:20px auto;
    }
    .tag {
        margin:0 10px;
    }
     .workcard i {
        color: white;
    }
    .white {
        color: white;
    }
</style>