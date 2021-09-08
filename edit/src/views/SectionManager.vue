<!--
 * @Author: your name
 * @Date: 2021-02-07 09:21:04
 * @LastEditTime: 2021-03-08 00:47:20
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\TeamManager.vue
-->
<template><div class='q-pa-md'>

    <q-table :columns="columns" :data="data" row-key="tid" :loading="loading" :pagination="initialPagination">
        <template v-slot:top="">
            <div class="full-width row items-center justify-between" style="height:50px">
                <div class="col-4 row items-center">
                    <span class="text-weight-thin text-h6 q-mr-md" >{{team.name}}</span>
                </div>
                <div class="col-8 text-right">
                    <q-chip class="q-mr-md" :label="'报名总名额：'+team.sum"/>
                    <q-chip class="q-mr-md" :label="'预留名额：'+team.remain"/>
                    <q-chip class="q-mr-md" :label="'剩余名额：'+team.sur"/>
                    <q-chip class="q-mr-md" color="primary" text-color="white" :label="'班级人数：'+(team.sum-team.remain-team.sur)"/>
                </div>
            </div>
            <q-separator class="full-width q-my-md"/>
            <div class="row full-width">        
                <div class="col text-left row">
                    <div class="q-mr-md"><q-btn icon="add" color="light-green-7" label="新增场次" @click="$refs.insertsection.showPanel(team.tid)"/></div>
                    <div class="q-mr-md"><q-btn icon="outbox" color="cyan-7" label="数据导出"/></div>
                    <div>
                        <q-input dense filled v-model="date">
                            <template v-slot:append>
                                <q-icon name="event" class="cursor-pointer">
                                <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
                                    <q-date mask="YYYYMMDD" v-model="date">
                                    <div class="row items-center justify-end">
                                        <q-btn v-close-popup label="Close" color="primary" flat />
                                    </div>
                                    </q-date>
                                </q-popup-proxy>
                                </q-icon>
                            </template>
                        </q-input>
                    </div>
                </div>
            </div>
        </template>
        <template v-slot:body="props">
            <q-tr :props="props">
                <q-td key="sid" :props="props">
                    {{ props.row.sid }}
                </q-td>
                <q-td key="date" :props="props">
                    {{ props.row.date }}
                </q-td>
                <q-td key="start" :props="props">
                    {{ props.row.start }}
                </q-td>
                <q-td key="end" :props="props">
                    {{ props.row.end }}
                </q-td>
                <q-td key="count" :props="props">
                    {{ props.row.count }}
                </q-td>
                <q-td key="sur" :props="props">
                    {{ props.row.sur }}
                </q-td>
                <q-td key="remain" :props="props">
                    {{ props.row.count-props.row.sur }}
                </q-td>
                <q-td key="statustext" :props="props">
                    {{ props.row.statustext }}
                </q-td>
                <q-td key="actions" :props="props">
                    <q-btn v-if="props.row.valid&&props.row.status==0" flat class="q-mr-sm" label="场次编辑" color="light-green-7" dense @click="$refs.sectionpanel.showPanel(props.row)"/>
                    <q-btn v-if="props.row.valid" flat class="q-mr-sm" label="人员管理" color="cyan-7" dense @click="$refs.sectionuserpanel.showPanel(props.row)"/>
                    <q-btn v-if="props.row.valid&&props.row.status==0" class="q-mr-sm" flat color="red-8" dense> 
                        <template v-slot:default="">
                            取消场次
                            <q-popup-edit v-model="props.row.tip">
                                <div class="q-pa-sm row" >
                                    <q-input standout="bg-negative text-white" dense label="备注："  v-model="props.row.tip"/>
                                    <q-btn dense color="negative" icon="check" @click="valid(props.row.sid, props.row.tip)" v-close-popup/>
                                </div>
                            </q-popup-edit>
                        </template>
                    </q-btn>
                    <q-btn v-if="props.row.valid==0&&props.row.status==0" flat class="q-mr-sm" label="启用场次" color="green-8" dense @click="valid(props.row.sid)"/>
                    <q-btn v-if="props.row.status==0" flat label="删除" dense >
                        <q-popup-proxy transition-show="flip-up" transition-hide="flip-down">
                            <q-banner class="bg-red-9 text-white">
                            <template v-slot:avatar>
                                <q-icon name="privacy_tip" />
                            </template>
                            <div class="row items-center">
                                <span>您确定要删除吗？</span>
                                <q-btn dense flat icon="done_outline" @click="deleteSection(props.row.sid)" v-close-popup/>
                            </div>
                            </q-banner>
                        </q-popup-proxy>
                    </q-btn>
                </q-td>
            </q-tr>
        </template>
    </q-table>
    <sectionpanel ref="sectionpanel" @change="getData"/>
    <sectionuserpanel ref="sectionuserpanel" @change="getData"/>
    <insertsection ref="insertsection" @change="getData"/>
</div></template>
<script>
import sectionpanel from '@/components/section/sectionpanel'
import sectionuserpanel from '@/components/section/sectionuserpanel'
import insertsection from '@/components/section/insertsection'

export default{
name:'SectionManager',
components:{sectionpanel, sectionuserpanel, insertsection},
props:['tid'],
data(){return {
    loading:false,date:'',
    team:{tid:0,name:'',logo:''},
    columns:[
        {name:'sid', label:'编号', align:'left'},
        {name:'date', label:'日期', align:'left'},
        {name:'start', label:'开始', align:'left'},
        {name:'end', label:'结束', align:'left'},
        {name:'count', label:'名额', align:'center'},
        {name:'sur', label:'剩余名额', align:'center'},
        {name:'remain', label:'预约数', align:'center'},
        {name:'statustext', label:'状态', align:'left'},
        {name:'actions', label:'操作',align:'left',required:false}
    ],
    data:[],
    actionObj:{},actionKey:0,
    initialPagination:{rowsPerPage: 0},
    openshow:false,deleteshow:false
}},
mounted(){
    this.getTeam();
    this.date = new Date().Format('yyyyMMdd');
},
methods:{
    getTeam(){
        this.axios.get(`team/${this.tid}`, {noloading: true}).then(res => {
            this.team = Object.assign({},{},res)
        })
    },
    getData(){
        this.$refs.qDateProxy.hide()
        this.loading = true
        this.axios.get(`sections/${this.tid}/${this.date}`,{noloading:true}).then(res => {
            this.data = Object.assign([],[],res)
            this.loading = false
        }).catch(err => {
            this.loading = false
        })
    },
    deleteSection(sid){
        this.axios.delete(`section/${sid}`).then(res => {
            this.getData()
        })
    },
    valid(sid, tip=''){
        this.axios.post(`section/valid/${sid}`, {tip: tip}).then(res => {
            this.getData()
        })
    },
},
watch:{
    date(){
        this.getData()
    }
},
destroy(){},
}
</script>
<style scoped>
.lineimg{width:35px;}
</style>