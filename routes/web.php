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

// Route::get('/', function () {
//     return view('welcome');
// });

Route::get('/', 'IndexController@index');

Route::match(['get', 'post'], 'admin', 'AdminController@login')->name('admin');

Route::get('/logout','AdminController@logout');

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

Route::get('/products/{url}','ProductsController@products')->name('products.url');

Route::group(['middleware'=>['auth']],function(){

    Route::get('/admin/dashboard','AdminController@dashboard')->name('dashboard');
    Route::get('/admin/settings','AdminController@settings');
    Route::get('/admin/check-pwd','AdminController@chkPassword');
    Route::match(['get', 'post'],'/admin/update-pwd','AdminController@updatePassword')->name('password.update');

    Route::resource('categories', 'CategoryController');
    Route::match(['get', 'post'], '/admin/edit-category/{id}','CategoryController@editCategory');
    Route::match(['get', 'post'], '/admin/delete-category/{id}','CategoryController@deleteCategory');
    Route::get('/admin/categories','CategoryController@index')->name('categories.view');

    Route::resource('products', 'ProductsController');
    Route::match(['get', 'post'], '/admin/edit-product/{id}','ProductsController@editProduct')->name('product.update');
    Route::match(['get', 'post'], '/admin/delete-product/{id}','ProductsController@deleteProduct')->name('product.delete');
    Route::match(['get', 'post'], '/admin/delete-image/{id}','ProductsController@deleteImage')->name('image.delete');

    Route::get('/admin/attributes/{id}','ProductsController@Attributes')->name('attribute.view');
    Route::match(['get', 'post'], '/admin/add-attributes/{id}','ProductsController@AddAtrributes')->name('add.attribute');
    Route::match(['get', 'post'], '/admin/delete-attribute/{id}','ProductsController@deleteAttribute')->name('attribute.delete');
});