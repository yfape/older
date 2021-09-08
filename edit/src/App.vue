<!--
 * @Author: your name
 * @Date: 2021-03-02 11:18:03
 * @LastEditTime: 2021-03-09 16:48:48
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\App.vue
-->
<template>
  <div id="app">
    <router-view/>
  </div>
</template>
<script>
export default{
  mounted(){
    this.axios.get('manager/self').then(res => {
      this.$store.state.user = Object.assign({},{},res)
    })
  }
}
</script>
<style>
html,body{height:100%;}
#app {
	height:100%;
  font-family: '黑体', Avenir, Helvetica, Arial, sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  text-align: center;
  color: #2c3e50;
}
</style>
