<template>
    <div class="GetWorks">
        <el-card class="sort-card">
            <el-form :inline="true" :model="params">
                <el-form-item>
                    <el-cascader :options="citys" v-model="params.location" placeholder="城市" expand-trigger="hover" size="small" style="width: 100px;">
                    </el-cascader>
                </el-form-item>
                <el-form-item>
                    <el-select v-model="params.tag" filterable placeholder="兼职种类" size="small" style="width: 100px;">
                        <el-option v-for="tag in tags" :key="tag.id" :label="tag.dispaly_name" :value="tag.name">
                        </el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-select v-model="params.sortBy" placeholder="排序方式" size="small" style="width: 100px; float:right;">
                        <el-option label="热度从高到低" value="page_view,desc"></el-option>
                        <el-option label="发布时间最新" value="updated_at,desc"></el-option>
                        <el-option label="工资由高到低" value="pay_amount,desc"></el-option>
                        <el-option label="工资由低到高" value="pay_amount,asc"></el-option>
                    </el-select>
                </el-form-item>
                <el-form-item>
                    <el-dropdown trigger="click" :hide-on-click="false">
                        <el-button class="SelectButton">更多选项<i class="el-icon-caret-bottom el-icon--right"></i></el-button>
                        <el-dropdown-menu slot="dropdown" style="width: 360px;">
                            <el-dropdown-item>
                                性别 <br/>
                                <el-radio-group v-model="params.gender"  size="small" style="margin:-20px auto 10px;">
                                    <el-radio-button label="男"></el-radio-button>
                                    <el-radio-button label="女"></el-radio-button>
                                    <el-radio-button label="不限"></el-radio-button>
                                </el-radio-group>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                工资发放时间<br/>
                                <el-radio-group v-model="params.pay_time" size="small" style="margin:-20px auto 10px;">
                                    <el-radio-button label="当天结算"></el-radio-button>
                                    <el-radio-button label="一周内结算"></el-radio-button>
                                    <el-radio-button label="一月内结算"></el-radio-button>
                                    <el-radio-button label="一月后结算"></el-radio-button>
                                </el-radio-group>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                支付方式<br/>
                                <el-radio-group v-model="params.pay_type" size="small" style="margin:-20px auto 10px;">
                                    <el-radio-button label="微信支付"></el-radio-button>
                                    <el-radio-button label="支付宝" ></el-radio-button>
                                    <el-radio-button label="银行卡转账"></el-radio-button>
                                    <el-radio-button label="现金支付"></el-radio-button>
                                </el-radio-group>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                结算方式<br/>
                                <el-radio-group v-model="params.settlement_type" size="small" style="margin:-20px auto 10px;">
                                    <el-radio-button label="元/天"></el-radio-button>
                                    <el-radio-button label="元/小时"></el-radio-button>
                                    <el-radio-button label="元/次"></el-radio-button>
                                    <el-radio-button label="面谈" ></el-radio-button>
                                </el-radio-group>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <span style="margin-right: 15px;">提成</span>
                                <el-switch v-model="params.has_commission" on-text="有" off-text="无">
                                </el-switch>
                            </el-dropdown-item>
                            <el-dropdown-item>
                                <span style="margin-right: 15px;">面试</span>
                                <el-switch v-model="params.need_interview" on-text="有" off-text="无">
                                </el-switch>
                            </el-dropdown-item>
                        </el-dropdown-menu>
                    </el-dropdown>
                </el-form-item>
                <el-button type="primary"  @click="GetWorks(params)" size="small" style="width: 100px; float:right;">筛选</el-button>
            </el-form>
        </el-card>
        <div class="WorkLists" v-if="works.data.length > 0">
            <WorkList v-for="work in works.data" :work="work"></WorkList>
            <el-button type="primary" class="LongButton" @click="loadMore(params)" v-if="works.next_page_url">加载更多</el-button>
            <el-button type="primary" :disabled="true" class="LongButton" v-else>已全部加载完</el-button>
        </div>
        <el-card v-else>
            <div class="EmptyState">
                <div class="EmptyState-inner">
                    <i class="fa fa-briefcase EmptyState-icon"></i>
                    <span>暂时还没有符合条件的兼职</span>
                </div>
            </div>
        </el-card>
    </div>
</template>

