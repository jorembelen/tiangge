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


Route::match(['get', 'post'], 'admin', 'AdminController@login')->name('admin');

Route::get('/logout','AdminController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware'=>['auth']],function(){

    Route::get('/admin/dashboard','AdminController@dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get', 'post'],'/admin/update-pwd','AdminController@updatePassword')->name('password.update');

    Route::resource('categories', 'CategoryController');
    Route::get('/admin/categories','CategoryController@index')->name('categories.view');

});