// pages/index/bm/bm.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    step:-1, dialogShow: false,
    content:[], backup:[],logteam:{},
    selected: '', user:{}, buttons: [{text: '取消'}, {text: '确定'}],
    team:{},team2:{}
  },

  //new functions
  getTeams(category=1){
    app.request('team/category/'+category,'','GET').then(res => {
      this.setData({content: res.data,backup:res.data,step:1})
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    });
  },
  goStep1(e){
    this.getTeams(e.currentTarget.dataset.category)
  },
  backStep0(){
    this.getUserTeams()
  },
  joinTeam(e){
    this.setData({selected: e.currentTarget.dataset.team, dialogShow:true})
  },
  tapDialogButton(e){
    if(e.detail.index == 0){
      this.setData({selected:'',dialogShow:false})
      return
    }
    app.request('team/'+this.data.selected.tid).then(res => {
      if(this.data.selected.category == 1){
        app.globalData.identity.tid = this.data.selected.tid
        this.setData({dialogShow:false,'user.tid':this.data.selected.tid})
      }else if(this.data.selected.category == 2){
        app.globalData.identity.t2id = this.data.selected.tid
        this.setData({dialogShow:false,'user.t2id':this.data.selected.tid})
      }
      this.backStep0()
      app.tipToast(res.data.msg, '报名成功')
    }).catch(err => {
      app.tipToast(err.data.msg, '网络错误')
      this.setData({dialogShow:false})
    })
  },
  getUserTeams(){
    app.request('team/user','','GET').then(res => {
      this.setData({team:res.data.team, team2:res.data.team2, step:0})
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  goYy(){
    wx.switchTab({  url: '/pages/user/yy/yy'    })
  },
  onLoad: function (options) {
    this.setData({user: app.globalData.identity})
  },
  onShow: function () {
    this.getUserTeams()
  }
})