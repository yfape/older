// pages/user/yy/yyhd/yyhd.js
var app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    date:'2021-02-09',datetext:'2021年02月09日 周二',startdate:'',enddate:'',loading:true,
    content:[]
  },
  getData(){
    this.setData({loading: true})
    let date = new Date(this.data.date).Format('yyyyMMdd');
    app.request('section/'+date,'','GET').then(res => {
      this.setData({content: res.data, loading: false})
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  openSection(e){
    this.setData({sectionpancelshow:true})
    this.selectComponent('#sectionpanel').showPanel(e.currentTarget.dataset.section)
  },
  backPage(){
    wx.switchTab({
      url: '/pages/user/yy/yy',
    })
  },
  nextDate(){
    if(this.data.date == this.data.enddate){
      app.tipToast('已到最后一天');
      return;
    }
    let s = new Date(this.data.date).getTime();
    let date = new Date((s/1000+86400)*1000).Format('yyyy-MM-dd');

    this.setData({date: date})
    this.setDatetext()
    this.getData()
  },
  setDatetext(){
    let datetext = new Date(this.data.date).Format('yyyy年MM月dd日 w')
    this.setData({datetext:datetext})
  },
  bindDateChange: function(e) {
    this.setData({
      date: e.detail.value
    })
    this.setDatetext()
    this.getData()
  },
  hideSection(){
    this.getData()
  },
  /**
   * 生命周期函数--监听页面加载
   */
  onLoad: function (options) {
    let today = new Date().getTime();
    let enddate = new Date((today/1000+86400*14)*1000).Format('yyyy-MM-dd');
    this.setData({date:new Date().Format('yyyy-MM-dd'),startdate:new Date().Format('yyyy-MM-dd'),enddate:enddate})
    this.setDatetext()
    this.getData()
  },

  /**
   * 生命周期函数--监听页面初次渲染完成
   */
  onReady: function () {

  },

  /**
   * 生命周期函数--监听页面显示
   */
  onShow: function () {
    
  },

  /**
   * 生命周期函数--监听页面隐藏
   */
  onHide: function () {

  },

  /**
   * 生命周期函数--监听页面卸载
   */
  onUnload: function () {

  },

  /**
   * 页面相关事件处理函数--监听用户下拉动作
   */
  onPullDownRefresh: function () {

  },

  /**
   * 页面上拉触底事件的处理函数
   */
  onReachBottom: function () {

  },

  /**
   * 用户点击右上角分享
   */
  onShareAppMessage: function () {

  }
})