<template>
	<q-dialog v-model="show" @hide="hidePanel" maximized>
		<div class="row items-center justify-center">
			<div class="col-md-3 col-12 q-pa-sm">
				<div class="bg-white q-px-md q-py-sm">
					<div class="text-subtitle1">
						场次ID.{{sub.sid}}
					</div>
					<q-input class="q-mb-sm full-width" readonly dense filled label="日期" v-model="sub.date"/>
					<q-input class="q-mb-sm full-width" dense filled label="开始时间" v-model="sub.start">
						<template v-slot:append>
							<q-btn dense flat icon="o_access_time">
								<q-popup-proxy :offset="[0, 1000]">
									<q-time v-model="sub.start"/>
								</q-popup-proxy>
							</q-btn>
						</template>
					</q-input>
					<q-input class="q-mb-sm full-width" dense filled label="结束时间" v-model="sub.end">
						<template v-slot:append>
							<q-btn dense flat icon="o_access_time">
								<q-popup-proxy :offset="[0, 1000]">
									<q-time v-model="sub.end"/>
								</q-popup-proxy>
							</q-btn>
						</template>
					</q-input>
					<q-input class="q-mb-sm full-width" dense filled label="地址" v-model="sub.addr"/>
					<q-input class="q-mb-sm full-width" type="number" dense filled label="总人数" v-model="sub.sum"/>
					<q-input class="q-mb-sm full-width" dense filled label="备注" v-model="sub.tip"/>
					<q-separator class="q-my-sm"/>

					<div class="row items-center justify-center">
						<div class="col-6 q-pa-sm">
							<q-btn class="full-width" v-if="sub.status!=9" :label="sub.status==0?'启用场次':'禁用场次'" :color="sub.status==0?'blue-7':'grey-6'" icon="o_close" outline @click="changeSubStatus"/>
						</div>
						<div class="col-6 q-pa-sm">
							<q-btn class="full-width" icon="o_delete" label="删除场次" color="red-10" outline @click="deleteSub"/>
						</div>
						<div class="col-6 q-pa-sm">
							<q-btn class="full-width" label="返回" color="grey-6" outline v-close-popup/>
						</div>
						<div class="col-6 q-pa-sm">
							<q-btn class="full-width" label="保存场次" color="green" icon="o_save" outline @click="saveSub"/>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6 col-12 q-pa-sm">
				<div class="bg-white q-px-md q-py-sm">
					<div class="text-subtitle1">预约名单 总人数:{{sub.work.length}} 实到:{{arrivenum}} 缺席:{{sub.work.length-arrivenum}}</div>
					<div style="min-height:100px;max-height:534px;overflow-y:auto;">
						<q-table hide-bottom :loading="loading" class="no-shadow  col-md-6 col-12" :data="sub.work" :columns="columns" row-key="mid" :pagination="{rowsPerPage:0}">
							<template v-slot:body="props">
						        <q-tr :props="props">
						          <q-td key="union_id" :props="props">
						            {{ props.row.union_id }}
						          </q-td>
						          <q-td key="name" :props="props">
						            {{ props.row.name }}
						          </q-td>
						          <q-td key="phone" :props="props">
						            {{ props.row.phone }}
						          </q-td>
						          <q-td key="action" :props="props">
						            <q-chip dense square dark :color="props.row.arrive?'green':'red-10'" :label="props.row.arrive?'已到场':'未到场'" clickable @click="changeArrive(props.row.mid)"/>
						          </q-td>
						      </q-tr>
						  </template>
						</q-table>
					</div>
				</div>
			</div>
		</div>
	</q-dialog>
</template>
<script>
export default{
name:'editSub',
components:{},
data(){return {
	show:false,loading:false,
	sub:{work:[]},arrivenum:0,
	initsub:{sid:-1,aid:0,date:'',start:'',end:'',addr:'',sum:0,tip:'',work:[],status:1},
	columns:[
		{name:'union_id',label:'用户编码',align:'left'},
		{name:'name',label:'姓名',align:'left'},
		{name:'phone',label:'电话',align:'left'},
		{name:'action',label:'操作',align:'center'}
	],
}},
mounted(){},
methods:{
	showPanel(sid,aid=0,date=''){
		if(sid == -1){
			this.sub = Object.assign({},{},this.initsub)
			this.sub.aid = aid
			this.sub.date = date
			this.show = true
			return
		}

		this.axios.post('getSubscribe',{sid:sid}).then(res=>{
			this.sub = Object.assign({},{},res)
			this.countArrive()
			this.show = true
		})
	},
	hidePanel(){
		this.sub = Object.assign({},{},this.initsub)
		this.show = false
	},
	countArrive(){
		let arrivenum = 0
		for(let i in this.sub.work){
			if(this.sub.work[i].arrive){
				arrivenum += 1
			}
		}
		this.arrivenum = arrivenum
	},
	changeArrive(mid){
		this.axios.post('changeArrive',{mid:mid,sid:this.sub.sid}).then(res=>{
			this.showPanel(this.sub.sid)
		})
	},
	saveSub(){
		this.axios.post('saveSub',this.sub).then(res=>{
			this.$emit('change')
			this.hidePanel()
		})
	},
	changeSubStatus(){
		this.axios.post('changeSubStatus',{sid:this.sub.sid}).then(res=>{
			this.$emit('change')
			this.showPanel(this.sub.sid)
		})
	},
	deleteSub(){
		this.axios.post('deleteSub',{sid:this.sub.sid}).then(res=>{
			this.$emit('change')
			this.hidePanel()
		})
	},
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>