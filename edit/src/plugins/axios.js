/*
 * @Author: your name
 * @Date: 2021-02-06 21:29:45
 * @LastEditTime: 2021-07-12 10:14:24
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \edit\src\plugins\axios.js
 */
"use strict";

import Vue from "vue";
import axios from "axios";
import main from "@/main.js";

// Full config:  https://github.com/axios/axios#request-config
axios.defaults.baseURL = "http://oeradmin.***.com";
// axios.defaults.headers.common['Authorization'] = AUTH_TOKEN;
axios.defaults.headers.post["Content-Type"] =
  "application/json,multipart/form-data";

let config = {
  // baseURL: process.env.baseURL || process.env.apiUrl || ""
  // timeout: 60 * 1000, // Timeout
  // withCredentials: true, // Check cross-site Access-Control
};

const _axios = axios.create(config);

_axios.interceptors.request.use(
  function (config) {
    if (!config.noloading) {
      main.$q.loading.show();
    }
    config.headers["Authorization"] = main.$store.state.token;
    return config;
  },
  function (error) {
    // Do something with request error
    return Promise.reject(error);
  }
);

// Add a response interceptor
_axios.interceptors.response.use(
  function (response) {
    main.$q.loading.hide();
    // Do something with response data
    main.AItip(response.data.msg);
    return response.data;
  },
  function (error) {
    main.$q.loading.hide();
    if (error.response.status == 401) {
      main.AItip(error.response.data.msg);
      main.$router.push("/login");
    } else {
      main.AItip(
        error.response.data.msg ? error.response.data.msg : "网络错误"
      );
      return Promise.reject(error);
    }
  }
);

Plugin.install = function (Vue, options) {
  Vue.axios = _axios;
  window.axios = _axios;
  Object.defineProperties(Vue.prototype, {
    axios: {
      get() {
        return _axios;
      },
      post() {
        return _axios;
      },
    },
    $axios: {
      get() {
        return _axios;
      },
      post() {
        return _axios;
      },
    },
  });
};

Vue.use(Plugin);

export default Plugin;
