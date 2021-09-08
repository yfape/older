<!--
 * @Author: your name
 * @Date: 2021-02-07 01:10:12
 * @LastEditTime: 2021-02-08 10:44:13
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\editCC.vue
-->
<template>
<q-dialog v-model="show" @hide="hidePanel">
	<q-card style="width:300px" class="q-px-sm">
		<q-card-section>
			<q-input class="q-mb-md" dense filled label="开始时间" v-model="obj.start">
				<template v-slot:append>
					<q-btn dense flat icon="o_access_time">
						<q-popup-proxy :offset="[0, 1000]">
							<q-time v-model="obj.start"/>
						</q-popup-proxy>
					</q-btn>
				</template>
			</q-input>
			<q-input class="q-mb-md" dense filled label="结束时间" v-model="obj.end">
				<template v-slot:append>
					<q-btn dense flat icon="o_access_time">
						<q-popup-proxy :offset="[0, 1000]">
							<q-time v-model="obj.end"/>
						</q-popup-proxy>
					</q-btn>
				</template>
			</q-input>
			<q-input class="q-mb-md" dense filled label="地点" v-model="obj.addr"></q-input>
			<q-input class="q-mb-md" dense filled label="总人数" type="number" v-model="obj.count" :max="100" :min="0"></q-input>
			<q-input class="q-mb-md" dense filled label="备注" type="textarea" v-model="obj.tip"></q-input>
			<div><q-btn class="full-width" label="保存" dense color="primary" @click="save"/></div>
			<div class="q-mt-sm" v-if="obj.start"><q-btn class="full-width text-primary bg-grey-4" label="删除" dense @click="deleteItem"/></div>
		</q-card-section>
	</q-card>
</q-dialog>
</template>
<script>
export default{
name:'editCC',
components:{},
data(){return {
	show:false,obj:{},
	initobj:{start:'',end:'',addr:'',sum:null,tip:''}
}},
mounted(){},
methods:{
	showPanel(obj,temp){
		if(obj){
			this.obj = Object.assign({},{},obj)
		}else{
			this.obj = Object.assign({},{},this.initobj)
			this.obj.addr = temp.addr
			this.obj.count = temp.count
		}
		this.show = true
	},
	hidePanel(){
		this.show = false
		this.obj = Object.assign({},{},this.initobj)
	},
	save(){
		if(!this.obj.start){
			return;
		}
		this.$emit('save',this.obj)
		this.hidePanel()
	},
	deleteItem(){
		this.$emit('delete')
		this.hidePanel()
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>