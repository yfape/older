/*
 * @Author: your name
 * @Date: 2021-02-07 09:21:04
 * @LastEditTime: 2021-02-08 15:19:43
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\main.js
 */
import Vue from 'vue'
import './plugins/axios'
import App from './App.vue'
import router from './router'
import store from './store'
import './quasar'
import '@/plugins/helper.js'

Vue.config.productionTip = false

Vue.prototype.dateformat = function(date,s='-',mode=0) {
  let y = date.getFullYear()
    let m = date.getMonth()+1+''
    let d = date.getDate()+''
    if(mode==1){
      m = (m.length==1) ? ('0'+m) : m
      d = (d.length==1) ? ('0'+d) : d
    }
    return y+s+m+s+d
}

Vue.prototype.timesub = function(start,end) {
  let endarr = end.split(':')
  let end_hour = parseInt(endarr[0])
  let end_min = parseInt(endarr[1])

  let startarr = start.split(':')
  let start_hour = parseInt(startarr[0])
  let start_min = parseInt(startarr[1])

  if(start_min > end_min){
    end_min = end_min + 60
    end_hour = end_hour - 1
  }

  let min = end_min - start_min
  let hour = end_hour - start_hour

  return hour+'时'+min+'分'
}

Vue.prototype.AItip = function(title,actions){
  if(!title){
    return
  }
  this.$q.notify({
      message: title,
      position: 'top',
      html: true,
      timeout:actions?0:2000,
      color: 'info',
      actions:actions
  })
}

Date.prototype.Format = function(fmt) 
{ //author: meizz 
  var o = { 
    "M+" : this.getMonth()+1,                 //月份 
    "d+" : this.getDate(),                    //日 
    "h+" : this.getHours(),                   //小时 
    "m+" : this.getMinutes(),                 //分 
    "s+" : this.getSeconds(),                 //秒 
    "q+" : Math.floor((this.getMonth()+3)/3), //季度 
    "S"  : this.getMilliseconds()             //毫秒 
  }; 
  if(/(y+)/.test(fmt)) 
    fmt=fmt.replace(RegExp.$1, (this.getFullYear()+"").substr(4 - RegExp.$1.length)); 
  for(var k in o) 
    if(new RegExp("("+ k +")").test(fmt)) 
  fmt = fmt.replace(RegExp.$1, (RegExp.$1.length==1) ? (o[k]) : (("00"+ o[k]).substr((""+ o[k]).length))); 
  return fmt; 
}

var vue = new Vue({
  router,
  store,
  render: h => h(App)
}).$mount('#app')

export default vue