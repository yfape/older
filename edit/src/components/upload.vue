<!--
 * @Author: your name
 * @Date: 2021-02-07 01:10:12
 * @LastEditTime: 2021-02-08 01:35:09
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\components\upload.vue
-->
<template><div>
	<q-img class="cursor-pointer" :ratio="1" contain :src="img?img:'/image/default.jpg'" @click="openfileselect"/>
	<input multiple type="file" :id="id" hidden @change="updateFile" :accept="type"/>
</div></template>
<script>
export default{
name:'upload',
components:{},
props:['intro','img'],
data(){return {
	id:'image',limit:2,
	type:'image/jpeg,image/png,image/x-icon,image/gif,application/msword,text/plain,application/pdf,aplication/zip',
}},
mounted(){
	this.id = Math.random().toString(36).slice(2);
},
methods:{
	openfileselect(){
		let file = document.getElementById(this.id);
       	file.click();
	},
	duageFileType(file){
		let res = this.type.indexOf(file.type)
		if(res>=0){
			return true
		}else{
			return false
		}
	},
	duageFileSize(file){
		if(file.size/1024/1024 >= this.limit){
			return false;
		}else{
			return true;
		}
	},
	updateFile(){
		this.$q.loading.show({
			'message':'文件上传中'
		})
		var filed = document.getElementById(this.id);
		var param = new FormData();
		for(let i=0;i<filed.files.length;i++){
			if(!this.duageFileSize(filed.files[i])){
				this.AItip('文件大于2M，请压缩后上传');return;
			}
			if(!this.duageFileType(filed.files[i])){
				this.AItip('文件格式不被允许');return;
			}
			param.append("file"+i,filed.files[i]);
		}
		if(!param){
			return;
		}
		this.axios.post('upload',param).then(response=>{
			this.uploadbackIn(response)
			this.$q.loading.hide()
		}).catch(err=>{
			this.$q.loading.hide()
		})
	},
	uploadbackIn(imgs){
		this.$emit('update:img',imgs[0])
		this.$emit('reflesh')
	}
},
watch:{},
destroy(){},
}
</script>
<style scoped>

</style>