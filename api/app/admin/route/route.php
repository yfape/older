<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 16:25:39
 * @LastEditTime: 2021-03-09 01:59:51
 * @LastEditors: Please set LastEditors
 * @Description: In User Settings Edit
 * @FilePath: \olderdata\app\wxapp\route\route.php
 */
use think\facade\Route;

Route::pattern([
    'tid' => '\d+',
    'sid' => '\d+',
    'rid' => '\d+',
    'uid' => '\d+',
    'field' => '\w+',
    // 'value' => '\w+',
    'limit' => '\d+',
    'page' => '\d+'
]);
//login
Route::post('login', 'services.login/index');
Route::get('page/home', 'services.page/home');
Route::post('upload', 'services.upload/index');

//team
Route::get('team/<tid>', 'team/get');
Route::get('teams/<limit>/<page>', 'team/list');
Route::post('team/open/<tid>', 'team/changeOpen');
Route::post('teams/open/<open>', 'team/openAll');
Route::post('team/valid/<tid>', 'team/changeValid');
Route::post('team$', 'team/insert');
Route::post('team/<tid>$', 'team/update');
Route::delete('team/<tid>$', 'team/delete');
Route::get('team/lastdate/<tid>', 'team/lastdate');
Route::get('team/users/<tid>/<limit>/<page>$', 'team/users');
Route::delete('team/user/<tid>/<uid>$', 'team/removeUser');
// Route::put('team/<tid>/<field>/[:value]', 'team/put');

//section

Route::get('sections/<tid>/<date>$', 'section/listByDate');
Route::post('section/valid/<sid>', 'section/changeValid');
Route::post('section/<sid>$', 'section/updateCountAndTip');
Route::delete('section/<sid>', 'section/delete');
Route::post('insertsections/<tid>', 'section.InsertSections/index');
// Route::get('sections/<tid>/<limit>/<page>$', 'section/list');

//user
Route::get('users/<limit>/<page>$', 'user/list');
Route::get('user/search/<search>$', 'user/search');
Route::get('user/<uid>$', 'user/get');
Route::post('user/team/<uid>/<tid>$', 'user/changeTeam');
Route::post('user$', 'user/insert');
Route::post('user/<uid>', 'user/update');
Route::delete('user/<uid>', 'user/delete');

//manager
Route::get('manager/self', 'manager/getSelf');
Route::post('manager/pass$', 'manager/reviewPass');
Route::get('managers$', 'manager/list');
Route::get('manager/<mid>$', 'manager/get');
Route::post('manager/pass/<mid>$', 'manager/resetPass');
Route::delete('manager/<mid>$', 'manager/delete');
Route::post('manager$', 'manager/insert');
Route::post('manager/<mid>$', 'manager/update');

// Route::get('users/<field>/<value>/<limit>/<page>', 'user.user/list');
// Route::put('user/:uid/:field/[:value]', 'user.user/put');
//record
Route::get('records/<sid>/<limit>/<page>', 'record.record/list');
Route::put('record/<rid>/<field>/<value>', 'record.record/put');

//output
Route::post('output/teams', 'output/teams');
Route::post('output/teamusers/<tid>', 'output/teamusers');
Route::post('output/users', 'output/users');