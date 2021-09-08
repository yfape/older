<!--
 * @Author: your name
 * @Date: 2021-02-07 20:48:05
 * @LastEditTime: 2021-09-07 11:14:28
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\teampanel.vue
-->
<template><div>
    <q-dialog v-model="show" @hide="hidePanel">
      <q-card class="q-pa-none" style="min-width:400px">
        <q-card-section>
            <q-input class="q-mb-md" dense filled label="卡号" v-model="user.incode" :readonly="user.hasOwnProperty('uid')"/>
			<q-input class="q-mb-md" dense filled label="姓名" v-model="user.name"/>
			<q-input class="q-mb-md" dense filled label="电话" v-model="user.phone"/>
            <q-select class="q-mb-md" option-value="tid" option-label="name" dense filled label="单科生" emit-value map-options filled :options="teams" v-model="user.single"/>
            <div class="q-mb-md" v-if="user.hasOwnProperty('uid')">
                <div class="text-caption text-secondary">日常班</div>
                <q-btn v-if="!user.tid" dense outline color="positive" class="full-width" icon="add" @click="$refs.teamspanel.showPanel(1)">选择</q-btn>
                <q-item v-else>
                    <q-item-section>{{user.teamname}}</q-item-section>
                    <q-item-section avatar>
                        <q-btn label="移除班队" dense outline color="red-10" @click="removeUserTeam(user.tid)"/>
                    </q-item-section>
                </q-item>
            </div>
            <div class="q-mb-md" v-if="user.hasOwnProperty('uid')">
                <div class="text-caption text-secondary">教学班</div>
                <q-btn v-if="!user.t2id" dense outline color="positive" class="full-width" icon="add" @click="$refs.teamspanel.showPanel(2)">选择</q-btn>
                <q-item v-else>
                    <q-item-section>{{user.team2name}}</q-item-section>
                    <q-item-section avatar>
                        <q-btn label="移除班队" dense outline color="red-10" @click="removeUserTeam(user.t2id)"/>
                    </q-item-section>
                </q-item>
            </div>
            <div class="q-mb-md" v-if="user.forbid">
                <div class="text-caption text-secondary">封禁时间</div>
                <q-item>
                    <q-item-section>{{dateformat(new Date(user.forbid * 1000))}}</q-item-section>
                    <q-item-section avatar>
                        <q-btn dense class="q-px-sm" label="解封" outline color="red-10" @click="clearForbid"/>
                    </q-item-section>
                </q-item>
            </div>
			<div v-if="user.uid"><q-btn class="full-width" label="保存" dense color="primary" @click="saveUser"/></div>
            <div v-else><q-btn class="full-width" label="新增" dense color="positive" @click="insertUser"/></div>
			<div class="q-mt-sm" v-if="user.start"><q-btn class="full-width text-primary bg-grey-4" label="删除" dense @click="deleteItem"/></div>
        </q-card-section>
      </q-card>
    </q-dialog>
    <teamspanel ref="teamspanel" @selected="joinTeam"/>
</div></template>
<script>
import teamspanel from '@/components/team/teamspanel'
export default{
name:'userpanel',
components:{teamspanel},
props:[],
data(){return {
  show:false,loading:false,
  user:{},teams:[]
}},
mounted(){
    this.getTeams()
},
methods:{
    showPanel(user){
        if(user){
            this.getUser(user.uid)
            // this.user = Object.assign({},{},user)
        }
        this.show = true
    },
    hidePanel(){
        this.initUser()
        this.$emit('change')
        this.show = false
    },
    initUser(){
        this.user = {}
    },
    saveUser(){
        this.axios.post(`user/${this.user.uid}`, this.user).then(res => {
            this.show = false
        })
    },
    insertUser(){
        this.axios.post('user', this.user).then(res => {
            this.show = false
        })
    },
    getUser(uid = this.user.uid){
        this.axios.get(`user/${uid}`,{noloading:true}).then(res => {
            this.user = Object.assign({},{},res)
        })
    },
    getTeams(){
        this.axios.get('teams/1000/1').then(res => {
            this.teams = Object.assign([],[],res.data)
            this.teams.unshift({tid:'', name:'非单科'})
        })
    },
    joinTeam(team){
        this.axios.post(`user/team/${this.user.uid}/${team.tid}`).then(res => {
            this.getUser()
        })
    },
    removeUserTeam(tid){
        this.axios.delete(`team/user/${tid}/${this.user.uid}`).then(res => {
            this.getUser()
        })
    },
    clearForbid(){
        this.axios.delete(`user/forbid/${this.user.uid}`).then(res => {
            this.getUser()
        })
    }
},
watch:{
},
destroy(){},
}
</script>
<style scoped>

</style>