<!--
 * @Author: your name
 * @Date: 2021-03-02 11:18:03
 * @LastEditTime: 2021-03-09 16:50:22
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\Users.vue
-->
<template><div class="row q-pa-md q-col-gutter-md items-top justify-start" v-if="show">
	<div class="col-md-3 col-12">
		<q-btn class="citem full-width text-green" unelevated icon="o_add" color="white" label="添加新管理员" @click="$refs.editUser.showPanel(0)"/>
	</div>
	<div class="col-md-3 col-12" v-for="item in users">
		<q-item class="citem text-left q-pa-sm bg-white">
			<q-item-section>
				<div>用户名：{{item.user}}</div>
				<div>名称：{{item.name}}</div>
			</q-item-section>
			<q-item-section avatar>
				<q-btn color="blue-7" stack icon="edit" dense label="编辑人员" @click="$refs.editUser.showPanel(item.mid)"/>
			</q-item-section>
		</q-item>
	</div>
	<editUser ref="editUser" @change="getUsers"/>
</div></template>
<script>
import editUser from '@/components/editUser'
export default{
name:'users',
props:[],
components:{editUser},
data(){return {
	show:false,users:[]
}},
mounted(){
	this.getUsers()
},
methods:{
	getUsers(){
		this.axios.get('managers').then(res=>{
			this.users = Object.assign([],[],res)
			this.show = true
		})
	},
},
watch:{},
destroy(){},
}
</script>
<style scoped>
.citem{height:80px;}
</style>