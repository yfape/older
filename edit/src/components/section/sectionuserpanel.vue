<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-03-15 15:25:17
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel" position="bottom">
      <q-card class="q-pa-none" style="min-width:900px">
        <q-card-section class="q-pa-none">
            <q-bar>
                <span class="q-mr-md">ID:{{section.sid}} </span>
                <span class="q-mr-md">日期:{{section.date}}</span>
                <span class="q-mr-md">开始时间:{{section.start}}</span>
                <span class="q-mr-md">结束时间:{{section.end}}</span>
                <span>状态:{{section.statustext}}</span>
                <q-space />
                <q-btn dense flat icon="close" v-close-popup>
                    <q-tooltip>关闭</q-tooltip>
                </q-btn>
            </q-bar>
            
            <q-table class="no-shadow" :columns="columns" :data="data" row-key="uid" :pagination.sync="pagination" @request="getList" :loading="loading">
                <template v-slot:top="">
                    <div class="q-pa-none">
                        <q-chip square :label="'预约人数：' + pagination.rowsNumber"/>
                    </div>
                </template>
                <template v-slot:body="props">
                    <q-tr :props="props">
                        <q-td key="uid" :props="props">
                            {{ props.row.user.uid }}
                        </q-td>
                        <q-td key="incode" :props="props">
                            {{ props.row.user.incode }}
                        </q-td>
                        <q-td key="name" :props="props">
                            {{ props.row.user.name }}
                        </q-td>
                        <q-td key="phone" :props="props">
                            {{ props.row.user.phone }}
                        </q-td>
                        <q-td key="arrive" :props="props">
                            <q-btn v-if="section.status!=9&&section.valid&&props.row.arrive==0" label="签到" dense flat color="positive" @click="arrive(props.row.rid)"/>
                            <q-btn v-else-if="section.status==9&&props.row.arrive==0" dense flat color="red-8" @click="arrive(props.row.rid)">{{props.row.statustext}}</q-btn>
                            <div v-else>{{props.row.statustext}}</div>
                        </q-td>
                        <!--<q-td key="actions" :props="props">
                            <q-btn v-if="section.status==0&&section.valid" label="移出" dense flat color="warning"/>
                        </q-td>-->
                    </q-tr>
                </template>
            </q-table>

        </q-card-section>
        <q-card-actions align="center" class="q-px-md">

        </q-card-actions>
      </q-card>
    </q-dialog>
</div></template>
<script>

export default{
name:'sectionuserpanel',
components:{},
props:[],
data(){return {
    show:false,loading:false,
    section:{sid:0,date:'',start:'',end:''},
    initsection:{sid:0,date:'',start:'',end:''},
    columns:[
        {name:'uid', label:'ID', align:'left'},
        {name:'incode', label:'编号', align:'left'},
        {name:'name', label:'姓名', align:'left'},
        {name:'phone', label:'电话', align:'left'},
        {name:'arrive', label:'签到', align:'center'},
        // {name:'actions', label:'操作', align:'center'},
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
    showPanel(section = {}){
        this.$q.loading.show()
        if(section.sid){
            this.section = Object.assign({},{},section)
        }
        this.getData()
    },
    hidePanel(){
        this.data = []
        this.section = Object.assign({},{},this.initsection)
        this.$emit('change')
        this.show = false
    },
    getList(props){
        const { page, rowsPerPage, sortBy, descending } = props.pagination
        this.getData(rowsPerPage, page)
    },
    getData(rowsPerPage = this.pagination.rowsPerPage, page = this.pagination.page){
        this.loading = true
        this.axios.get(`records/${this.section.sid}/${rowsPerPage}/${page}`,{noloading:true}).then(res => {
            this.data = Object.assign([],[],res.data)
            this.pagination.page = res.current_page
            this.pagination.rowsNumber = res.total
            this.pagination.rowsPerPage = res.per_page
            this.loading = false
            this.$q.loading.hide()
            this.show = true
        }).catch(err => {
            this.loading = false
        })
    },
    arrive(rid){
        this.axios.put(`record/${rid}/arrive/1`).then(res => {
            this.getData()
        })
    }
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>