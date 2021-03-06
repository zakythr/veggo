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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::post('create', 'SampleDatabaseController@create');
Route::get('read', 'SampleDatabaseController@read');
Route::post('update/{id}', 'SampleDatabaseController@update');
Route::get('delete/{id}', 'SampleDatabaseController@delete');

Route::get('etalase', 'Pembeli\BarangController@showBarang');