// pages/epi/epi.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    input:{
      date:'',islocal:0,istour:0,istouchlocal:0,istouch:0,istouchrecovery:0,isill:0,iswilling:1,heat:''
    }
  },
  goBack(){
    wx.navigateBack({delta: 0})
  },
  bindDateChange: function(e) {
    this.setData({
      'input.date': e.detail.value
    })
  },
  radioChange(e){
    let input = this.data.input
    input[e.currentTarget.dataset.field] = parseInt(e.detail.value)
    this.setData({
      input: input
    })
  },
  inputChange(e){
    let input = this.data.input
    input[e.currentTarget.dataset.field] = parseFloat(e.detail.value)
    this.setData({
      input: input
    })
  },
  submit(){
    if(!this.data.input.heat){
      app.tipToast('请输入当日体温');
      return;
    }
    app.request('epi',this.data.input).then(res => {
      app.tipToast(res.data.msg, '提交成功')
      wx.navigateBack({ delta: 0})
    }).catch(err => {
      app.tipToast(err.data.msg, '操作失败')
    })
  },
  onLoad: function (options) {
    this.setData({
      'input.date': options.date
    })
  },
  onShow: function () {

  },
})