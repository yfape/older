<template>
	<q-dialog v-model="show" @hide="hidePanel">
		<q-card class="q-pa-sm">
			<q-card-section class="q-pa-none">
				<div class="row items-start">
					<div class="col-md-4 col-12 q-pa-sm">
						<q-input class="q-mb-sm" dense filled label="姓名" v-model="member.name" />
						<q-input class="q-mb-sm" dense filled label="电话" v-model="member.phone" />
						<q-input class="q-mb-sm" dense filled label="编号" v-model="member.union_id" />
						<q-select class="q-mb-sm" dense filled emit-value map-options label="班级" :options="activitys" option-value="aid" option-label="name" v-model="member.aid" />
						<q-item class="bg-grey-2" dense tag="label" clickable v-ripple>
							<q-item-section>
								<q-item-label :lines="1" class="text-grey text-left">
									{{member.valid?'启用':'禁用'}}
								</q-item-label>
							</q-item-section>
							<q-item-section>
								<q-item-label class="text-right">
									<q-toggle :disable="this.member.mid<=0" dense :true-value="1" :false-value="0" v-model="member.valid" @input="changeMemberValid"/>
								</q-item-label>
							</q-item-section>
						</q-item>
					</div>
					<div class="col-md-8 col-12 q-pa-sm">
						<q-table class="no-shadow" :data="subdata" :columns="columns" row-key="sid" :pagination.sync="pagination" @request="getSyncTable">
							<template v-slot:body="props">
								<q-tr :props="props">
						          	<q-td key="sid" :props="props">
						            	{{ props.row.sid }}
						          	</q-td>
						          	<q-td key="name" :props="props">
						            	{{ props.row.name }}
						          	</q-td>
						          	<q-td key="date" :props="props">
						            	{{ props.row.date }}
						          	</q-td>
						          	<q-td key="time" :props="props">
						            	{{props.row.start}} - {{props.row.end}}
						          	</q-td>
						      	</q-tr>
							</template>
						</q-table>
					</div>
				</div>
			</q-card-section>
			<q-card-actions class="row items-center justify-end">
				<q-btn class="q-px-sm" unelevated label="取消" color="grey-6" unev @click="hidePanel" />
				<q-btn class="q-px-sm" label="删除用户" color="red-10" @click="deleteMember"/>
				<q-btn class="q-px-sm" label="保存用户" color="green" @click="saveMember"/>
			</q-card-actions>
		</q-card>
	</q-dialog>
</template>
<script>
export default{
name:'',
props:['activitys'],
components:{},
data(){return {
	show:false,member:{},
	initmember:{mid:-1,name:'',phone:'',valid:1,union_id:'',subs:[],arrive:[]},
	subdata:[],
	columns:[
		{name:'sid',label:'场次编号',align:'left'},
		{name:'name',label:'活动名称',align:'left'},
		{name:'date',label:'日期',align:'left'},
		{name:'time',label:'时间',align:'left'},
	],
	pagination:{
		page:1,rowsPerPage:5,rowsNumber: 0
	},
}},
mounted(){},
methods:{
	showPanel(mid){
		if(mid<0){
			this.member = Object.assign({},{},this.initmember)
			this.subdata = []
			this.show = true
			return
		}
		this.axios.post('getMemeber',{mid:mid,page:this.pagination.page,row:this.pagination.rowsPerPage}).then(res=>{
			this.member = Object.assign({},{},res.member)
			this.subdata = Object.assign([],[],res.subdata)
			this.pagination.rowsNumber = res.count
			this.pagination.rowsPerPage = res.row
			this.pagination.page = res.page
			this.show = true
		})
	},
	getSyncTable(props){
		this.axios.post('getMemeber',{mid:this.member.mid,page:props.pagination.page,row:props.pagination.rowsPerPage}).then(res=>{
			this.member = Object.assign({},{},res.member)
			this.subdata = Object.assign([],[],res.subdata)
			this.pagination.rowsNumber = res.count
			this.pagination.rowsPerPage = res.row
			this.pagination.page = res.page
			this.show = true
		})
	},
	hidePanel(){
		this.subdata = []
		this.member = Object.assign({},{},this.initmember)
		this.pagination = {page:1,rowsPerPage:10,rowsNumber: 0}
		this.show = false
	},
	changeMemberValid(){
		this.axios.post('changeMemberValid',{mid:this.member.mid}).then(res=>{
			this.showPanel(this.member.mid)
			this.$emit('change')
		})
	},
	saveMember(){
		this.axios.post('saveMember',this.member).then(res=>{
			this.$emit('change')
			this.hidePanel()
		})
	},
	deleteMember(){
		this.axios.post('deleteMember',{mid:this.member.mid}).then(res=>{
			this.$emit('change')
			this.hidePanel()
		})
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>q