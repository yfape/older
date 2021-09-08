// pages/sign/sign.js
const app = getApp()
Page({
  /**
   * 页面的初始数据
   */
  data: {
    title:'',
    content:[]
  },
  getSign(){
    app.request('signdata', '', 'GET').then(res => {
      this.setData({
        title: res.data.title,
        content: res.data.content
      })
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  sign(){
    app.request('sign').then(res => {
      wx.reLaunch({
        url: '/pages/user/yy/yy',
      })
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  onLoad: function (options) {
    this.getSign()
  },
})