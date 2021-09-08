// pages/index/jl/jl.js
const app = getApp()
Page({

  /**
   * 页面的初始数据
   */
  data: {
    show: false,moreshow:false,
    indexdata:[],
    page: 1
  },
  openRecord(e){return;
    this.selectComponent('#recordpanel').showPanel(e.currentTarget.dataset.record)
  },
  hideRecord(){
    this.setData({recordshow:false})
  },
  getIndexData(){
    this.setData({moreshow:false})
    app.request('record/history/'+this.data.page, '', 'GET').then(res => {
      // let arr = this.formatData(res.data)
      let moreshow = false
      if(res.data.length >= 10){
        moreshow = true
      }
      if(res.data.length > 0){
        this.setData({page:this.data.page+1})
      }
      let arr = this.data.indexdata
      arr.push(...res.data)
      this.setData({show: true,indexdata: arr, moreshow: moreshow})
    }).catch(err => {
      wx.navigateTo({ url: '/pages/error/error?statuscode='+err.statusCode+'&msg'+err.data.msg })
    })
  },
  formatData(data){
    var arr = [];
    this.setData({indextext:'今天，您没有活动'})
    for(let i=0; i<data.length; i++){
      let rearr = [];
      let obj = new Object;
      if(rearr.includes(data[i].update_time)){
        continue;
      }
      let d = new Date().Format("yyyy/MM/dd");
      if(data[i].section.date == d){
        obj.date = '今日'
      }else{
        obj.date = data[i].update_time
      }
      obj.content = []
      obj.content.push(data[i])
      rearr.push(data[i].update_time)
      for(let j=i+1; j<data.length; j++){
        if(data[j].update_time == obj.date){
          obj.content.push(data[j])
        }
      }
      arr.push(obj)
    }
    return arr;
  },
  onLoad: function (options) {
    // this.getIndexData()
  },
  onShow: function () {
    this.setData({ indexdata: [], page: 1, show: false })
    this.getIndexData()
  },
})