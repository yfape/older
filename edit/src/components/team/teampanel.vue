<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-03-07 21:52:59
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" persistent @hide="hidePanel" position="bottom">
      <q-card style="min-width:1000px" class="q-pa-none">
        <q-card-section class="row q-pa-none">
          <div class="col-4 q-pa-md">
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" v-model="team.name" label="名称" /></div>
            <div class="q-mb-md">
              <q-select dense emit-value map-options standout="bg-teal text-white" v-model="team.category" :options="categorys" label="班队类型" />
            </div>
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" type="number" v-model="team.sum" label="报名总数" /></div>
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" type="number" v-model="team.remain" label="预留名额" /></div>
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" type="number" v-model="team.sur" readonly label="剩余" /></div>
            <q-separator />
            <div class="text-caption text-secondary q-mb-sm">模板初始数据</div>
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" v-model="team.count" label="每场人数" /></div>
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" v-model="team.time" label="时间说明" /></div>
            <div><q-input dense standout="bg-primary text-white" class="q-mb-md" v-model="team.addr" label="地点说明" /></div>
          </div>
          <div class="col-8 q-pa-md">
            <div class="q-mb-md">
              <q-tabs dense stretch narrow-indicator v-model="week" active-color="primary" align="justify">
                <q-tab :name="1">周一</q-tab>
                <q-tab :name="2">周二</q-tab>
                <q-tab :name="3">周三</q-tab>
                <q-tab :name="4">周四</q-tab>
                <q-tab :name="5">周五</q-tab>
                <q-tab :name="6">周六</q-tab>
                <q-tab :name="0">周日</q-tab>
              </q-tabs>
            </div>
            <div class="text-left">
              <q-item class="q-px-lg" clickable v-ripple v-for="item,index in team.content[week]" @click="openCC(index)">
                <q-item-section>
                  <q-item-label :lines="3" caption>
                    <div class="text-subtitle1 text-bold">时间：{{item.start}} - {{item.end}}</div>
                    <div>总人数：{{item.count}}<span class="q-px-sm"></span>地点：{{item.addr}}<span class="q-px-sm"></span>备注：{{item.tip}}</div>
                  </q-item-label>
                </q-item-section>
              </q-item>
            </div>
            <div>
              <q-btn icon="o_add" label="添加场次" flat @click="openCC(-1)"/>
            </div>
          </div>
        </q-card-section>
        <q-card-actions align="right">
          <q-btn flat label="取消" color="grey-7" v-close-popup />
          <q-btn v-if="team.tid" label="保存班队数据" color="primary" @click="saveTeam"/>
          <q-btn v-else label="新增班队" color="primary" @click="insertTeam"/>
        </q-card-actions>
      </q-card>
    </q-dialog>
    <editCC ref="editCC" @save="saveCC" @delete="deleteCC"/>
</div></template>
<script>
import uploadbtn from '../upload'
import editCC from './editCC'
export default{
name:'teampanel',
components:{uploadbtn,editCC},
props:[],
data(){return {
  show:false,
  index:0,categorys:[{value:1, label:'日常班'},{value:2, label:'教学班'}],
  team:{name:'',content:[[],[],[],[],[],[],[]],open:1},
  initteam:{name:'',content:[[],[],[],[],[],[],[]],open:1},
  week:1
}},
mounted(){

},
methods:{
    showPanel(obj = {}, index = 0){
      if(obj.tid){
        this.team = Object.assign({},{},obj)
        this.team.content = this.team.content ? this.team.content : this.initteam.content
      }
      this.index = index
      this.show = true
    },
    hidePanel(){
      this.index = 0
      this.team = Object.assign({},{},this.initteam)
      this.show = false
      this.$emit('change')
    },
    openCC(i){
      this.action = i
      if(!this.team.content[this.week]){
        this.$refs.editCC.showPanel(false, this.team)
      }else{
        this.$refs.editCC.showPanel(this.team.content[this.week][i]?this.team.content[this.week][i]:'',this.team)
      }
    },
    saveCC(obj){
      if(!this.team.content[this.week]){
        this.team.content[this.week] = []
      }
      if(this.action<0){
        this.team.content[this.week].push(obj)
      }else{
        this.$set(this.team.content[this.week],this.action,obj)
      }
      this.team.content = Object.assign([],[],this.team.content)
    },
    deleteCC(){
      this.team.content[this.week].splice(this.action,1)
      this.team.content = Object.assign([],[],this.team.content)
    },
    saveTeam(){
      this.axios.post('team/'+this.team.tid,this.team).then(res => {
        this.hidePanel()
      })
    },
    insertTeam(){
      this.axios.post('team',this.team).then(res => {
        this.hidePanel()
      })
    }
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>