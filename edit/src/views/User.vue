<!--
 * @Author: your name
 * @Date: 2021-03-02 11:18:03
 * @LastEditTime: 2021-03-09 15:32:19
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\User.vue
-->
<template><div class="q-pa-md row q-col-gutter-md">
	<div class="col-md-3 col-12">
		<div class="bg-white rounded-borders q-pa-md shadow-2" >
			<div class="text-subtitle1">账号信息</div>
			<q-input outlined readonly v-model="user.user" label="账号" dense class="q-mb-md"/>
			<q-item class="q-mb-md bg-grey-1 rounded-borders" dense tag="label" v-ripple>
		        <q-item-section class="text-left">
		          	<q-item-label>{{user.isadmin?'系统管理员权限':'非系统管理员'}}</q-item-label>
		        </q-item-section>
		        <q-item-section avatar>
		          	<q-toggle disable color="blue" v-model="user.isadmin" :true-value="1" :false-value="0" />
		        </q-item-section>
		    </q-item>
			<q-input outlined v-model="user.name" label="名称" readonly dense class="q-mb-md"/>
			<q-btn class="full-width" dense color="primary" label="修改名称" @click="reviseNickname"/>
		</div>
	</div>
	<div class="col-md-3 col-12">
		<div class="bg-white rounded-borders q-pa-md shadow-2" >
		<div class="text-subtitle1">密码修改</div>
		<q-input outlined v-model="oldpass" label="旧密码" dense class="q-mb-md"/>
		<q-input outlined v-model="newpass" label="新密码" dense class="q-mb-md"/>
		<q-input outlined v-model="checkpass" label="确认密码" dense class="q-mb-md"/>
		<q-btn class="full-width" dense color="primary" label="修改密码" @click="revisePass"/>
		</div>
	</div>
	
</div></template>
<script>
export default{
name:'user',
props:[],
components:{},
data(){return {
	show:true,
	oldpass:'',newpass:'',checkpass:'',
	user:{admin:0},
}},
mounted(){
	this.getSelf()
},
methods:{
	getSelf(){
		this.axios.get('manager/self').then(res=>{
			this.user = Object.assign({},{},res)
		})
	},
	revisePass(){
		if(!this.judgePass()){
			this.AItip('输入框不能为空');return;
		}else if(this.checkpass != this.newpass){
			this.AItip('两次输入密码不一致');return;
		}
		this.axios.post('manager/pass',{oldpass:this.oldpass,newpass:this.newpass})
	},
	reviseNickname(){
		if(!this.user.nickname){
			this.AItip('名称不能为空');return;
		}
		this.axios.post('reviseNickname',{nickname:this.user.nickname})
	},
	judgePass(){
		if(this.oldpass&&this.newpass&&this.checkpass){
			return true
		}else{
			return false
		}
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>
.widthlim{max-width:350px;}
</style>