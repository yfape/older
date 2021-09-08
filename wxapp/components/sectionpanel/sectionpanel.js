// components/recordpanel/recordpanel.js
const app = getApp()
Component({
  /**
   * 组件的属性列表
   */
  properties: {
    mode:Number
  },

  /**
   * 组件的初始数据
   */
  data: {
    show: false,
    content:{}
  },
  options: {
    addGlobalClass: true,
    multipleSlots: true
  },
  /**
   * 组件的方法列表
   */
  methods: {
    showPanel(obj){
      this.setData({
        content: obj,
        show: true
      })
    },
    hidePanel(){
      this.triggerEvent('hide')
      this.setData({show: false})
    },
    cancelSection(){
      app.request('record/' + this.data.content.record[0].rid).then(res => {
        this.hidePanel()
        app.tipToast(res.data.msg, '取消成功')
      }).catch(err => {
        app.tipToast(err.data.msg, '取消失败')
      })
    },
    joinSection(){
      app.request('section/' + this.data.content.sid).then(res => {
        app.tipToast(res.data.msg, '预约成功')
        this.hidePanel()
      }).catch(err => {
        app.tipToast(err.data.msg, '预约失败')
      })
    }
  }
})
