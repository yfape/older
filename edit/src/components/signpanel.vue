<template>
	<q-dialog v-model="show" @hide="hidePanel" persistent maximized position="bottom">
		<meta name="format-detection" content="telephone=yes"/>
		<q-card class="bg-grey-2" v-if="show">
			<q-bar class="bg-grey-2">
			  <div class="text-grey text-caption text-bold" style="font-size:14px">{{sub.start}} - {{sub.end}} {{sub.statusmsg}}</div>
	          <q-space />
	          <q-btn dense flat icon="close" v-close-popup>
	            <q-tooltip content-class="bg-white text-primary">关闭</q-tooltip>
	          </q-btn>
	        </q-bar>
			<div class="q-py-sm q-px-sm" style="max-width:600px!important;margin:0 auto">
				<div class="row items-center q-mb-sm">
					<div class="col-6 q-pr-xs">
						<div class="bg-white text-center q-py-sm rounded-borders"><span class="text-grey">预约人数</span><br>{{sub.work.length}}</div>
					</div>
					<div class="col-6 q-pl-xs">
						<div class="bg-white text-center q-py-sm rounded-borders"><span class="text-grey">实到人数</span><br>{{arrivenum}}</div>
					</div>
				</div>
				<q-item class="bg-white q-mb-sm" v-for="item in sub.work">
			        <q-item-section avatar>
			        	<q-item-label :lines="1" class="text-subtitle1">{{item.name}} - {{item.union_id}}</q-item-label>
			        	<q-item-label :lines="1" caption class="row items-center"><a :href="'tel:'+item.phone"><q-icon name="o_local_phone" size="14px"/> {{item.phone}}</a></q-item-label>
			        </q-item-section>
			        <q-item-section slide>
			        	<div class='text-right'>
			        		<q-btn :loading="loading" v-if="!item.arrive&&sub.statusmsg!='已结束'" class="q-px-sm" unelevated color="green" label="签到" @click="arrive(item.mid)"/>
			        		<q-btn v-if="!item.arrive&&sub.statusmsg=='已结束'" color="red-10" outline label="缺席"/>
			        		<q-btn v-if="item.arrive" v-else color="grey" flat label="已到达" @click="arrive(item.mid)"/>
			        	</div>
			        </q-item-section>
			    </q-item>
			</div>
		</q-card>
	</q-dialog>
</template>
<script>
export default{
name:'signpanel',
components:{},
data(){return {
	show:false,sub:{},loading:false,arrivenum:0
}},
mounted(){},
methods:{
	showPanel(sid){
		this.axios.post('getSubscribe',{sid,sid}).then(res=>{
			this.sub = Object.assign({},{},res)
			this.setArrivenum()
			this.loading = false
			this.show = true
		}).catch(err=>{
			this.loading = false
		})
	},
	hidePanel(){
		this.show = false
	},
	setArrivenum(){
		let num = 0
		for(let i in this.sub.work){
			if(this.sub.work[i].arrive){
				num += 1
			}
		}
		this.arrivenum = num
	},
	arrive(mid){
		this.loading = true
		this.axios.post('changeArrive',{sid:this.sub.sid,mid:mid}).then(res=>{
			this.showPanel(this.sub.sid)
			this.$emit('change')
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
a{text-decoration: none;color:#000;}
</style>