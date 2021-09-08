<template><div>
	<q-btn class="full-width" icon="o_vertical_align_bottom" :label="label" :color="color" @click="openfileselect"/>
	<input type="file" :id="id" hidden @change="updateFile" :accept="type"/>
</div></template>
<script>
export default{
name:'uploadbtn',
components:{},
props:['label','color'],
data(){return {
	id:'image',limit:2,
	type:'.xls,.xlsx',
}},
mounted(){
	this.id = Math.random().toString(36).slice(2);
},
methods:{
	openfileselect(){
		let file = document.getElementById(this.id);
       	file.click();
	},
	duageFileSize(file){
		if(file.size/1024/1024 >= this.limit){
			return false;
		}else{
			return true;
		}
	},
	updateFile(){
		var filed = document.getElementById(this.id);
		var param = new FormData();
		if(!this.duageFileSize(filed.files[0])){
			this.AItip('文件大于2M，请压缩后上传');return;
		}
		param.append("file",filed.files[0]);
		this.axios.post('importMember',param).then(response=>{
			this.uploadbackIn(response)
			this.$q.loading.hide()
		}).catch(err=>{
			this.$q.loading.hide()
		})
	},
	uploadbackIn(imgs){
		this.$emit('change')
	},
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>