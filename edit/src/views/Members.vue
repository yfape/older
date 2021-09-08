<!--
 * @Author: your name
 * @Date: 2021-03-02 11:18:03
 * @LastEditTime: 2021-03-09 01:59:26
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\Members.vue
-->
<template><div class="q-pa-md">
	<q-card class="q-pa-md no-shadow">
		<div class="row items-center">
			<uploadbtn label="导入用户" color="red-10" class="q-mr-md q-mb-md col-md-2 col-12" @change=""/>
			<q-btn label="新增用户" class="q-mr-md q-mb-md col-md-2 col-12" icon="o_add" color="green-6" @click="$refs.userpanel.showPanel()" />
			<q-btn label="导出数据" class="q-mr-md q-mb-md col-md-2 col-12" icon="outbox" color="blue-6" @click="output" />
			<q-input class="col-md-3 col-12 q-mr-md q-mb-md" dense filled label="模糊搜索(卡号、姓名、电话)" v-model="search">
				<template v-slot:append>
					<q-btn v-if="search" dense flat icon="o_close" @click="clearSearch"/>
					<q-btn dense flat icon="o_search" @click="getData()"/>
				</template>
			</q-input>
		</div>
	</q-card>
	<q-table class="no-shadow" :loading="loading" :data="users" :columns="columns" row-key="mid" :pagination.sync="pagination" @request="getList">
		<template v-slot:body="props">
	        <q-tr :props="props">
	          <q-td key="incode" :props="props">
	            {{ props.row.incode }}
	          </q-td>
	          <q-td key="name" :props="props">
	            {{ props.row.name }}
	          </q-td>
	          <q-td key="phone" :props="props">
	            {{ props.row.phone }}
	          </q-td>
	          <q-td key="tid" :props="props">
	            {{ props.row.teamname }}
	          </q-td>
			  <q-td key="t2id" :props="props">
	            {{ props.row.team2name }}
	          </q-td>
			  <q-td key="single" :props="props">
	            {{ props.row.singleteam }}
	          </q-td>
	          <q-td key="action" :props="props">
	         	<q-btn dense color="blue-7" class="q-px-sm q-mr-sm" icon="o_edit" label="编辑" @click="openMember(props.row)"/>
				<q-btn color="red-10" label="删除" dense icon="o_delete" class="q-px-sm">
					<q-popup-proxy transition-show="flip-up" transition-hide="flip-down">
						<q-banner class="bg-red-9 text-white">
						<template v-slot:avatar>
							<q-icon name="privacy_tip" />
						</template>
						<div class="row items-center">
							<span>您确定要删除吗？</span>
							<q-btn dense flat icon="done_outline" @click="deleteUser(props.row)" v-close-popup/>
						</div>
						</q-banner>
					</q-popup-proxy>
				</q-btn>
	          </q-td>
	      </q-tr>
	  </template>
	</q-table>
	<userpanel ref="userpanel" @change="getData"/>
</div></template>
<script>
import userpanel from '@/components/user/userpanel'
import uploadbtn from '@/components/uploadbtn'
export default{
name:'Members',
components:{userpanel,uploadbtn},
data(){return {
	show:false,loading:false,search:'',
	columns:[
		{name:'incode',label:'卡号',align:'left'},
		{name:'name',label:'姓名',align:'left'},
		{name:'phone',label:'电话',align:'left'},
		{name:'tid',label:'日常班',align:'left'},
		{name:'t2id',label:'教学班',align:'left'},
		{name:'single',label:'单科',align:'left'},
		{name:'action',label:'操作',align:'center'}
	],
	users:[],
	pagination:{
        sortBy: 'desc',
        descending: false,
        page: 1,
        rowsPerPage: 10,
        rowsNumber: 0
    },
	activitys:[],
	search:'',aid:'',
}},
mounted(){
	this.getData()
},
methods:{
	getList(props){
        const { page, rowsPerPage, sortBy, descending } = props.pagination
        this.getData(rowsPerPage, page)
    },
    getData(rowsPerPage = this.pagination.rowsPerPage, page = this.pagination.page){
		console.log(this.pagination)
        this.loading = true
        this.axios.get(`users/${rowsPerPage}/${page}`,{noloading:true,params:{
			search:this.search
		}}).then(res => {
            this.users = Object.assign([],[],res.data)
            this.pagination.page = res.current_page
            this.pagination.rowsNumber = res.total
            this.pagination.rowsPerPage = res.per_page
            this.loading = false
        }).catch(err => {
            this.loading = false
        })
    },
	changeValid(uid){
		this.axios.post(`user/valid/${uid}`).then(res => {
			this.getData()
		})
	},
	openMember(user){
		this.$refs.userpanel.showPanel(user)
	},
	clearSearch(){
		this.search = ''
		this.getData()
	},
	deleteUser(user){
		this.axios.delete(`user/${user.uid}`).then(res => {
			this.getData()
		})
	},
	output(){
		this.axios.post('output/users').then(res => {
			window.open(res)
		})
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>