<script>
    import WorkList from './WorkList.vue';
    import {mapState} from 'vuex';
    export default {
        name: 'GetWorks',
        components:{WorkList},
        props:['query'],
        data() {
            return {
                citys:[{
                    value:'北京', label:'北京', children:[{value:'全部', label:'全部'},{value:'东城区', label:'东城区'},{value:'西城区', label:'西城区'},{value:'崇文区', label:'崇文区'},{value:'宣武区', label:'宣武区'},{value:'朝阳区', label:'朝阳区'},{value:'丰台区', label:'丰台区'},{value:'石景山区', label:'石景山区'},{value:'海淀区', label:'海淀区'},{value:'门头沟区', label:'门头沟区'},{value:'房山区', label:'房山区'}, {value:'通州区', label:'通州区'},{value:'顺义区', label:'顺义区'},{value:'昌平区', label:'昌平区'},{value:'大兴区', label:'大兴区'},{value:'怀柔区', label:'怀柔区'},{value:'平谷区', label:'平谷区'},{value:'密云县', label:'密云县'},{value:'延庆县', label:'延庆县'}]
                },{value:'上海', label:'上海', children:[{value:'全部', label:'全部'},{value:'黄浦区', label:'黄浦区'},{value:'卢湾区', label:'卢湾区'},{value:'徐汇区', label:'徐汇区'},{value:'长宁区', label:'长宁区'},{value:'静安区', label:'静安区'},{value:'普陀区', label:'普陀区'},{value:'闸北区', label:'闸北区'},{value:'虹口区', label:'虹口区'},{value:'杨浦区', label:'杨浦区'},{value:'闵行区', label:'闵行区'},{value:'宝山区', label:'宝山区'}, {value:'嘉定区', label:'嘉定区'},{value:'浦东新区', label:'浦东新区'},{value:'金山区', label:'金山区'},{value:'松江区', label:'松江区'},{value:'青浦区', label:'青浦区'},{value:'南汇区', label:'南汇区'},{value:'奉贤区', label:'奉贤区'},{value:'崇明县', label:'崇明县'}]
                },{value:'广州', label:'广州', children:[{value:'全部', label:'全部'},{value:'东山区', label:'东山区'},{value:'荔湾区', label:'荔湾区'},{value:'越秀区', label:'越秀区'},{value:'海珠区', label:'海珠区'},{value:'天河区', label:'天河区'},{value:'芳村区', label:'芳村区'},{value:'白云区', label:'白云区'},{value:'黄埔区', label:'黄埔区'},{value:'番禺区', label:'番禺区'},{value:'花都区', label:'花都区'},{value:'增城市', label:'增城市'},{value:'从化市', label:'从化市'}]
                },{value:'深圳', label:'深圳', children:[{value:'全部', label:'全部'},{value:'罗湖区', label:'罗湖区'},{value:'福田区', label:'福田区'},{value:'南山区', label:'南山区'},{value:'宝安区', label:'宝安区'},{value:'龙岗区', label:'龙岗区'},{value:'盐田区', label:'盐田区'},]
                }]
            }
        },
        computed: mapState ({
            params: state => state.params,
            works: state => state.works,
            HotTags: state => state.HotTags,
            tags: state => state.tags
        }),
        methods: {
            GetWorks(params) {
                if (params.sortBy) {
                    params.sort = (params.sortBy).split(",");
                }
                this.$store.dispatch('GetWorks',params);
            },
            loadMore(params) {
                if (params.sortBy) {
                    params.sort = (params.sortBy).split(",");
                }
                if (this.works.next_page_url) {
                    params.page = this.works.current_page + 1;
                }
                this.$store.dispatch('GetMoreWorks',params);
            }
        },
        mounted: function () {
            if (Object.keys(this.query).length !== 0) {
                for (let key in this.query) {
                    if (this.params.hasOwnProperty(key)){
                        this.params[key] = this.query[key];
                    }
                }
            }
            this.GetWorks(this.params);
            this.$store.dispatch('GetHotTags');
        }
    }
</script>

<style scoped>
    .sort-card {
        width: 100%;
        margin-bottom:10px;
    }
    .el-form-item {
        margin-bottom:0px;
    }
    .el-form {
        margin-bottom:0px;
        font-size: 15px;
    }
    .SelectButton {
        width: 100px;
        display: inline-block;
        line-height: 27px;
        font-size: 13px;
        padding: 0px;
        color: #8391a5;
    }
    .WorkLists {
        display: flex;
        flex-direction: column;
    }
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
</style>