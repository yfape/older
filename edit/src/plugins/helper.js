import Vue from 'vue'

Vue.prototype.lStorage = function(name,data=false){
	if(data!==false){
		localStorage.setItem(name,JSON.stringify(data))
		return true
	}else{
		return localStorage.getItem(name)?JSON.parse(localStorage.getItem(name)):false
	}
},
Vue.prototype.refleshAxiosHeader = function(axios){
	let yfinfo = localStorage.getItem('yf')?JSON.parse(localStorage.getItem('yf')):''
  	axios.defaults.headers.common['Authorization'] = yfinfo?yfinfo.token:'';
  	axios.defaults.headers.common['YFU'] = yfinfo?yfinfo.uid:'';
  	return true
},
Vue.prototype.myGoTo = function(r,name){
  if(r.history.current.name != name){
    r.push({name:name})
  }else{
    console.log(false)
  }
},
Vue.prototype.toThousands = function(num) {
  var result = [ ], counter = 0;
  num = (num || 0).toString().split('');
  for (var i = num.length - 1; i >= 0; i--) {
    counter++;
    result.unshift(num[i]);
    if (!(counter % 3) && i != 0) {
      result.unshift(',');
    }
  }
  return result.join('');
},
Vue.prototype.formatDate = function(now) { 
     var year=now.getFullYear();
     var month=now.getMonth()+1;
     month = month<10?('0'+month):month
     var date=now.getDate();
     date = date<10?('0'+date):date
     // var hour=now.getHours();
     // var minute=now.getMinutes();
     // var second=now.getSeconds();
     return year+"-"+month+"-"+date; 
},
Vue.prototype.getLocalTime = function(posttime) { 
  return this.formatDate(new Date(posttime*1000));
},
Vue.prototype.timeDate = function(arg) {
  if(typeof arg=='string'){
    let date = new Date(arg)
    return date.getTime()/1000
  }else if(typeof arg=='number'){
    return this.formatDate(new Date(arg*1000))
  }
},
Vue.prototype.outRepeat = function(arr){
  return Array.from(new Set(array));
}

