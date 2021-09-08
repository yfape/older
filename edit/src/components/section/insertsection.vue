<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-02-19 12:20:41
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel">
      <q-card class="q-pa-none">
        <q-card-section class="q-pa-md">
            <div class="text-weight-bold text-subtitle1">目前已更新至 {{lastdate}}</div>
            <q-date v-model="date" range :options="optionsFn"/>
        </q-card-section>
        <q-card-actions align="center" class="q-px-md">
          <q-btn label="上线场次至选择时间" class="full-width q-mb-sm" color="primary" @click="insertSections"/>
          <q-btn label="取消" class="full-width q-mb-sm" text-color="primary" v-close-popup/>
        </q-card-actions>
      </q-card>
    </q-dialog>
</div></template>
<script>

export default{
name:'insertsection',
components:{},
props:[],
data(){return {
    show:false,
    tid:0,
    lastdate:'',
    date:[]
}},
mounted(){

},
methods:{
    showPanel(tid){
        this.tid = tid
        this.axios.get(`team/lastdate/${tid}`, {noloading:true}).then(res => {
            this.lastdate = res
            this.show = true
        })
    },
    hidePanel(){
        this.date = []
        this.$emit('change')
        this.show = false
    },
    optionsFn(date){
        return date > this.lastdate;
    },
    insertSections(){
        this.axios.post('insertsections/' + this.tid, {date: this.date}).then(res => {
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