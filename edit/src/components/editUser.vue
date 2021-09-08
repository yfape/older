<template>
<q-dialog v-model="show" @hide="hidePanel" persistent>
	<q-card class="q-pa-md" style="min-width: 400px">
		<q-card-section class="q-pa-none">
			<q-input class="q-mb-sm" dense outlined readonly label="管理员ID" v-model="user.mid"/>
			<q-input class="q-mb-sm" dense filled label="用户名" :readonly="user.mid" v-model="user.user"/>
			<q-input dense class="q-mb-sm" filled label="名称" v-model="user.name"/>
			<q-input dense class="q-mb-sm" filled label="电话" v-model="user.phone"/>
			<q-item tag="label" v-ripple class="q-mb-sm">
				<q-item-section>
		          	<q-item-label>系统管理权限</q-item-label>
		          	<q-item-label caption>
		            	可管理所有管理员账户
		          	</q-item-label>
		        </q-item-section>
		        <q-item-section side top>
		          	<q-checkbox v-model="user.isadmin" :true-value="1" :false-value="0"/>
		        </q-item-section>
		    </q-item>
		    <q-item tag="label" v-ripple class="q-mb-sm">
				<q-item-section>
		          	<q-item-label>{{user.valid?'启用':'禁用'}}</q-item-label>
		          	<q-item-label caption>
		            	禁用后该账号无法使用
		          	</q-item-label>
		        </q-item-section>
		        <q-item-section side top>
		          	<q-checkbox v-model="user.valid" :true-value="1" :false-value="0"/>
		        </q-item-section>
		    </q-item>
		    <div class="row q-col-gutter-sm">

	        	<div class="q-pa-sm col-6" v-if="user.mid">
	        		<q-btn class="full-width" label="删除管理员" color="red-7" @click="deleteUser"/>
	        	</div>
	        	<div class="q-pa-sm col-6" v-if="user.mid">
	        		<q-btn class="full-width" label="重置密码" color="blue-7" @click="resetPass"/>
	        	</div>
	        	<div class="q-pa-sm col-6">
	        		<q-btn class="full-width" label="返回" color="grey-5" @click="hidePanel"/>
	        	</div>
	        	<div class="q-pa-sm col-6" v-if="user.mid">
	        		<q-btn class="full-width" label="保存信息" color="green-5" @click="saveUser"/>
	        	</div>
	        	<div class="q-pa-sm col-6" v-if="!user.mid">
	        		<q-btn class="full-width" label="新增管理员" icon="o_add" color="green-5" @click="addUser"/>
	        	</div>
	        </div>
		</q-card-section>
	</q-card>
</q-dialog>
</template>
<script>

export default{
name:'editUser',
components:{},
props:[],
data(){return {
	show:false,user:{mid:0},
	inituser:{mid:0,user:'',name:'',phone:'',nameisadmin:0,valid:1}
}},
mounted(){},
methods:{
	showPanel(mid){
		this.user.mid = mid
		this.show = true
		if(mid == 0){
			this.user = Object.assign({},{},this.inituser)
			return
		}
		this.axios.get(`manager/${mid}`).then(res=>{
			this.user = Object.assign({},{},res)
		})
	},
	hidePanel(){
		this.user = Object.assign({},{},this.inituser)
		this.show = false
	},
	resetPass(){
		this.axios.post(`manager/pass/${this.user.mid}`)
	},
	deleteUser(){
		this.axios.delete(`manager/${this.user.mid}`).then(res=>{
			this.show = false
			this.$emit('change')
		})
	},
	addUser(){
		this.axios.post('manager',this.user).then(res=>{
			this.show = false
			this.$emit('change')
		})
	},
	saveUser(){
		this.axios.post('manager/'+this.user.mid,this.user).then(res=>{
			this.show = false
			this.$emit('change')
		})
	}

},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>