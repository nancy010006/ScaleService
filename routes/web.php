<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', 'SiteController@index');

// 量表
Route::get('/Scales', 'ScaleController@getData');
Route::get('/Scale', 'ScaleController@index');
Route::get('/Scale/{scale}', 'ScaleController@getOneData');
Route::post('/Scale', 'ScaleController@insert');
Route::put('/Scale/{scale}', 'ScaleController@update');
Route::delete('/Scale/{scale}', 'ScaleController@delete');

//題目
Route::get('/Questions', 'QuestionController@getData');
Route::get('/Question/{Question}', 'QuestionController@getOneData');
Route::post('/Question', 'QuestionController@insert');
// Route::put('/Question/{Question}', 'QuestionController@update');
Route::put('/Question/', 'QuestionController@update');
Route::delete('/Question/{Question}', 'QuestionController@delete');

//回應
Route::get('/Responses', 'ResponseController@getData');
Route::get('/Response/{Response}', 'ResponseController@getOneData');
Route::post('/Response', 'ResponseController@insert');
Route::put('/Response/{Response}', 'ResponseController@update');
Route::delete('/Response/{Response}', 'ResponseController@delete');

//一般使用者驗證功能
Route::get('/site/login', 'site\LoginController@showLoginForm');
Route::post('/site/login', 'site\LoginController@login');
Route::post('/site/logout', 'site\LoginController@logout');

//管理員頁面
Route::get('/admin', 'AdminController@index');
Route::get('/admin/tables', 'AdminController@tables');
Route::get('/admin/scale', 'AdminController@scale');
Route::get('/admin/scale/add', 'AdminController@scaleAdd');
Route::get('/admin/scale/edit/{scale}', 'AdminController@scaleEdit');
Route::get('/admin/default', 'AdminController@default');

//拿token
Route::get('site/token', 'SiteController@getAPIToken');
//一般使用者頁面
Route::get('/site', 'SiteController@index');
Route::get('/site/scales', 'SiteController@scales')->middleware('isUser');
Route::get('/site/records', 'SiteController@records')->middleware('isUser');
Route::get('/site/scales/{scale}', 'SiteController@scale');
Route::get('/site/record/{scale}', 'SiteController@record');


Route::get('/login', 'site\LoginController@showLoginForm')->name('login');

