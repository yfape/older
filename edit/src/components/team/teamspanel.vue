<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-03-08 15:48:27
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel" position="bottom">
      <q-card style="min-width:1000px" class="q-pa-none">
        <q-card-section class="q-pa-none">
            <q-table :columns="columns" :data="data" row-key="tid" :pagination.sync="pagination" @request="getList" :loading="loading">
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
                        <q-td key="sur" :props="props">
                            {{ props.row.sur }}
                        </q-td>
                        <q-td key="actions" :props="props">
                            <q-btn flat class="q-mr-sm" label="选择" color="light-green-7" dense @click="selected(props.row)"/>
                        </q-td>
                    </q-tr>
                </template>
            </q-table>
        </q-card-section>
      </q-card>
    </q-dialog>
</div></template>
<script>
export default{
name:'teamspanel',
components:{},
props:[],
data(){return {
    show:false,loading:false,category:0,
    columns:[
        {name:'tid', label:'编号', align:'left'},
        {name:'name', label:'名称', align:'left'},
        {name:'category', label:'班队类型', align:'left'},
        {name:'sum', label:'报名总数', align:'left'},
        {name:'remain', label:'报名预留数', align:'left'},
        {name:'sur', label:'名额剩余', align:'left'},
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
    showPanel(category=0){
        this.category = category
        this.getData()
        this.show = true
    },
    hidePanel(){
        this.show = false
        this.$emit('change')
    },
    getList(props){
        const { page, rowsPerPage, sortBy, descending } = props.pagination
        this.getData(rowsPerPage, page)
    },
    getData(rowsPerPage = this.pagination.rowsPerPage, page = this.pagination.page){
        this.loading = true
        this.axios.get(`teams/${rowsPerPage}/${page}`,{noloading:true,params:{category:this.category}}).then(res => {
            this.data = Object.assign([],[],res.data)
            this.pagination.page = res.current_page
            this.pagination.rowsNumber = res.total
            this.pagination.rowsPerPage = res.per_page
            this.loading = false
        }).catch(err => {
            this.loading = false
        })
    },
    selected(team){
        this.$emit('selected', team)
        this.show = false
    }
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>