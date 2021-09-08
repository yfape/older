<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-03-17 16:44:57
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel" position="bottom">
      <q-card style="min-width:1000px" class="q-pa-none">
        <q-card-section class="q-pa-none">
          <q-table :columns="columns" :data="data" row-key="uid" :pagination.sync="pagination" @request="getList" :loading="loading">
            <template v-slot:top="">
                <div class="row full-width">
                    <div class="col-4 text-left">
                        <q-btn class="q-mr-md" icon="add" color="light-green-7" label="新增成员" @click="$refs.userspanel.showPanel(team.tid)"/>
                        <q-btn icon="outbox" color="cyan-7" label="数据导出" @click="output"/>
                    </div>
                    <div class="col-8 row justify-end text-right">
                      <q-chip class="q-mr-sm" square>最大人数：{{team.sum}}</q-chip>
                      <q-chip class="q-mr-sm" square>预留名额：{{team.remain}}</q-chip>
                      <q-chip class="q-mr-sm" text-color="white" square color="light-blue-7">可操作：{{team.remain - team.remained}}</q-chip>
                      <q-chip class="q-mr-sm" text-color="white" square color="positive">用户可预约：{{team.sum - team.remain - pagination.rowsNumber + (team.remained>team.remain?team.remain:team.remained)}}</q-chip>
                      <q-chip square text-color="white" color="primary">当前成员：{{pagination.rowsNumber}}</q-chip>
                    </div>
                </div>
                <div style="border-radius:5px" class="bg-red-10 full-width text-white text-subtitle1 q-my-sm q-px-md" v-if="(team.sum - team.remain - pagination.rowsNumber + (team.remained>team.remain?team.remain:team.remained)) != team.sur">！ 班队成员数据异常</div>
            </template>
            <template v-slot:body="props">
                <q-tr :props="props">
                    <q-td key="incode" :props="props">
                        {{ props.row.incode }}
                    </q-td>
                    <q-td key="name" :props="props">
                        {{ props.row.name }}
                    </q-td>
                    <q-td key="phone" :props="props">
                        {{ props.row.phone }}
                    </q-td>
                    <q-td key="single" :props="props">
                        {{ props.row.single?'是':'' }}
                    </q-td>
                    <q-td key="forbid" :props="props">
                        {{ props.row.forbid }}
                    </q-td>
                    <q-td key="actions" :props="props">
                      <q-btn outline label="移出班队" color="red-8" dense @click="removeUser(props.row.uid)"/>
                    </q-td>
                </q-tr>
            </template>
          </q-table>
        </q-card-section>
      </q-card>
    </q-dialog>
    <userspanel ref="userspanel" @change="getData" @selected="userJoin"/>
</div></template>
<script>
import userspanel from '@/components/user/userspanel'
export default{
name:'teamuserpanel',
components:{userspanel},
props:[],
data(){return {
  show:false,loading:false,
  team:{tid:0,remain:0,sur:0},
  columns:[
    {name:'incode', label:'编号', align:'left'},
    {name:'name', label:'名称', align:'left'},
    {name:'phone', label:'电话', align:'left'},
    {name:'single', label:'是否为单科', align:'center'},
    {name:'forbid', label:'是否被禁止预约', align:'center'},
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
}},
mounted(){

},
methods:{
    showPanel(team){
      this.team = Object.assign({},{},team)
      this.getData()
      this.show = true
    },
    hidePanel(){
      this.show = false
      this.$emit('change')
    },
    getTeam(){
      this.axios.get(`team/${this.team.tid}`).then(res => {
        this.team = Object.assign({},{},res)
      })
    },
    getList(props){
        const { page, rowsPerPage, sortBy, descending } = props.pagination
        this.getData(rowsPerPage, page)
    },
    getData(rowsPerPage=this.pagination.rowsPerPage, page=this.pagination.page){
      this.loading = true
      this.axios.get(`team/users/${this.team.tid}/${rowsPerPage}/${page}` ,{noloading:true}).then(res => {
        this.data = Object.assign([],[],res.data)
        this.pagination.page = res.current_page
        this.pagination.rowsNumber = res.total
        this.pagination.rowsPerPage = res.per_page
        this.loading = false
      }).catch(err => {
        this.loading = false
      })
    },
    removeUser(uid){
      this.axios.delete(`team/user/${this.team.tid}/${uid}`).then(res => {
        this.getData()
        this.getTeam()
        this.loading = false
      }).catch(err => {
        this.loading = false
      })
    },
    userJoin(user){
      this.axios.post(`user/team/${user.uid}/${this.team.tid}`).then(res => {
        this.getData()
        this.getTeam()
      })
    },
    output(){
      this.axios.post(`output/teamusers/${this.team.tid}`).then(res=>{
        window.open(res)
      })
    }
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>