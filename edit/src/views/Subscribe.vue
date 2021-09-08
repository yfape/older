<template><div class="q-pa-md" v-if="show">
	<div class="row items-center justify-start" style="position:relative;z-index:1">
		<q-item :class="['col-12 col-md-3 q-pa-md shadow-2 rounded-borders',['bg-'+activity.color]]" dark>
			<q-item-section avater>
				<q-img :src="activity.img" contain :ratio="1" style="width:80px;"/>
			</q-item-section>
			<q-item-section>
				<q-item-label class="text-subtitle1 text-bold text-left">
					{{activity.name}}
				</q-item-label>
				<q-item-label class="text-left" caption>
					{{activity.text}}
				</q-item-label>
			</q-item-section>
		</q-item>
	</div>

	<div class="row items-center justify-start q-mb-sm" style="position:relative;z-index:0">
		<q-item class="col-md-3 col-12 bg-white q-pa-sm">
			<q-item-section >
				<div>
					<span class="text-green text-bold">{{activity.name}}</span> 预约时间已发布至 <span class="text-green text-bold">{{lastday}}</span>
				</div>
			</q-item-section>
		</q-item>
	</div>
	<div class="row items-center justify-start q-mb-md">
		<q-item class="col-md-3 col-12 bg-white q-pa-sm">
			<q-item-section>
				<div class="q-py-sm">
					您希望更新上线预约时间从 
					<span class="cursor-pointer text-green text-bold">{{startdate?startdate:'________'}}
						<q-popup-proxy>
							<q-date v-model="startdate" :options="optionsFn"/>
						</q-popup-proxy>
					</span> 
					至 
					<span class="cursor-pointer text-green text-bold">{{enddate?enddate:'________'}}
						<q-popup-proxy>
							<q-date v-model="enddate" :options="optionsFn"/>
						</q-popup-proxy>
					</span>
				</div>
				<div class="q-px-md">
					<q-btn class="full-width" label="确定更新" dense color="red-10" @click="syncSub"/>
				</div>
			</q-item-section>
		</q-item>
	</div>

	<q-separator class="q-mt-sm"/>
	<div class="text-left text-grey q-mb-sm">单日场次管理</div>
	<div class="row items-center justify-start">
		<q-table :loading="loading" class="col-md-6 col-12 no-shadow" :data="subs" :columns="columns" row-key="name" :pagination="{rowsPerPage:0}">
	    	<template v-slot:top="props">
	    		<q-input dense class="full-width" filled v-model="date" label="日期" mask="date" :rules="['date']">
			      <template v-slot:append>
			        <q-icon name="event" class="cursor-pointer">
			          <q-popup-proxy ref="qDateProxy" transition-show="scale" transition-hide="scale">
			            <q-date v-model="date" @input="() => $refs.qDateProxy.hide()" />
			          </q-popup-proxy>
			        </q-icon>
			      </template>
			    </q-input>
	    	</template>
	    	<template v-slot:body="props" :disable="!props.row.valid" >
		        <q-tr :props="props" class="cursor-pointer" @click="$refs.editSub.showPanel(props.row.sid)">
		          <q-td key="sid" :props="props">
		            {{ props.row.sid }}
		          </q-td>
		          <q-td key="start" :props="props">
		            {{ props.row.start }}
		          </q-td>
		          <q-td key="end" :props="props">
		            {{ props.row.end }}
		          </q-td>
		          <q-td key="attend" :props="props">
		            {{ props.row.work.length }}
		          </q-td>
		          <q-td key="status" :props="props">
		          	<q-chip dark square v-if="props.row.statusmsg=='进行中'" color="green" :label="props.row.statusmsg"/>
		          	<q-chip dark square v-else color="grey-4" :label="props.row.statusmsg"/>
		          </q-td>
		      </q-tr>
		  </template>
		  <template v-slot:bottom>
		  	<q-btn dense class="q-px-sm" label="新增当日场次" icon="o_add" color="green-7" @click="$refs.editSub.showPanel(-1,activity.aid,date)"/>
		  </template>
	    </q-table>
	    <div class="col-1" style="height:1px"></div>
	</div>
	<editSub ref="editSub" @change="getDateSub"/>
</div></template>
<script>
import editSub from '@/components/editSub'

export default{
name:'subscribe',
components:{editSub},
props:['aid'],
data(){return {
	show:false,activity:{},date:'',subs:[],loading:false,startdate:'',enddate:'',today:'',lastday:'',
	columns:[{name:'sid',label:'编号',align:'left'},{name:'start',label:'开始',align:'left'},{name:'end',label:'结束',align:'left'},{name:'attend',label:'报名人数',align:'left'},{name:'status',label:'状态'}]
}},
mounted(){
	this.getActivity()
},
methods:{
	getActivity(){
		this.axios.post('getActivity',{aid:this.aid}).then(res=>{
			this.activity = Object.assign({},{},res)
			this.getToday()
			this.getLastDay()
		})	
	},
	getToday(){
		let date = new Date()
		this.date = this.dateformat(date,'/',1)
		this.today = this.date + ''
	},
	getLastDay(){
		this.axios.post('getLastSubDay',{aid:this.aid}).then(res=>{
			this.lastday = res
			this.show = true
		})
	},
	getDateSub(){
		this.loading = true
		this.axios.post('getSubList',{aid:this.aid,date:this.date}).then(res=>{
			this.subs = Object.assign([],[],res)
			this.loading = false
		})
	},
	syncSub(){
		this.axios.post('syncSub',{aid:this.aid,startdate:this.startdate,enddate:this.enddate}).then(res=>{
			this.lastday = res
			this.seldate = ''
		})
	},
	optionsFn(date){
		return date>this.today;
	}
},
watch:{
	'date'(){
		this.getDateSub()
	}
},
destroy(){},
}
</script>
<style scoped>

</style>