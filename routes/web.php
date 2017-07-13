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

Route::get('/', 'ListingsController@all');
Route::post('/', 'ListingsController@all');

Route::get('/listings', 'ListingsController@all');
Route::post('/listings', 'ListingsController@all');
Route::get('/listings/{id}', 'ListingsController@show');
