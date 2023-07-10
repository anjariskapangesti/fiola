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
        // MASTER //
        Route::group(['prefix' => 'department'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'DepartmentController@create')->name('department.create');
                Route::post('/store', 'DepartmentController@store')->name('department.store');
                Route::delete('/destroy', 'DepartmentController@destroy')->name('department.destroy');
                Route::get('/show_data_department', 'DepartmentController@show_data_department')->name('department.show_data_department');
                Route::get('/show_data_department_ajax', 'DepartmentController@show_data_department_ajax')->name('department.show_data_department_ajax');
            });
        });

        Route::group(['prefix' => 'folder'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'FolderController@create')->name('folder.create');
                Route::post('/store', 'FolderController@store')->name('folder.store');
                Route::delete('/destroy', 'FolderController@destroy')->name('folder.destroy');
                Route::get('/show_data_folder', 'FolderController@show_data_folder')->name('folder.show_data_folder');
                Route::get('/show_data_folder_ajax', 'FolderController@show_data_folder_ajax')->name('folder.show_data_folder_ajax');
            });
        });

        Route::group(['prefix' => 'subfolder'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'SubFolderController@create')->name('subfolder.create');
                Route::post('/store', 'SubFolderController@store')->name('subfolder.store');
                Route::delete('/destroy', 'SubFolderController@destroy')->name('subfolder.destroy');
                Route::get('/show_data_subfolder', 'SubFolderController@show_data_subfolder')->name('subfolder.show_data_subfolder');
                Route::get('/show_data_subfolder_ajax', 'SubFolderController@show_data_subfolder_ajax')->name('subfolder.show_data_subfolder_ajax');
            });
        });

        Route::group(['prefix' => 'user'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'UserController@create')->name('user.create');
                Route::post('/store', 'UserController@store')->name('user.store');
                Route::delete('/destroy', 'UserController@destroy')->name('user.destroy');
                Route::get('/show_data_user', 'UserController@show_data_user')->name('user.show_data_user');
                Route::get('/show_data_user_ajax', 'UserController@show_data_user_ajax')->name('user.show_data_user_ajax');
            });
        });

        
        // FORM ACCOUNT //
        Route::group(['prefix' => 'account'], function(){
            Route::group(['middleware' => ['can:can_create_form']], function () {
            Route::get('/create', 'AccountController@create')->name('account.create');
            Route::post('/store', 'AccountController@store')->name('account.store');
            });

            Route::group(['middleware' => ['can:can_approve_mgr']], function () {
                Route::get('/show_manager_approval', 'AccountController@show_manager_approval')->name('account.show_manager_approval');
                Route::get('/show_manager_approval_ajax', 'AccountController@show_manager_approval_ajax')->name('account.show_manager_approval_ajax');
                Route::post('/approve_manager', 'AccountController@approve_manager')->name('account.approve_manager');
                Route::get('/show_data_manager_approval', 'AccountController@show_data_manager_approval')->name('account.show_data_manager_approval');
                Route::get('/show_data_manager_approval_ajax', 'AccountController@show_data_manager_approval_ajax')->name('account.show_data_manager_approval_ajax');
                
            });
            Route::group(['middleware' => ['can:can_approve_it']], function () {
                Route::get('/show_it_approval', 'AccountController@show_it_approval')->name('account.show_it_approval');
                Route::get('/show_it_approval_ajax', 'AccountController@show_it_approval_ajax')->name('account.show_it_approval_ajax');
                Route::post('/approve_it', 'AccountController@approve_it')->name('account.approve_it');
                Route::get('/show_data_it_approval', 'AccountController@show_data_it_approval')->name('account.show_data_it_approval');
                Route::get('/show_data_it_approval_ajax', 'AccountController@show_data_it_approval_ajax')->name('account.show_data_it_approval_ajax');
            });
            Route::group(['middleware' => ['can:can_approve_it_mgr']], function () {
                Route::get('/show_it_mgr_approval', 'AccountController@show_it_mgr_approval')->name('account.show_it_mgr_approval');
                Route::get('/show_it_mgr_approval_ajax', 'AccountController@show_it_mgr_approval_ajax')->name('account.show_it_mgr_approval_ajax');
                Route::post('/approve_it_mgr', 'AccountController@approve_it_mgr')->name('account.approve_it_mgr');
                Route::get('/show_data_it_mgr_approval', 'AccountController@show_data_it_mgr_approval')->name('account.show_data_it_mgr_approval');
                Route::get('/show_data_it_mgr_approval_ajax', 'AccountController@show_data_it_mgr_approval_ajax')->name('account.show_data_it_mgr_approval_ajax');
            });

            Route::group(['middleware' => ['can:can_execution']], function () {
                Route::get('/show_execution', 'AccountController@show_execution')->name('account.show_execution');
                Route::get('/show_execution_ajax', 'AccountController@show_execution_ajax')->name('account.show_execution_ajax');
                Route::post('/approve_execution', 'AccountController@approve_execution')->name('account.approve_execution');
                Route::get('/show_data_execution', 'AccountController@show_data_execution')->name('account.show_data_execution');
                Route::get('/show_data_execution_ajax', 'AccountController@show_data_execution_ajax')->name('account.show_data_execution_ajax');
            });
            
        });
        // FORM FOLDER ACCESS //
        Route::group(['prefix' => 'folder-access'], function(){
            Route::get('/create', 'FolderAccessController@create')->name('folder-access.create');
            Route::post('/store', 'FolderAccessController@store')->name('folder-access.store');
            Route::get('/subfolder_ajax', 'FolderAccessController@subfolder_ajax')->name('folder-access.subfolder_ajax');

            Route::group(['middleware' => ['can:can_approve_mgr']], function () {
                Route::get('/show_manager_approval', 'FolderAccessController@show_manager_approval')->name('folder-access.show_manager_approval');
                Route::get('/show_manager_approval_ajax', 'FolderAccessController@show_manager_approval_ajax')->name('folder-access.show_manager_approval_ajax');
                Route::post('/approve_manager', 'FolderAccessController@approve_manager')->name('folder-access.approve_manager');
                Route::get('/show_data_manager_approval', 'FolderAccessController@show_data_manager_approval')->name('folder-access.show_data_manager_approval');
                Route::get('/show_data_manager_approval_ajax', 'FolderAccessController@show_data_manager_approval_ajax')->name('folder-access.show_data_manager_approval_ajax');                
            });
            Route::group(['middleware' => ['can:can_approve_it']], function () {
                Route::get('/show_it_approval', 'FolderAccessController@show_it_approval')->name('folder-access.show_it_approval');
                Route::get('/show_it_approval_ajax', 'FolderAccessController@show_it_approval_ajax')->name('folder-access.show_it_approval_ajax');
                Route::post('/approve_it', 'FolderAccessController@approve_it')->name('folder-access.approve_it');
                Route::get('/show_data_it_approval', 'FolderAccessController@show_data_it_approval')->name('folder-access.show_data_it_approval');
                Route::get('/show_data_it_approval_ajax', 'FolderAccessController@show_data_it_approval_ajax')->name('folder-access.show_data_it_approval_ajax');
            });
            Route::group(['middleware' => ['can:can_approve_it_mgr']], function () {
                Route::get('/show_it_mgr_approval', 'FolderAccessController@show_it_mgr_approval')->name('folder-access.show_it_mgr_approval');
                Route::get('/show_it_mgr_approval_ajax', 'FolderAccessController@show_it_mgr_approval_ajax')->name('folder-access.show_it_mgr_approval_ajax');
                Route::post('/approve_it_mgr', 'FolderAccessController@approve_it_mgr')->name('folder-access.approve_it_mgr');
                Route::get('/show_data_it_mgr_approval', 'FolderAccessController@show_data_it_mgr_approval')->name('folder-access.show_data_it_mgr_approval');
                Route::get('/show_data_it_mgr_approval_ajax', 'FolderAccessController@show_data_it_mgr_approval_ajax')->name('folder-access.show_data_it_mgr_approval_ajax');
            });

            Route::group(['middleware' => ['can:can_execution']], function () {
                Route::get('/show_execution_approval', 'FolderAccessController@show_execution_approval')->name('folder-access.show_execution_approval');
                Route::get('/show_execution_approval_ajax', 'FolderAccessController@show_execution_approval_ajax')->name('folder-access.show_execution_approval_ajax');
                Route::post('/approve_execution', 'FolderAccessController@approve_execution')->name('folder-access.approve_execution');
                Route::get('/show_data_execution_approval', 'FolderAccessController@show_data_execution_approval')->name('folder-access.show_data_execution_approval');
                Route::get('/show_data_execution_approval_ajax', 'FolderAccessController@show_data_execution_approval_ajax')->name('folder-access.show_data_execution_approval_ajax');
            });
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