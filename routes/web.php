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

Route::get('/Scale', 'ScaleController@index');
Route::post('/Scale', 'ScaleController@insert');
Route::put('/Scale', 'ScaleController@update');
Route::delete('/Scale', 'ScaleController@delete');