<template><div class="q-pa-md">
	<div class="q-col-gutter-md row items-top justify-start">

		<div class="col-md-3 col-12">
			<q-item class="bg-white rounded-borders text-right initem">
				<q-item-section avatar>
					<q-spinner-facebook color="yellow-8" size="36px"/>
				</q-item-section>
				<q-item-section>
					<q-item-label :lines="1" class="text-subtitle1 text-grey">
						今日活动场次
					</q-item-label>
					<q-item-label :lines="1" class="text-h6 text-bold">
						{{subnum}}场
					</q-item-label>
				</q-item-section>
			</q-item>
		</div>

		<div class="col-md-3 col-12">
			<q-item class="bg-white rounded-borders text-right initem">
				<q-item-section avatar>
					<q-spinner-pie color="blue-4" size="36px"/>
				</q-item-section>
				<q-item-section>
					<q-item-label :lines="1" class="text-subtitle1 text-grey">
						今日预约人数
					</q-item-label>
					<q-item-label :lines="1" class="text-h6 text-bold">
						{{memsubnum}}人
					</q-item-label>
				</q-item-section>
			</q-item>
		</div>

		<div class="col-md-3 col-12">
			<q-item class="bg-white rounded-borders text-right initem">
				<q-item-section avatar>
					<q-icon name="group" color="red-4" size="44px"/>
				</q-item-section>
				<q-item-section>
					<q-item-label :lines="1" class="text-subtitle1 text-grey">
						平台总用户
					</q-item-label>
					<q-item-label :lines="1" class="text-h6 text-bold">
						{{memnum}}人
					</q-item-label>
				</q-item-section>
			</q-item>
		</div>

		<div class="col-md-3 col-12">
			<q-item class="bg-white rounded-borders text-right initem">
				<q-item-section avatar>
					<q-icon name="recent_actors" color="orange-4" size="44px"/>
				</q-item-section>
				<q-item-section>
					<q-item-label :lines="1" class="text-subtitle1 text-grey">
						平台管理人员
					</q-item-label>
					<q-item-label :lines="1" class="text-h6 text-bold">
						{{usernum}}人
					</q-item-label>
				</q-item-section>
			</q-item>
		</div>

		<div class="col-md-3 col-12">
			<q-item class="bg-white rounded-borders text-right initem">
				<q-item-section avatar>
					<q-spinner-cube color="blue-8" size="36px"/>
				</q-item-section>
				<q-item-section>
					<q-item-label :lines="1" class="text-subtitle1 text-grey">
						项目总数
					</q-item-label>
					<q-item-label :lines="1" class="text-h6 text-bold">
						{{actnum}}个
					</q-item-label>
				</q-item-section>
			</q-item>
		</div>

		<div class="col-md-3 col-12">
			<q-item class="bg-white rounded-borders text-right initem">
				<q-item-section slide>
					<q-btn label="场次管理" icon="view_module" color="blue-4" stack>
						<q-popup-proxy transition-show="flip-up" transition-hide="flip-down">
							<div class="row items-center justify-center no-shadow q-gutter-md">
								<div v-for="item in activitys" class="sitem">
									<div :class="[['bg-'+item.color],'text-white rounded-borders shadow-1 full-width full-height row items-center justify-center']" @click="changeArg('subscribe',item.aid)">
										{{item.name}}
									</div>
								</div>
							</div>
						</q-popup-proxy>
					</q-btn>
				</q-item-section>
				<q-item-section slide>
					<q-btn label="当日考勤" icon="loyalty" color="red-4" stack>
						<q-popup-proxy transition-show="flip-up" transition-hide="flip-down">
							<div class="row items-center justify-center no-shadow q-gutter-md">
								<div v-for="item in activitys" class="sitem">
									<div :class="[['bg-'+item.color],'text-white rounded-borders shadow-1 full-width full-height row items-center justify-center']" @click="changeArg('sign',item.aid)">
										{{item.name}}
									</div>
								</div>
							</div>
						</q-popup-proxy>
					</q-btn>
				</q-item-section>
			</q-item>
		</div>	
	</div>
</div></template>
<script>
export default{
name:'Datacenter',
components:{},
data(){return {
	show:false,activitys:[],subnum:0,memsubnum:0,memnum:0,usernum:0,actnum:0
}},
mounted(){
	this.getData()
},
methods:{
	getData(){
		this.axios.get('page/home').then(res=>{
			this.subnum = res.subnum
			this.memsubnum = res.memsubnum
			this.memnum = res.memnum
			this.usernum = res.usernum,
			this.actnum = res.actnum
			this.activitys = Object.assign([],[],res.activitys)
		})
	},
	changeArg(mode,aid){
		this.$router.push('/'+mode+'/'+aid)
	},
},
watch:{},
destroy(){},
}
</script>
<style scoped>
.initem{height:80px;}
.sitem{height:100px;width:100px;}
</style>