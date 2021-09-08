// app.js
App({
  globalData: {
    URL: 'https://oer.scdjw.com.cn/',
    // URL: 'http://10.100.80.204/',
    // URL:'http://192.168.2.3/',
    token: null,
    bloading: false,
    identity:{
      name: null,
      phone: null
    }
  },
  onLaunch() {
    Date.prototype.Format = function (fmt) { // author: meizz
      var weeks = new Array("周日", "周一", "周二", "周三", "周四", "周五", "周六");
      var o = {
        "M+": this.getMonth() + 1, // 月份
        "d+": this.getDate(), // 日
        "h+": this.getHours(), // 小时
        "m+": this.getMinutes(), // 分
        "s+": this.getSeconds(), // 秒
        "q+": Math.floor((this.getMonth() + 3) / 3), // 季度
        "S": this.getMilliseconds(), // 毫秒
        "w": weeks[this.getDay()]
      };
      if (/(y+)/.test(fmt))
        fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));
      for (var k in o)
        if (new RegExp("(" + k + ")").test(fmt)) fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));
        return fmt;
    }
  },
  request(url, data = '', method = 'POST', header = 'application/json') {
    return new Promise((resolve, reject) => {
      let that = this
      wx.request({
          url: this.globalData.URL + url,
          method: method,
          data: data,
          header: {
              'Content-Type': header,
              'Authorization': this.globalData.token ? this.globalData.token : ''
          },
          success(request) {
              if (request.statusCode == 200) {
                  resolve(request)
              } else {
                  reject(request)
              }
          },
          fail(error) {
            wx.reLaunch({url: '/pages/error/error?statuscode='+error.statusCode})
          }
      })
  })
  },
  tipToast(text, temp) {
    wx.showToast({
      title: text?text:temp,
      icon: 'none',
      duration: 2000
    })
  }
})