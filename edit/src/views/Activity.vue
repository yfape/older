<template><div class="q-pa-md" v-if="show">
	<div class="q-pa-md">
		<div class="row items-start justify-start">
			<q-expansion-item group="somegroup" :class="[['col-sm-5 col-md-4 col-lg-3 col-xl-2 col-12 q-mr-md q-mb-md q-pa-none shadow-2'],['bg-'+item.color]]" dark v-for="item in activitys" style="border-radius:10px;overflow:hidden}">
		        <template v-slot:header>
					<q-item-section avater>
						<q-img style="width:80px;" :ratio="1" contain :src="item.img"/>
					</q-item-section>
					<q-item-section class="text-left">
						<q-item-label :lines="1" class="text-subtitle1">{{item.name}}</q-item-label>
						<q-item-label :lines="2" caption>
							{{item.text}}
						</q-item-label>
					</q-item-section>
				</template>
				<div class="bg-white row items-center q-py-xs text-grey-7" style="border-radius:0 0 10px 10px">
					<q-btn flat label="活动设置" class="col" @click="showEditPanel(item.aid)"/>
					<q-separator vertical inset/>
					<q-btn flat label="日程管理" class="col" @click="$router.push('/subscribe/'+item.aid)"/>
					<q-separator vertical inset/>
					<q-btn flat label="考勤" class="col" @click="$router.push('/sign/'+item.aid)"/>
				</div>

			</q-expansion-item>
		
			<q-item outlined clickable v-ripple class="full-height col-sm-5 col-md-4 col-lg-3 col-xl-2 col-12 rounded-borders no-shadow q-mr-md q-mb-md self-center" style="border:1px solid #ABA8A8" @click="showEditPanel(-1)">
				<q-item-section avater>
					<q-icon size="60px" name="o_add"/>
				</q-item-section>
				<q-item-section class="text-left">
					<q-item-label :lines="1" class="text-subtitle1">新增活动</q-item-label>
				</q-item-section>
			</q-item>
		</div>
	</div>
	
</div></template>
<script>
export default{
name:'Activity',
data(){return {
	show:false,activitys:[]
}},
mounted(){
	this.getActivityList()
},
methods:{
	getActivityList(){
		this.axios.post('getActivityList').then(res=>{
			this.activitys = Object.assign([],[],res)
		})
		this.show = true
	},
	showEditPanel(aid){
		this.$router.push('/editactivity/'+aid)
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>
.lh{height:80px}
</style>