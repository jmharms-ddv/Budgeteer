<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

Route::get('income', ['as' => 'income.index', 'uses' => 'IncomesController@index']);
Route::get('income/{income}', ['as' => 'income.show', 'uses' => 'IncomesController@show']);
Route::post('income', ['as' => 'income.store', 'uses' => 'IncomesController@store']);
Route::put('income', ['as' => 'income.update', 'uses' => 'IncomesController@update']);
Route::delete('income/{income}', ['as' => 'income.destroy', 'uses' => 'IncomesController@destroy']);

Route::get('bill', ['as' => 'bill.index', 'uses' => 'BillsController@index']);
Route::get('bill/{bill}', ['as' => 'bill.show', 'uses' => 'BillsController@show']);
Route::post('bill', ['as' => 'bill.store', 'uses' => 'BillsController@store']);
Route::put('bill', ['as' => 'bill.update', 'uses' => 'BillsController@update']);
Route::delete('bill/{bill}', ['as' => 'bill.destroy', 'uses' => 'BillsController@destroy']);

Route::get('paycheck', ['as' => 'paycheck.index', 'uses' => 'PaychecksController@index']);
Route::get('paycheck/{paycheck}', ['as' => 'paycheck.show', 'uses' => 'PaychecksController@show']);
Route::post('paycheck', ['as' => 'paycheck.store', 'uses' => 'PaychecksController@store']);
Route::put('paycheck', ['as' => 'paycheck.update', 'uses' => 'PaychecksController@update']);
Route::delete('paycheck/{paycheck}', ['as' => 'paycheck.destroy', 'uses' => 'PaychecksController@destroy']);

Route::get('user', ['as' => 'user.loggedin', 'uses' => 'UsersController@loggedin']);
