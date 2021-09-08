// pages/entry/entry.js
var app = getApp()
Page({
  data: {},
  onLoad: function (options) {},
  onShow: function () {
    wx.login({
      success: res => {
        // console.log(res.code);return;
        // 发送 res.code 到后台换取 openId, sessionKey, unionId
        app.request('login', {code: res.code}).then(res => {
          app.globalData.token = res.data.token
          app.globalData.identity = res.data.identity
          
          switch(res.data.pass){
            case 0: wx.redirectTo({url: '/pages/guide/guide'});break;
            case 1: wx.switchTab({url: '/pages/user/yy/yy'});break;
            case 2: wx.redirectTo({url: '/pages/admin/admin'});break;
            default: wx.redirectTo({url: '/pages/error/error'});
          }
        }).catch(err => {
          wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
        })
      },
      fail: err => {
        wx.reLaunch({url: '/pages/error/error'});
      }
    })
  },
})