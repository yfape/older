// pages/admin/hdqd/hdqd.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show:false,teams:[],date:'',startdate:'',endate:'',
  },
  onLoad: function () {
    this.setDate()
  },
  onShow: function(){
    this.getTeams()
  },
  getTeams(){
    let date = new Date(this.data.date).Format('yyyyMMdd');
    app.request('manager/teams/'+date,'','GET').then(res => {
      this.setData({teams: res.data})
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  goBack(){
    wx.navigateBack({delta: 0})
  },
  setDate(){
    let date = new Date();
    let enddate = new Date((date/1000+86400*14)*1000).Format('yyyy-MM-dd');
    this.setData({date: date.Format('yyyy-MM-dd'), startdate: date.Format('yyyy-MM-dd'), enddate: enddate})
  },
  goSectionPage(e){
    let sid = e.currentTarget.dataset.sid
    wx.navigateTo({
      url: '/pages/admin/sectionmanager/sectionmanager?sid='+sid
    })
  },
  bindDateChange: function(e) {console.log(e)
    this.setData({
      date: e.detail.value,
    })
    this.getTeams()
  },
})