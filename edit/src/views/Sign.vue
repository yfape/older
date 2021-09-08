<template><div class="q-pa-md row items-center justify-center">
	<div class="col-sm-5 col-md-4 col-lg-3 col-xl-2 col-12">


		<q-item :class="[['q-mb-md shadow-2'],['bg-'+activity.color]]" dark style="border-radius:10px;overflow:hidden">
			<q-item-section avater>
				<q-img style="width:80px;" :ratio="1" contain :src="activity.img"/>
			</q-item-section>
			<q-item-section class="text-left">
				<q-item-label :lines="1" class="text-subtitle1 text-bold">{{activity.name}}</q-item-label>
				<q-item-label :lines="2" class="text-subtitle1">
					{{today}}
				</q-item-label>
			</q-item-section>
		</q-item>

		<q-item clickable class="bg-white rounded-borders q-mb-sm row" v-ripple v-for="item in subs" :disabled="item.status!=1" @click="$refs.signpanel.showPanel(item.sid)">
			<div class="col-12">
				<div class="row items-center justify-between">
					<div class="col-7 text-left">{{item.start}} - {{item.end}}</div>
					<div class="col-5 text-right">
						<q-chip label="进行中" v-if="item.statusmsg=='进行中'" color="green" dense dark square/>
						<q-chip :label="item.statusmsg" v-else="item.status==0" color="grey" dense dark square/>
					</div>
				</div>
				<q-separator inset class="q-my-sm"/>
				<div class="row q-py-none items-center justify-between text-grey text-caption">
					<div class="col-4 text-left">地址</div>
					<div class="col-8 text-right">{{item.addr}}</div>
				</div>
				<div class="row q-py-none items-center justify-between text-grey text-caption">
					<div class="col-4 text-left">预约人数:{{item.work.length}}</div>
				</div>
			</div>
		</q-item>

		<div class="text-center text-grey" v-if="subs.length<=0">本日未设置场次</div>

	</div>
	
	<signpanel ref="signpanel" @change="getSubs"/>	

</div></template>
<script>
import signpanel from '@/components/signpanel'

export default{
name:'Sign',
components:{signpanel},
props:['aid'],
data(){return {
	show:false,activity:{},today:'',subs:[],
}},
mounted(){
	this.getToday()
	this.getActivity()
},
methods:{
	getToday(){
		this.today = this.dateformat(new Date())
	},
	getActivity(){
		this.axios.post('getActivity',{aid:this.aid}).then(res=>{
			this.activity = Object.assign({},{},res)
			this.getSubs()
		})
	},
	getSubs(){
		this.axios.post('getSubList',{aid:this.aid,date:this.today}).then(res=>{
			this.subs = res
			this.show = true
		})
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>