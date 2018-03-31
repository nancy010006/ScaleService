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

Route::get('/', function () {
    return view('welcome');
});

// 量表
Route::get('/Scale', 'ScaleController@index');
Route::get('/Scale/data', 'ScaleController@getData');
Route::post('/Scale', 'ScaleController@insert');
Route::put('/Scale', 'ScaleController@update');
Route::delete('/Scale', 'ScaleController@delete');

//題目
Route::get('/Question/data', 'QuestionController@getData');
Route::post('/Question', 'QuestionController@insert');
Route::put('/Question', 'QuestionController@update');
Route::delete('/Question', 'QuestionController@delete');