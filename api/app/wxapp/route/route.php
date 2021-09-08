<?php
/*
 * @Author: your name
 * @Date: 2021-01-25 16:25:39
 * @LastEditTime: 2021-03-04 09:12:16
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

//登录
Route::post('login', 'common.login/index'); //

//账号识别及绑定
Route::post('identity/phone', 'common.identity/byPhone');//
Route::post('identity/incode', 'common.identity/byIncode');//
Route::post('identity/affirm', 'common.identity/affirmIdentity');//

//承诺书
Route::get('isSign', 'user.Page/isSign');//
Route::get('signdata', 'user.Page/getSignData');//
Route::post('sign', 'user.Page/signBook');//

//疫情防控调查
Route::get('isEpi', 'user.Epi/isEpi');//
Route::post('epi', 'user.Epi/save');//

//record
Route::get('record/uncompletes', 'user.record/uncompletes');
Route::post('record/<rid>', 'user.record/cancelRecord');
Route::get('record/history/<page>', 'user.record/history');

//team
Route::get('team/category/<category>', 'user.team/listWithCategory');
Route::post('team/<tid>', 'user.team/joinTeam');
Route::get('team/user', 'user.team/getUserTeams');

//section
Route::get('section/:date', 'user.section/listWithDate');
Route::post('section/:sid', 'user.section/joinSection');

//manager
Route::get('manager/teams/<date>$', 'manager.Info/getTeams');
Route::get('manager/section/<sid>', 'manager.Info/getSection');
Route::post('manager/record/<rid>/<field>/<value>', 'manager.Info/setRecord');
Route::get('manager/sid/<sid>/record/uid/<uid>/rid/<rid>', 'manager.Info/getSectionUserRecord');
Route::post('manager/quickarrive/<sid>/uid/<uid>/rid/<rid>', 'manager.Info/quickarrive');
Route::get('manager/user/uid/<uid>', 'manager.Info/getUser');