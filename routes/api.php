<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api');

Route::get('/historyResponses', 'ResponseController@getSomeOneHistoryResponses')->middleware('auth:api');
Route::get('/historyResponse/{Scale}', 'ResponseController@getSomeOneHistoryResponse')->middleware('auth:api');
Route::get('/getstd/{Scale}', 'ResponseController@getstd');
Route::get('/getAnalysis/{Scale}', 'ScaleController@getAnalysis');
Route::get('/getAnalysis/{Scale}/{StartDate}/{EndDate}', 'ScaleController@getAnalysis');
Route::get('/export/{Scale}/{StartDate}/{EndDate}', 'ScaleController@exportExcel');
Route::get('/export/{Scale}', 'ScaleController@exportExcel');
