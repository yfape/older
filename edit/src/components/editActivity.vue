<template>
	<q-dialog v-model="show" @hide="hidePanel" position="bottom">
		<q-card class="q-pa-md row items-center" style="min-width:900px">
			<upload intro="活动图片" img=""/>
		</q-card>
	</q-dialog>
</template>
<script>
import upload from '@/components/upload'
export default{
name:'editActivity',
components:{upload},
data(){return {
	show:false,img:''
}},
mounted(){},
methods:{
	showPanel(aid){
		this.show = true
	},
	hidePanel(){
		this.show = false
	},
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>