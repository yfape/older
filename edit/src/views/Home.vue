<!--
 * @Author: your name
 * @Date: 2021-02-06 21:29:45
 * @LastEditTime: 2021-03-09 17:20:49
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\views\Home.vue
-->
<template>
  <q-layout view="hHh lpR fFf" class="bg-grey-2">
    <q-header elevated class="bg-blue-8 text-white">
      <q-toolbar>
        <q-btn v-if="$store.state.pagemode==1" dense flat round icon="menu" @click="left = !left" />
        <q-btn v-else flat icon="navigate_before" label="后退" @click="$router.back(-1)" />
        <q-toolbar-title class="row items-center justify-center">
          报名预约系统管理后台
        </q-toolbar-title>
        <q-btn flat dense icon="o_exit_to_app" @click="exit"/>
      </q-toolbar>
    </q-header>

    <q-drawer v-if="$store.state.pagemode==1" show-if-above v-model="left" :width="200" side="left" bordered>
      <q-tabs class="text-grey-7" vertical inline-label>
        <!-- <q-route-tab icon="o_donut_large" label="数据中心" to="/datacenter" exact/> -->
        <q-route-tab icon="o_view_day" label="班队管理" to="/teammanager" exact/>
        <q-route-tab icon="o_groups" label="用户管理" to="/members" exact/>
        <q-route-tab v-if="$store.state.user.isadmin" class="" icon="o_admin_panel_settings" label="管理员账号" to="/users" exact/>

        <div class="text-caption text-grey text-left">个人</div>
        <q-route-tab class="" icon="o_person_outline" label="个人设置" to="/user" exact/>
        
      </q-tabs>
    </q-drawer>
    <q-page-container>
      <router-view/>
    </q-page-container>
  </q-layout>
</template>

<script>
export default {
name: 'Home',
components: {},
data (){return {
  left:false,
}},
mounted(){},
methods:{
  exit(){
    localStorage.setItem('token','')
    this.$store.state.token = ''
    this.$router.replace('/login')
  }
}
}
</script>
