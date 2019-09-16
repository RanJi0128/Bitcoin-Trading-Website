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

Route::get('/','Bitcoin\HomeController@index');
Route::post('/','Bitcoin\HomeController@index');

Route::get('transaction','Bitcoin\HomeController@transaction');
Route::post('transaction','Bitcoin\HomeController@transaction');

Route::get('exchanges','Bitcoin\HomeController@exchanges');
Route::post('exchanges','Bitcoin\HomeController@exchanges');

Route::post('cron','Bitcoin\HomeController@cron');
Route::get('contact','Bitcoin\HomeController@contact');
Route::get('vtest','Bitcoin\HomeController@vtest');
Auth::routes();

Route::get('/clear', function() {

    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('config:cache');
    Artisan::call('view:clear');
 
    return "Cleared!";
 
 });


