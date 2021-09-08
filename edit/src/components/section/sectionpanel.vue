<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-03-08 00:28:54
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel">
      <q-card class="q-pa-none">
        <q-card-section class="q-pa-md">
          <q-input class="q-mb-sm" standout="bg-primary text-white" label="日期" dense v-model="obj.date" readonly/>
          <q-input class="q-mb-sm" standout="bg-primary text-white" label="开始时间" dense v-model="obj.start" readonly/>
          <q-input class="q-mb-sm" standout="bg-primary text-white" label="结束时间" dense v-model="obj.end" readonly/>
          <q-input class="q-mb-sm" standout="bg-primary text-white" label="总人数" dense v-model="obj.count" :readonly="obj.status!=0"/>
          <q-input standout="bg-primary text-white" label="备注" dense v-model="obj.tip" :readonly="obj.status!=0"/>
        </q-card-section>
        <q-card-actions align="center" class="q-px-md">
          <q-btn label="保存" class="full-width q-mb-sm" color="primary" @click="update"/>
          <q-btn label="取消" class="full-width q-mb-sm" text-color="primary" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
</div></template>
<script>

export default{
name:'sectionpanel',
components:{},
props:[],
data(){return {
    show:false,
    obj:{date:'',start:'',end:''},
    initobj:{date:'',start:'',end:''},
}},
mounted(){

},
methods:{
    showPanel(obj = {}){
      if(obj.sid){
        this.obj = Object.assign({},{},obj)
      }
      this.show = true
    },
    hidePanel(){
      this.obj = Object.assign({},{},this.initobj)
      this.$emit('change')
      this.show = false
    },
    update(){
      this.axios.post(`section/${this.obj.sid}`, this.obj).then(res => {
        this.show = false
      })
    }
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>