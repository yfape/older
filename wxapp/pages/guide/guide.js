// pages/guide/guide.js
const app = getApp()

Page({

  /**
   * 页面的初始数据
   */
  data: {
    loading: false, step: 0,
    phone: null, incode: null, name: null,
    s_incode: null, s_name: null, s_phone: null,
    currentAudio: null, identitykey: null,
  },
  getPhoneNumber(e){
    this.setData({ loading: true })
    if(e.detail.encryptedData){
      app.request('identity/phone', {
        encryptedData: encodeURIComponent(e.detail.encryptedData),
        iv: e.detail.iv
      }).then(res => {
        this.setData({
          loading: false,
          s_phone: res.data.identity.phone
        })
        if(res.data.pass == 0){
          this.goStep(2)
        }else if(res.data.pass == 9){
          this.setData({
            s_incode: res.data.identity.incode,
            s_name: res.data.identity.name,
            identitykey: res.data.identitykey
          })
          this.goStep(9)
        }
      }).catch(err => {
        wx.showToast({
          title: err.data.msg ? err.data.msg : '网络错误',
          icon: 'none',
          duration: 2000
        })
        this.setData({loading: false})
      })
    }else{
      this.setData({loading: false})
    }
  },
  bindKeyInput(e){
    this.setData({
      [e.currentTarget.dataset.name]: e.detail.value
    })
  },
  goStep(e){
    var step = (!e.currentTarget) ? e : e.currentTarget.dataset.step
    this.playAudio(step)
    this.setData({step: step})
  },
  submitIncode(){
    if(!(this.data.incode && this.data.name && this.data.s_phone)){
      return;
    }
    this.setData({loading: true})
    var data = {
      incode: this.data.incode,
      name: this.data.name,
      phone: this.data.s_phone
    }
    app.request('identity/incode', data).then(res => {
      this.setData({
        loading: false,
        s_incode: res.data.identity.incode,
        s_name: res.data.identity.name,
        s_phone: res.data.identity.phone,
        identitykey: res.data.identitykey
      })
      this.goStep(res.data.pass)
    }).catch(err => {
      this.setData({loading: false})
      wx.showToast({
        title: err.data.msg ? err.data.msg : '验证失败',
        icon: 'none',
        duration: 2000
      })
    })
  },
  affirmIdentity(){
    this.setData({loading: true})
    app.request('identity/affirm', {
      name: this.data.s_name,
      phone: this.data.s_phone,
      incode: this.data.s_incode,
      identitykey: this.data.identitykey
    }).then(res => {
      this.setData({loading: false})
      app.globalData.identity = res.data.identity
      var step = parseInt(res.data.pass)
      switch(step){
        case 1:  wx.reLaunch({
          url: '/pages/user/yy/yy'
        });break;
        case 2: wx.reLaunch({
          url: '/pages/admin/admin'
        });break;
        default: wx.redirectTo({
          url: '/pages/error/error'
        });
      }
    }).catch(err => {
      this.setData({loading: false})
      wx.showToast({
        title: res.data.msg ? res.data.msg : '验证错误',
        icon: 'none',
        duration: 2000
      })
    })
  },
  onLoad: function (options) {
    this.playAudio(0)
    setTimeout(()=>{
      this.goStep(1)
    }, 6000)
  },
  //dev
  playAudio(step){
    if(this.data['audio' + this.data.currentAudio]){
      this.data['audio' + this.data.currentAudio].stop()
    }
    this.data['audio'+step] = wx.createInnerAudioContext()
    this.data['audio'+step].src = '/static/audio/' + step + '.mp3'
    this.data['audio'+step].play()
    this.setData({currentAudio: step})
  },
})