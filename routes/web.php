<?php

use Illuminate\Support\Facades\Route;

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

Route::group(['namespace' => 'Website', 'as' => 'website.'], function() {
    Route::get('login', 'AuthController@showLoginPage')->name('auth.login');
    Route::post('login', 'AuthController@authenticate')->name('auth.authenticate');
    Route::get('register', 'RegisterController@showRegisterForm')->name('auth.register');
    Route::post('register', 'RegisterController@register')->name('auth.create');

    Route::middleware('auth.web')->group(function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/home', 'HomeController@index')->name('auth.home');
        Route::get('logout', 'AuthController@logout')->name('auth.logout');

        Route::group(['prefix' => 'account'], function(){
            Route::get('/create', 'AccountController@create')->name('account.create');
            Route::post('/store', 'AccountController@store')->name('account.store');
            Route::get('/show_manager_approval', 'AccountController@show_manager_approval')->name('account.show_manager_approval');
            Route::get('/show_manager_approval_ajax', 'AccountController@show_manager_approval_ajax')->name('account.show_manager_approval_ajax');
        });
    });
});

Route::group(['namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin'], function() {
    Route::get('login', 'AuthController@showLoginPage')->name('auth.login');
    Route::post('login', 'AuthController@authenticate')->name('auth.authenticate');

   Route::middleware('auth.admin')->group(function () {
       Route::get('/', 'HomeController@index')->name('home');
       Route::get('/home', 'HomeController@index')->name('auth.home');
       Route::get('logout', 'AuthController@logout')->name('auth.logout');
   });

});