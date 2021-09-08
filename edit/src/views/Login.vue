<!--
 * @Author: your name
 * @Date: 2021-02-06 21:29:45
 * @LastEditTime: 2021-02-06 23:04:57
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\Login.vue
-->
<template><div class="flex flex-center bg-light-blue-8 full-height">
<div class="desktop-only" style="min-width:900px">
	<div class="row items-center justify-between q-mb-sm" style="height:70px">
		<div class="col-6 text-left row items-center">
			<q-img style="width:35px" src="logo.png" :ratio="1" class="q-mr-md"/>
			<span class="text-bold text-h6 text-white">四川省老干部活动中心</span>
		</div>
		<div class="col-6 text-right text-white">
			<span class="text-subtitle2">欢迎登陆</span><br>
			<span class="text-body1 text-bold">预约平台管理系统</span>
		</div>
	</div>

	<div class="rounded-borders shadow-3 bg-white row" style="height:400px">
		<div class="col-7 row items-center q-pa-md">
			<q-img contain src="image/login2.png"/>
		</div>
		<div class="col-5 q-pa-xl text-left">
			<div class="text-h5 q-py-lg text-grey-6">登录</div>
			<q-input v-model="user.user" class="q-mb-lg" standout="bg-blue-4 text-white" rounded dense label="用户名">
				<template v-slot:prepend>
					<q-icon name="o_perm_identity"/>
				</template>
			</q-input>
			<q-input v-model="user.pass" type="password" class="q-mb-lg" standout="bg-blue-4 text-white" rounded dense label="密码">
				<template v-slot:prepend>
					<q-icon name="o_lock_open"/>
				</template>
			</q-input>
			<q-btn unelevated rounded :loading="loading" :disable="(user.user==''||user.pass=='')" :color="(user.user&&user.pass)?'blue-7':'grey-5'" class="full-width" label="登  录" @click="login"/>
		</div>
	</div>

	<div class="text-white text-caption text-center q-mt-sm">&copy; 四川省老干部活动中心</div>
</div>

<div class="mobile-only full-width row items-center justify-center">
	<div class="bg-white rounded-borders shadow-2" style="width:300px">
		<div class="row items-center justify-center q-py-lg">
			<div class="text-bold text-h6">
				<div>四川省老干部活动中心</div>
				<div class="text-subtitle2">预约平台</div>
			</div>
		</div>
		<div class="q-pa-lg">
			<q-input v-model="user.user" class="q-mb-lg" standout="bg-blue-4 text-white" dense label="用户名">
				<template v-slot:prepend>
          			<q-icon name="o_perm_identity" />
        		</template>
			</q-input>
			<q-input v-model="user.pass" type="password" class="q-mb-lg" standout="bg-blue-4 text-white" dense label="密码">
				<template v-slot:prepend>
					<q-icon name="o_lock_open"/>
				</template>
			</q-input>
			<q-btn :unelevated="user.user&&user.pass?false:true" :loading="loading" :disable="(user.user==''||user.pass=='')" :color="(user.user&&user.pass)?'light-blue-5':'grey-5'" class="full-width" label="登  录" @click="login"/>
		</div>
	</div>	
	<div style="position:fixed;bottom:10px;" class="text-white text-caption text-center q-mt-sm">&copy; 四川省老干部活动中心</div>
</div>

</div></template>

<script>
export default{
name:'Login',
components:{},
data(){return {
	user:{user:'',pass:''},
	loading:false
}},
mounted(){},
methods:{
	login(){
		this.loading = true
		this.axios.post('login',this.user).then(response=>{console.log(response)
			if(this.lStorage('token',response.token)){
				this.$store.state.token = response.token
				this.$router.replace('/')
			}else{
				this.AItip('<div>抱歉，您的浏览器未启用storage存储权限，请联系技术人员</div>');
			}
			this.loading = false
		}).catch(err=>{
			this.loading = false
		})
	},
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>