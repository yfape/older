// pages/admin/sectionmanager/sectionmanager.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show:false,sid:0,
    content:{sid:0, record:[]},
    arriveNum:0,
    record:{rid:0},dialogShow:false,buttons: [{text: '取消'}, {text: '签到'}],
  },
  arriveRecordExt(rid){

    if(new Date().Format('yyyy/MM/dd') != this.data.content.date){
      wx.showToast({
        title: '只能签到当天场次',
        icon:'none',
        duration:1500
      })
      return;
    }

    app.request(`manager/record/${rid}/arrive/1`).then(res => {
      this.getSection(this.data.content.sid)
    }).catch(err => {
      app.tipToast(err.data.msg, '操作失败')
    })
  },
  arriveRecord(e){
    let rid = e.currentTarget.dataset.rid
    this.arriveRecordExt(rid)
  },
  tapDialogButton(e){
    if(!e.detail.index){
      this.setData({dialogShow:false})
      return;
    }
    this.arriveRecordExt(this.data.record.rid)
  },
  getRecord(urlsec){
    app.request(`manager/sid/${this.data.sid}/record/`+urlsec,'','GET').then(res => {
      if(res.data.user){
        if(res.data.arrive){
          this.setData({record: res.data, dialogShow: true, buttons: [{text: '已签到',extClass:'tc-tip'}]})
        }else{
          if(this.data.content.status == 9){
            this.setData({record: res.data, dialogShow: true, buttons: [{text: '缺席',extClass:'tc-tip'}]})
          }else{
            this.setData({record: res.data, dialogShow: true, buttons: [{text: '取消',extClass:'tc-tip'}, {text: '签到'}]})
          }
        }
      }else{
        this.setData({record: res.data, dialogShow: true, buttons: [{text: '取消'}]})
      }
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  quickScanCode(){
    wx.scanCode({
      onlyFromCamera: true,
      success: res => {
        let urlsec = res.result
        app.request(`manager/quickarrive/${this.data.sid}/${urlsec}`).then(res => {
          app.tipToast(res.data.msg, '操作成功')
          this.getSection(this.data.sid)
        }).catch(err => {
          app.tipToast(err.data.msg, '操作失败')
        })
      }
    })
  },
  scanCode(){
    wx.scanCode({
      onlyFromCamera: true,
      success: res => {
        let urlsec = res.result
        this.getRecord(urlsec)
      }
    })
  },
  mathArrive(){
    let arriveNum = 0;
    let arr = this.data.content.record
    for(let i=0;i<arr.length;i++){
      if(arr[i].arrive == 1){
        arriveNum += 1;
      }
    }
    this.setData({arriveNum: arriveNum})
  },
  getSection(sid){
    app.request(`manager/section/${sid}`,'','GET').then(res => {
      this.setData({content: res.data,show: true,dialogShow:false})
      this.mathArrive()
    }).catch(err => {
      wx.reLaunch({url: '/pages/error/error?statuscode='+err.statusCode+'&msg='+err.data.msg})
    })
  },
  goBack(){
    wx.navigateBack({delta: 0})
  },
  onLoad: function (options) {
    let sid = parseInt(options.sid)
    this.setData({sid:sid})
  },
  onShow: function () {
    this.getSection(this.data.sid)
  },
})