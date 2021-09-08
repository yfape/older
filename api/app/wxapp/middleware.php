<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 09:30:12
 * @LastEditTime: 2021-02-06 23:12:31
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
    // 全局验证token
    \app\common\middleware\Auth::class
];
