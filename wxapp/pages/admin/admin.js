// pages/admin/admin.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    manager:{},buttons: [{text: '关闭'}],identityShow:false,idialog:{}
  },
  gohdqd(){
    wx.navigateTo({
      url: '/pages/admin/hdqd/hdqd',
    })
  },
  getIdentity(){
    wx.scanCode({
      onlyFromCamera: true,
      success: res => {console.log(res)
        let urlsec = res.result
        app.request(`manager/user/${urlsec}`,'','GET').then(res => {
          this.setData({idialog: res.data,identityShow:true})
        }).catch(err => {
          app.tipToast(err.data.msg, '操作失败')
        })
      }
    })
  },
  onLoad: function (options) {
    this.setData({manager:app.globalData.identity})
  },
  onShow: function () {

  },
})