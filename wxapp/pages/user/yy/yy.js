// pages/index/yy/yy.js
import wxbarcode from 'wxbarcode'
var app = getApp();
Page({
  /**
   * 页面的初始数据
   */
  data: {
    show: false,
    indexdata:[],
    noteam: true,
    indextext:'今天，您没有活动',
    welcometext:'',
    ewmdialogshow: false,
    oneButton: [{text: '确定'}],
  },
  openMyewm(){
    wxbarcode.qrcode('myewm', 'uid/'+app.globalData.identity.uid, 300, 300);
    this.setData({ewmdialogshow: true})
  },
  tapDialogButton(){
    this.setData({ewmdialogshow:false})
  },
  setWelcome(){
    let date = new Date()
    var hour = date.getHours()
    let text = ''
    switch(true){
      case (hour<=3): text='深夜';break;
      case (hour<=6): text='凌晨';break;
      case (hour<=11): text='上午';break;
      case (hour<=13): text='中午';break;
      case (hour<=18): text='下午';break;
      case (hour<=24): text='晚上';break;
      default: text='您';break;
    }
    this.setData({welcometext: text})
  },
  getIndex(){
    app.request('record/uncompletes', '','GET').then(res => {
      let arr = this.judgeToday(res.data)
      this.setData({
        indexdata: arr,
        show: true
      })
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  judgeToday(arr){
    let date = new Date();
    let datetext = date.Format('yyyy/MM/dd');
    
    for(let i=0;i<arr.length;i++){
      if(arr[i].section.date == datetext){
        arr[i].section.date = '今日';
        this.setData({indextext:'今日有活动'});
      }
    }
    return arr;
  },
  openRecord(e){
    this.setData({recordshow:true})
    wxbarcode.qrcode('ewm', `uid/${app.globalData.identity.uid}/rid/${e.currentTarget.dataset.record.rid}`, 300, 300);
    this.selectComponent('#recordpanel').showPanel(e.currentTarget.dataset.record)
  },
  hideRecord(){
    this.setData({recordshow:false})
    this.getIndex()
  },
  goYyhd(){
    if(this.data.noteam){
      wx.showToast({
        title: '请先加入一个班队',
        icon: 'none',
        duration: 2000
      })
      return;
    }
    wx.reLaunch({url: '/pages/user/yy/yyhd/yyhd'})
  },
  goBm(){
    wx.switchTab({url: '/pages/user/bm/bm'})
  },
  goJl(){
    wx.switchTab({
      url: '/pages/user/jl/jl',
    })
  },
  getIssign(){
    app.request('isSign','','GET').then(res => {
      if(res.data.issign==0){
        wx.reLaunch({
          url: '/pages/sign/sign'
        })
      }
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  judgeTeam(){
    let noteam = app.globalData.identity.tid||app.globalData.identity.t2id ? false : true;
    this.setData({noteam: noteam})
  },
  judgeEpi(){
    app.request('isEpi','','GET').then(res => {
      if(res.data.isepi == 1){
        this.setData({nodates:[], nodateshow:false})
        return;
      }
      this.setData({nodates:res.data.nodates, nodateshow:true})
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  inputTheEpi(e){
    wx.navigateTo({
      url: '/pages/epi/epi?date='+e.currentTarget.dataset.date,
    })
  },
  onLoad: function (options) {
    this.getIssign()
  },
  onShow: function () {
    this.judgeEpi()
    this.judgeTeam()
    this.setWelcome()
    this.getIndex()
  },
})