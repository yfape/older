<!--
 * @Author: your name
 * @Date: 2021-02-07 09:21:04
 * @LastEditTime: 2021-03-15 16:53:21
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\TeamManager.vue
-->
<template><div class='q-pa-md'>
    <q-table :columns="columns" :data="data" row-key="tid" :pagination.sync="pagination" @request="getList" :loading="loading">
        <template v-slot:top="">
            <div class="row full-width">
                <div class="col text-left">
                    <q-btn class="q-mr-md" icon="add" color="light-green-7" label="新增班队" @click="$refs.teampanel.showPanel()"/>
                    <q-btn class="q-mr-md" color="red-7" label="关闭所有报名" @click="openAll(0)"/>
                    <q-btn class="q-mr-md" color="blue-7" label="开放所有报名" @click="openAll(1)"/>
                    <q-btn icon="outbox" color="cyan-7" label="数据导出" @click="outputTeams()"/>
                </div>
                <div class="col text-right" ><span>班队总数：<span class="text-h6 text-bold">{{pagination.rowsNumber}}</span></span></div>
            </div>
        </template>
        <template v-slot:body="props">
            <q-tr :props="props">
                <q-td key="tid" :props="props">
                    {{ props.row.tid }}
                </q-td>
                <q-td key="name" :props="props">
                    {{ props.row.name }}
                </q-td>
                <q-td key="category" :props="props">
                    {{ props.row.category }}
                </q-td>
                <q-td key="sum" :props="props">
                    {{ props.row.sum }}
                </q-td>
                <q-td key="remain" :props="props">
                    {{ props.row.remain }}
                </q-td>
                <q-td key="bmstatus" :props="props">
                    <q-toggle v-model="props.row.open" checked-icon="check" color="green" unchecked-icon="clear" :true-value="1" :false-value="0" @input="changeTeamOpen(props.row)"/>
                </q-td>
                <q-td key="actions" :props="props">
                    <q-btn flat class="q-mr-sm" label="基本信息" color="light-green-7" dense @click="$refs.teampanel.showPanel(props.row, props.rowIndex)"/>
                    <q-btn flat class="q-mr-sm" label="场次管理" color="info" dense @click="$router.push(`/sectionmanager/${props.row.tid}`)"/>
                    <q-btn flat class="q-mr-sm" label="成员管理" color="cyan-7" dense @click="$refs.teamuserpanel.showPanel(props.row)"/>
                    <q-btn flat label="删除班队" color="red-8" dense @click="openDeleteDialog(props)"/>
                </q-td>
            </q-tr>
        </template>
    </q-table>
    <div id="inserta"></div>
    <q-dialog v-model="deleteshow" persistent>
      <q-card>
        <q-card-section class="row items-center">
          <q-avatar icon="highlight_off" color="negative" text-color="white" />
          <span class="q-ml-sm">您确定要删除{{actionObj.name}}</span>
        </q-card-section>

        <q-card-actions align="right">
          <q-btn flat label="取消" color="grey-6" v-close-popup />
          <q-btn label="确定" color="red-7" @click="deleteTeam(actionObj.tid)" v-close-popup />
        </q-card-actions>
      </q-card>
    </q-dialog>
    <teampanel ref="teampanel" @change="getData"/>
    <teamuserpanel ref="teamuserpanel" @change="getData"/>
</div></template>
<script>
import teampanel from '@/components/team/teampanel';
import teamuserpanel from '@/components/team/teamuserpanel';
export default{
name:'TeamManager',
components:{teampanel,teamuserpanel},
props:[],
data(){return {
    loading:false,
    columns:[
        {name:'tid', label:'编号', align:'left'},
        {name:'name', label:'名称', align:'left'},
        {name:'category', label:'班队类型', align:'left'},
        {name:'sum', label:'报名总数', align:'left'},
        {name:'remain', label:'报名预留数', align:'left'},
        {name:'bmstatus', label:'报名状态', align:'left'},
        {name:'actions', label:'操作',align:'center',required:false}
    ],
    data:[],
    pagination:{
        sortBy: 'desc',
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
    },
    actionObj:{},actionKey:0,
    openshow:false,deleteshow:false
}},
mounted(){
    this.getData();
},
methods:{
    getList(props){
        const { page, rowsPerPage, sortBy, descending } = props.pagination
        this.getData(rowsPerPage, page)
    },
    getData(rowsPerPage = this.pagination.rowsPerPage, page = this.pagination.page){
        this.loading = true
        this.axios.get(`teams/${rowsPerPage}/${page}`,{noloading:true}).then(res => {
            this.data = Object.assign([],[],res.data)
            this.pagination.page = res.current_page
            this.pagination.rowsNumber = res.total
            this.pagination.rowsPerPage = res.per_page
            this.loading = false
        }).catch(err => {
            this.loading = false
        })
    },
    openOpenDialog(e){
        this.actionKey = e.key
        this.actionObj = Object.assign({},{},e.row)
        this.openshow = true
    },
    openDeleteDialog(e){
        this.actionKey = e.key
        this.actionObj = Object.assign({},{},e.row)
        this.deleteshow = true
    },
    changeTeamOpen(e){
        this.axios.post(`team/open/${e.tid}`).then(res => {
            this.getData()
        })
    },
    deleteTeam(tid){
        this.axios.delete(`team/${tid}`).then(res => {
            this.getData()
        })
    },
    openAll(open){
        this.axios.post(`teams/open/${open}`).then(res => {
            this.getData()
        })
    },
    outputTeams(){
        this.axios.post(`output/teams`).then(res => {
            window.open(res)
        })
    }
},
watch:{},
destroy(){},
}
</script>
<style scoped>
.lineimg{width:35px;}
#inserta a{width:100px;height:50px;cursor:pointer}
</style>