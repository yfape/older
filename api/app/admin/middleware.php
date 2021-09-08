<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 09:30:12
 * @LastEditTime: 2021-02-07 00:22:14
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\middleware.php
 */
// 全局中间件定义文件
return [
    // 全局请求缓存
    // \think\middleware\CheckRequestCache::class,
    // 多语言加载
    // \think\middleware\LoadLangPack::class,
    // Session初始化
    // \think\middleware\SessionInit::class
    // 跨域请求支持
    \app\admin\middleware\Auth::class
];
