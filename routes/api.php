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
Route::group(['middleware' => 'auth:api'], function() {
    Route::get('income', ['as' => 'income.index', 'uses' => 'IncomesController@index']);
    Route::get('income/{income}', ['as' => 'income.show', 'uses' => 'IncomesController@show']);
    Route::post('income', ['as' => 'income.store', 'uses' => 'IncomesController@store']);
    Route::put('income', ['as' => 'income.store', 'uses' => 'IncomesController@store']);
    Route::delete('income/{income}', ['as' => 'income.destroy', 'uses' => 'IncomesController@destroy']);
});
