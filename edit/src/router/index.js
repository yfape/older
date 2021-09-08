/*
 * @Author: your name
 * @Date: 2021-02-06 21:29:45
 * @LastEditTime: 2021-09-07 10:37:51
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\router\index.js
 */
import Vue from 'vue'
import VueRouter from 'vue-router'
import Home from '@/views/Home'
import Login from '@/views/Login'
import Datacenter from '@/views/Datacenter'
import Activity from '@/views/Activity'
// import EditActivity from '@/views/EditActivity'
import Subscribe from '@/views/Subscribe'
import Members from '@/views/Members'
import Sign from '@/views/Sign'
import User from '@/views/User'
import Users from '@/views/Users'
import DataOut from '@/views/DataOut'

import TeamManager from '@/views/TeamManager'
import SectionManager from '@/views/SectionManager'

import store from '@/store'

Vue.use(VueRouter)

const routes = [
  {path: '/', name: 'Home', component: Home , redirect:'/teammanager', children:[
      {path: '/datacenter', name: 'Datacenter', component: Datacenter },
      {path: '/activity', name: 'Activity', component: Activity },
      // {path: '/editactivity/:tid', name: 'EditActivity', component: EditActivity, props:true},
      {path: '/subscribe/:aid', name: 'Subscribe', component: Subscribe, props:true},
      {path: '/members', name: 'Members', component: Members },
      {path: '/sign/:aid', name: 'Sign', component: Sign, props:true},
      {path: '/user', name: 'User', component: User },
      {path: '/users', name: 'Users', component: Users },
      {path: '/dataout', name: 'DataOut', component: DataOut },
      {path: '/teammanager', name: 'TeamManager', component: TeamManager },
      {path: '/sectionmanager/:tid', name: 'SectionManager', component: SectionManager, props: true}
  ]},
  {path: '/login', name: 'Login', component: Login },
 
]

const router = new VueRouter({
  routes
})

function lStorage(name,data=''){
  if(data){
    localStorage.setItem(name,JSON.stringify(data))
    return true
  }else{
    return localStorage.getItem(name)?JSON.parse(localStorage.getItem(name)):false
  }
}
function checkToken(){
  if(!store.state.token){
    store.state.token = lStorage('token')
  }
  return store.state.token?true:false
}
router.beforeEach((to,form,next)=>{
  store.state.pagemode = 1
  if(['SectionManager'].indexOf(to.name) !== -1){
    store.state.pagemode = 2
  }
  if(to.name=='Login'){
    next()
  }else{
    checkToken()?next():next('/login')
  }
})

export default router
