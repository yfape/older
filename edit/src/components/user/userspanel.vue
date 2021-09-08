<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-03-08 02:48:15
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel">
      <q-card style="min-width:700px" class="q-pa-none">
        <q-card-section>
          <q-input class="q-mb-sm" dense color="teal" filled v-model="search" label="卡号、姓名、电话">
            <template v-slot:prepend>
              <q-icon name="search" @click="getUsers"/>
            </template>
            <template v-if="search" v-slot:append>
              <q-icon name="cancel" @click.stop="search = null" class="cursor-pointer" @click="initUsers"/>
            </template>
          </q-input>
          <q-table :columns="columns" :data="users" row-key="uid" :pagination="pagination" :loading="loading">
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
                <q-td key="tid" :props="props">
                  {{ props.row.teamname }}
                </q-td>
                <q-td key="t2id" :props="props">
                  {{ props.row.team2phone }}
                </q-td>
                <q-td key="actions" :props="props">
                  <q-btn label="加入班队" color="green-8" dense @click="userJoin(props.row)"/>
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
name:'userspanel',
components:{},
props:[],
data(){return {
  show:false,loading:false,
  search:'',users:[],
  columns:[
    {name:'incode', label:'编号', align:'left'},
    {name:'name', label:'名称', align:'left'},
    {name:'phone', label:'电话', align:'left'},
    {name:'tid', label:'日常班', align:'center'},
    {name:'t2id', label:'教学班', align:'center'},
    {name:'actions', label:'操作',align:'center',required:false}
  ],
  pagination:{
    rowsPerPage: 0,
  },
}},
mounted(){

},
methods:{
    showPanel(){
      this.show = true
    },
    hidePanel(){
      this.show = false
    },
    getUsers(){
      this.axios.get(`user/search/${this.search}`).then(res =>{
        this.users = Object.assign([],[],res)
      })
    },
    userJoin(uid){
      this.$emit('selected', uid)
      this.show = false
    },
    initUsers(){
      this.users = []
    },
},
watch:{
},
destroy(){},
}
</script>
<style scoped>

</style>