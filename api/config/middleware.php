<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 09:30:12
 * @LastEditTime: 2021-01-26 15:24:16
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\config\middleware.php
 */
// 中间件配置
return [
    // 别名或分组
    'alias'    => [],
    // 优先级设置，此数组中的中间件会按照数组中的顺序优先执行
    'priority' => [],
    // 不会匹配全局中间件的路由
    'except' => ['/login','/autorun']
];
