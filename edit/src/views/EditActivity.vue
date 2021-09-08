<template><div class="q-pa-md">
	<div class="row items-center q-mb-md">
		<span class="text-grey-8">
			<span class="text-grey-5 cursor-pointer" @click="$router.push('/activity')">活动管理</span> > {{aid>0?'编辑活动':'新增活动'}}
		</span>
	</div>
	<div class="lw q-mb-md">
		<q-input filled dense label="活动名称" v-model="activity.name" />
	</div>
	<div class="lw q-mb-md">
		<q-input filled dense label="活动说明" v-model="activity.text" />
	</div>
	<div class="q-mb-md" style="width:200px">
		<upload :img.sync="activity.img" intro="展示图"/>
	</div>
	<div class="lw q-mb-md">
      	<q-select use-input dense filled emit-value map-options label="背景色" v-model="activity.color" :options="colors" option-value="value" option-label="label">
      		<template v-slot:option="scope">
      			<q-item v-bind="scope.itemProps" v-on="scope.itemEvents" :class="['q-py-xs',['bg-'+scope.opt]]">
      			</q-item>
      		</template>
      	</q-select>
	</div>
	<div class="q-mb-md text-left">
		<div class="text-grey">单日活动场次设置</div>
		<q-separator />
	</div>
	<div class="q-mb-md row items-center justify-start">
		<q-tabs dense shrink narrow-indicator v-model="week">
			<q-tab :name="1">周一</q-tab>
			<q-tab :name="2">周二</q-tab>
			<q-tab :name="3">周三</q-tab>
			<q-tab :name="4">周四</q-tab>
			<q-tab :name="5">周五</q-tab>
			<q-tab :name="6">周六</q-tab>
			<q-tab :name="0">周日</q-tab>
		</q-tabs>
	</div>
	<div class="q-mb-md q-px-md text-left">
		<q-item clickable v-ripple v-for="item,index in activity.steps[week]" @click="openCC(index)">
			<q-item-section>
				<q-item-label :lines="3" caption>
					<div class="text-subtitle1 text-bold">时间：{{item.start}} - {{item.end}}</div>
					<div>总人数：{{item.sum}}<span class="q-px-sm"></span>地点：{{item.addr}}<span class="q-px-sm"></span>备注：{{item.tip}}</div>
				</q-item-label>
			</q-item-section>
			<q-item-section thumbnail>
				<q-toggle v-model="item.valid" :true-value="1" :false-value="0" :label="item.valid?'启用':'禁用'"/>				
			</q-item-section>
		</q-item>
	</div>
	<div class="q-mb-md text-left">
		<q-btn icon="o_add" label="添加场次" flat @click="openCC(-1)"/>
	</div>
	<div class="q-mb-md text-left">
		<div class="text-grey">操作</div>
		<q-separator />
	</div>
	<div class="text-left row items-center justify-start">
		<q-btn icon="o_save" :label="(activity.aid<0)?'新增活动':'保存活动'" color="green" @click="saveActivity" class="q-mr-md q-mb-md col-12 col-md-2"/>
		<q-btn v-if="activity.aid>0" icon="o_close" :label="(activity.valid==1)?'禁用活动':'启用活动'" :color="(activity.valid==1)?'warning':'grey-5'" @click="changevalid"  class="q-mr-md q-mb-md col-12 col-md-2"/>
		<q-btn v-if="activity.aid>0" icon="o_delete_forever" class="q-mb-md col-12 col-md-2" label="删除活动" color="red-10">
			<q-popup-edit dark v-model="test">
				<q-btn color="red-10" label="确定要删除吗！" @click="deleteActivity"/>
			</q-popup-edit>
		</q-btn>
	</div>
	<editCC ref="editCC" @save="saveCC" @delete="deleteCC"/>
</div></template>
<script>
import upload from '@/components/upload'
// import editCC from '@/components/editCC'
export default{
name:'EditActivty',
components:{upload,editCC},
props:['tid'],
data(){return {
	activity:{aid:-1,name:'',img:'',week:[],steps:[[],[],[],[],[],[],[]],text:'',color:'',valid:1},action:0,test:'',week:1,
	colors:[
		'red-6','red-9','pink-6','pink-9','purple-6','purple-9','deep-purple-6','deep-purple-9','indigo-6','indigo-9','blue-6','blue-9','light-blue-6','light-blue-9','cyan-6','cyan-9','teal-6','teal-9','green-6','green-9','lime-6','lime-9','yellow-6','yellow-9','amber-6','amber-9','orange-6','orange-9','brown-6','brown-9','grey-6','grey-9','blue-grey-6','blue-grey-9'
	],
}},
mounted(){
	this.getActivity()
},
methods:{
	getActivity(){
		if(this.aid<1){
			return
		}
		this.axios.get('team/'+this.tid).then(res=>{console.log(res);return;
			this.activity = Object.assign({},{},res)
			if(!res.steps){
				this.activity.steps = [[],[],[],[],[],[],[]]
			}
		})
	},
	openCC(i){
		this.action = i
		if(!this.activity.steps[this.week]){
			this.$refs.editCC.showPanel()
		}else{
			this.$refs.editCC.showPanel(this.activity.steps[this.week][i]?this.activity.steps[this.week][i]:'')
		}
	},
	saveCC(obj){
		if(!this.activity.steps[this.week]){
			this.activity.steps[this.week] = []
		}
		if(this.action<0){
			this.activity.steps[this.week].push(obj)
		}else{
			this.$set(this.activity.steps[this.week],this.action,obj)
		}
		this.activity.steps = Object.assign([],[],this.activity.steps)
	},
	deleteCC(){
		this.activity.steps[this.week].splice(this.action,1)
		this.activity.steps = Object.assign([],[],this.activity.steps)
	},
	saveActivity(){
		this.axios.post('saveActivity',this.activity).then(res=>{
			this.$router.push('/activity')
		})
	},
	changevalid(){
		let valid = this.activity.valid ? 0 : 1
		this.axios.post('saveActivity',{aid:this.activity.aid,valid:valid}).then(res=>{
			this.activity.valid = valid
		})
	},
	deleteActivity(){
		this.axios.post('deleteActivity',{aid:this.activity.aid}).then(res=>{
			this.$router.push('/activity')
		})
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>
.lw{width:300px;}
</style>