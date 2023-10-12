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
        Route::get('/home_ajax', 'HomeController@home_ajax')->name('home_ajax');
        Route::get('/home', 'HomeController@index')->name('auth.home');
        Route::get('logout', 'AuthController@logout')->name('auth.logout');
        
        
        // MASTER //
        Route::group(['prefix' => 'department'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'DepartmentController@create')->name('department.create');
                Route::post('/store', 'DepartmentController@store')->name('department.store');
                Route::post('/destroy', 'DepartmentController@destroy')->name('department.destroy');
                Route::get('/show_data_department', 'DepartmentController@show_data_department')->name('department.show_data_department');
                Route::get('/show_data_department_ajax', 'DepartmentController@show_data_department_ajax')->name('department.show_data_department_ajax');
            });
        });

        Route::group(['prefix' => 'folder'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'FolderController@create')->name('folder.create');
                Route::post('/store', 'FolderController@store')->name('folder.store');
                Route::post('/destroy', 'FolderController@destroy')->name('folder.destroy');
                Route::get('/show_data_folder', 'FolderController@show_data_folder')->name('folder.show_data_folder');
                Route::get('/show_data_folder_ajax', 'FolderController@show_data_folder_ajax')->name('folder.show_data_folder_ajax');
            });
        });

        Route::group(['prefix' => 'subfolder'], function(){
            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'SubFolderController@create')->name('subfolder.create');
                Route::post('/store', 'SubFolderController@store')->name('subfolder.store');
                Route::post('/destroy', 'SubFolderController@destroy')->name('subfolder.destroy');
                Route::get('/show_data_subfolder', 'SubFolderController@show_data_subfolder')->name('subfolder.show_data_subfolder');
                Route::get('/show_data_subfolder_ajax', 'SubFolderController@show_data_subfolder_ajax')->name('subfolder.show_data_subfolder_ajax');
            });
        });

        Route::group(['prefix' => 'user'], function(){
            Route::get('/edit', 'UserController@edit')->name('user.edit');
            Route::put('/update', 'UserController@update')->name('user.update');

            Route::group(['middleware' => ['can:can_master']], function () {
                Route::get('/create', 'UserController@create')->name('user.create');
                Route::post('/store', 'UserController@store')->name('user.store');
                Route::post('/destroy', 'UserController@destroy')->name('user.destroy');
                Route::get('/show_data_user', 'UserController@show_data_user')->name('user.show_data_user');
                Route::get('/show_data_user_ajax', 'UserController@show_data_user_ajax')->name('user.show_data_user_ajax');
            });
        });

        
        // FORM ACCOUNT //
        Route::group(['prefix' => 'account'], function(){
            Route::group(['middleware' => ['can:can_create_form']], function () {
            Route::get('/create', 'AccountController@create')->name('account.create');
            Route::post('/store', 'AccountController@store')->name('account.store');
            Route::get('/edit/{id}', 'AccountController@edit')->name('account.edit');
            Route::post('/update/{id}', 'AccountController@update')->name('account.update');
            Route::get('/show_data_form', 'AccountController@show_data_form')->name('account.show_data_form');
            Route::get('/show_data_form_ajax', 'AccountController@show_data_form_ajax')->name('account.show_data_form_ajax');
            Route::post('/approve_form', 'AccountController@approve_form')->name('account.approve_form');
            Route::post('/delete_form', 'AccountController@delete_form')->name('account.delete_form');
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
            Route::get('/show_data_form', 'FolderAccessController@show_data_form')->name('folder-access.show_data_form');
            Route::get('/show_data_form_ajax', 'FolderAccessController@show_data_form_ajax')->name('folder-access.show_data_form_ajax'); 
            Route::post('/approve_form', 'FolderAccessController@approve_form')->name('folder-access.approve_form');  

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
                Route::get('/show_execution', 'FolderAccessController@show_execution')->name('folder-access.show_execution');
                Route::get('/show_execution_ajax', 'FolderAccessController@show_execution_ajax')->name('folder-access.show_execution_ajax');
                Route::post('/approve_execution', 'FolderAccessController@approve_execution')->name('folder-access.approve_execution');
                Route::get('/show_data_execution', 'FolderAccessController@show_data_execution')->name('folder-access.show_data_execution');
                Route::get('/show_data_execution_ajax', 'FolderAccessController@show_data_execution_ajax')->name('folder-access.show_data_execution_ajax');
            });
        });
        // FORM NEW FOLDER //
        Route::group(['prefix' => 'new-folder'], function(){
            Route::get('/create', 'NewFolderController@create')->name('new-folder.create');
            Route::post('/store', 'NewFolderController@store')->name('new-folder.store');
            Route::get('/subfolder_ajax', 'NewFolderController@subfolder_ajax')->name('new-folder.subfolder_ajax');
            Route::get('/show_data_form', 'NewFolderController@show_data_form')->name('new-folder.show_data_form');
            Route::get('/show_data_form_ajax', 'NewFolderController@show_data_form_ajax')->name('new-folder.show_data_form_ajax');  
            Route::post('/approve_form', 'NewFolderController@approve_form')->name('new-folder.approve_form');

            Route::group(['middleware' => ['can:can_approve_mgr']], function () {
                Route::get('/show_manager_approval', 'NewFolderController@show_manager_approval')->name('new-folder.show_manager_approval');
                Route::get('/show_manager_approval_ajax', 'NewFolderController@show_manager_approval_ajax')->name('new-folder.show_manager_approval_ajax');
                Route::post('/approve_manager', 'NewFolderController@approve_manager')->name('new-folder.approve_manager');
                Route::get('/show_data_manager_approval', 'NewFolderController@show_data_manager_approval')->name('new-folder.show_data_manager_approval');
                Route::get('/show_data_manager_approval_ajax', 'NewFolderController@show_data_manager_approval_ajax')->name('new-folder.show_data_manager_approval_ajax');                
            });
            Route::group(['middleware' => ['can:can_approve_it']], function () {
                Route::get('/show_it_approval', 'NewFolderController@show_it_approval')->name('new-folder.show_it_approval');
                Route::get('/show_it_approval_ajax', 'NewFolderController@show_it_approval_ajax')->name('new-folder.show_it_approval_ajax');
                Route::post('/approve_it', 'NewFolderController@approve_it')->name('new-folder.approve_it');
                Route::get('/show_data_it_approval', 'NewFolderController@show_data_it_approval')->name('new-folder.show_data_it_approval');
                Route::get('/show_data_it_approval_ajax', 'NewFolderController@show_data_it_approval_ajax')->name('new-folder.show_data_it_approval_ajax');
            });
            Route::group(['middleware' => ['can:can_approve_it_mgr']], function () {
                Route::get('/show_it_mgr_approval', 'NewFolderController@show_it_mgr_approval')->name('new-folder.show_it_mgr_approval');
                Route::get('/show_it_mgr_approval_ajax', 'NewFolderController@show_it_mgr_approval_ajax')->name('new-folder.show_it_mgr_approval_ajax');
                Route::post('/approve_it_mgr', 'NewFolderController@approve_it_mgr')->name('new-folder.approve_it_mgr');
                Route::get('/show_data_it_mgr_approval', 'NewFolderController@show_data_it_mgr_approval')->name('new-folder.show_data_it_mgr_approval');
                Route::get('/show_data_it_mgr_approval_ajax', 'NewFolderController@show_data_it_mgr_approval_ajax')->name('new-folder.show_data_it_mgr_approval_ajax');
            });

            Route::group(['middleware' => ['can:can_execution']], function () {
                Route::get('/show_execution', 'NewFolderController@show_execution')->name('new-folder.show_execution');
                Route::get('/show_execution_ajax', 'NewFolderController@show_execution_ajax')->name('new-folder.show_execution_ajax');
                Route::post('/approve_execution', 'NewFolderController@approve_execution')->name('new-folder.approve_execution');
                Route::get('/show_data_execution', 'NewFolderController@show_data_execution')->name('new-folder.show_data_execution');
                Route::get('/show_data_execution_ajax', 'NewFolderController@show_data_execution_ajax')->name('new-folder.show_data_execution_ajax');
            });
        });
        // FORM SOFTWARE //
        Route::group(['prefix' => 'software'], function(){
            Route::get('/create', 'SoftwareController@create')->name('software.create');
            Route::post('/store', 'SoftwareController@store')->name('software.store');
            Route::get('/subfolder_ajax', 'SoftwareController@subfolder_ajax')->name('software.subfolder_ajax');
            Route::get('/show_data_form', 'SoftwareController@show_data_form')->name('software.show_data_form');
            Route::get('/show_data_form_ajax', 'SoftwareController@show_data_form_ajax')->name('software.show_data_form_ajax'); 
            Route::post('/approve_form', 'SoftwareController@approve_form')->name('software.approve_form');

            Route::group(['middleware' => ['can:can_approve_mgr']], function () {
                Route::get('/show_manager_approval', 'SoftwareController@show_manager_approval')->name('software.show_manager_approval');
                Route::get('/show_manager_approval_ajax', 'SoftwareController@show_manager_approval_ajax')->name('software.show_manager_approval_ajax');
                Route::post('/approve_manager', 'SoftwareController@approve_manager')->name('software.approve_manager');
                Route::get('/show_data_manager_approval', 'SoftwareController@show_data_manager_approval')->name('software.show_data_manager_approval');
                Route::get('/show_data_manager_approval_ajax', 'SoftwareController@show_data_manager_approval_ajax')->name('software.show_data_manager_approval_ajax');                
            });
            Route::group(['middleware' => ['can:can_approve_it']], function () {
                Route::get('/show_it_approval', 'SoftwareController@show_it_approval')->name('software.show_it_approval');
                Route::get('/show_it_approval_ajax', 'SoftwareController@show_it_approval_ajax')->name('software.show_it_approval_ajax');
                Route::post('/approve_it', 'SoftwareController@approve_it')->name('software.approve_it');
                Route::get('/show_data_it_approval', 'SoftwareController@show_data_it_approval')->name('software.show_data_it_approval');
                Route::get('/show_data_it_approval_ajax', 'SoftwareController@show_data_it_approval_ajax')->name('software.show_data_it_approval_ajax');
            });
            Route::group(['middleware' => ['can:can_approve_it_mgr']], function () {
                Route::get('/show_it_mgr_approval', 'SoftwareController@show_it_mgr_approval')->name('software.show_it_mgr_approval');
                Route::get('/show_it_mgr_approval_ajax', 'SoftwareController@show_it_mgr_approval_ajax')->name('software.show_it_mgr_approval_ajax');
                Route::post('/approve_it_mgr', 'SoftwareController@approve_it_mgr')->name('software.approve_it_mgr');
                Route::get('/show_data_it_mgr_approval', 'SoftwareController@show_data_it_mgr_approval')->name('software.show_data_it_mgr_approval');
                Route::get('/show_data_it_mgr_approval_ajax', 'SoftwareController@show_data_it_mgr_approval_ajax')->name('software.show_data_it_mgr_approval_ajax');
            });

            Route::group(['middleware' => ['can:can_execution']], function () {
                Route::get('/show_execution', 'SoftwareController@show_execution')->name('software.show_execution');
                Route::get('/show_execution_ajax', 'SoftwareController@show_execution_ajax')->name('software.show_execution_ajax');
                Route::post('/approve_execution', 'SoftwareController@approve_execution')->name('software.approve_execution');
                Route::get('/show_data_execution', 'SoftwareController@show_data_execution')->name('software.show_data_execution');
                Route::get('/show_data_execution_ajax', 'SoftwareController@show_data_execution_ajax')->name('software.show_data_execution_ajax');
            });
        });
        // FORM HARDWARE //
        Route::group(['prefix' => 'hardware'], function(){
            Route::get('/create', 'HardwareController@create')->name('hardware.create');
            Route::post('/store', 'HardwareController@store')->name('hardware.store');
            Route::get('/subfolder_ajax', 'HardwareController@subfolder_ajax')->name('hardware.subfolder_ajax');
            Route::get('/show_data_form', 'HardwareController@show_data_form')->name('hardware.show_data_form');
            Route::get('/show_data_form_ajax', 'HardwareController@show_data_form_ajax')->name('hardware.show_data_form_ajax'); 
            Route::post('/approve_form', 'HardwareController@approve_form')->name('hardware.approve_form');

            Route::group(['middleware' => ['can:can_approve_mgr']], function () {
                Route::get('/show_manager_approval', 'HardwareController@show_manager_approval')->name('hardware.show_manager_approval');
                Route::get('/show_manager_approval_ajax', 'HardwareController@show_manager_approval_ajax')->name('hardware.show_manager_approval_ajax');
                Route::post('/approve_manager', 'HardwareController@approve_manager')->name('hardware.approve_manager');
                Route::get('/show_data_manager_approval', 'HardwareController@show_data_manager_approval')->name('hardware.show_data_manager_approval');
                Route::get('/show_data_manager_approval_ajax', 'HardwareController@show_data_manager_approval_ajax')->name('hardware.show_data_manager_approval_ajax');                
            });
            Route::group(['middleware' => ['can:can_approve_it']], function () {
                Route::get('/show_it_approval', 'HardwareController@show_it_approval')->name('hardware.show_it_approval');
                Route::get('/show_it_approval_ajax', 'HardwareController@show_it_approval_ajax')->name('hardware.show_it_approval_ajax');
                Route::post('/approve_it', 'HardwareController@approve_it')->name('hardware.approve_it');
                Route::get('/show_data_it_approval', 'HardwareController@show_data_it_approval')->name('hardware.show_data_it_approval');
                Route::get('/show_data_it_approval_ajax', 'HardwareController@show_data_it_approval_ajax')->name('hardware.show_data_it_approval_ajax');
            });
            Route::group(['middleware' => ['can:can_approve_it_mgr']], function () {
                Route::get('/show_it_mgr_approval', 'HardwareController@show_it_mgr_approval')->name('hardware.show_it_mgr_approval');
                Route::get('/show_it_mgr_approval_ajax', 'HardwareController@show_it_mgr_approval_ajax')->name('hardware.show_it_mgr_approval_ajax');
                Route::post('/approve_it_mgr', 'HardwareController@approve_it_mgr')->name('hardware.approve_it_mgr');
                Route::get('/show_data_it_mgr_approval', 'HardwareController@show_data_it_mgr_approval')->name('hardware.show_data_it_mgr_approval');
                Route::get('/show_data_it_mgr_approval_ajax', 'HardwareController@show_data_it_mgr_approval_ajax')->name('hardware.show_data_it_mgr_approval_ajax');
            });

            Route::group(['middleware' => ['can:can_execution']], function () {
                Route::get('/show_execution', 'HardwareController@show_execution')->name('hardware.show_execution');
                Route::get('/show_execution_ajax', 'HardwareController@show_execution_ajax')->name('hardware.show_execution_ajax');
                Route::post('/approve_execution', 'HardwareController@approve_execution')->name('hardware.approve_execution');
                Route::get('/show_data_execution', 'HardwareController@show_data_execution')->name('hardware.show_data_execution');
                Route::get('/show_data_execution_ajax', 'HardwareController@show_data_execution_ajax')->name('hardware.show_data_execution_ajax');
            });
        });
        // FORM VPN //
        Route::group(['prefix' => 'vpn'], function(){
            Route::get('/create', 'VpnController@create')->name('vpn.create');
            Route::post('/store', 'VpnController@store')->name('vpn.store');
            Route::get('/subfolder_ajax', 'VpnController@subfolder_ajax')->name('vpn.subfolder_ajax');
            Route::get('/show_data_form', 'VpnController@show_data_form')->name('vpn.show_data_form');
            Route::get('/show_data_form_ajax', 'VpnController@show_data_form_ajax')->name('vpn.show_data_form_ajax');   
            Route::post('/approve_form', 'VpnController@approve_form')->name('vpn.approve_form');

            Route::group(['middleware' => ['can:can_approve_mgr']], function () {
                Route::get('/show_manager_approval', 'VpnController@show_manager_approval')->name('vpn.show_manager_approval');
                Route::get('/show_manager_approval_ajax', 'VpnController@show_manager_approval_ajax')->name('vpn.show_manager_approval_ajax');
                Route::post('/approve_manager', 'VpnController@approve_manager')->name('vpn.approve_manager');
                Route::get('/show_data_manager_approval', 'VpnController@show_data_manager_approval')->name('vpn.show_data_manager_approval');
                Route::get('/show_data_manager_approval_ajax', 'VpnController@show_data_manager_approval_ajax')->name('vpn.show_data_manager_approval_ajax');                
            });
            Route::group(['middleware' => ['can:can_approve_it']], function () {
                Route::get('/show_it_approval', 'VpnController@show_it_approval')->name('vpn.show_it_approval');
                Route::get('/show_it_approval_ajax', 'VpnController@show_it_approval_ajax')->name('vpn.show_it_approval_ajax');
                Route::post('/approve_it', 'VpnController@approve_it')->name('vpn.approve_it');
                Route::get('/show_data_it_approval', 'VpnController@show_data_it_approval')->name('vpn.show_data_it_approval');
                Route::get('/show_data_it_approval_ajax', 'VpnController@show_data_it_approval_ajax')->name('vpn.show_data_it_approval_ajax');
            });
            Route::group(['middleware' => ['can:can_approve_it_mgr']], function () {
                Route::get('/show_it_mgr_approval', 'VpnController@show_it_mgr_approval')->name('vpn.show_it_mgr_approval');
                Route::get('/show_it_mgr_approval_ajax', 'VpnController@show_it_mgr_approval_ajax')->name('vpn.show_it_mgr_approval_ajax');
                Route::post('/approve_it_mgr', 'VpnController@approve_it_mgr')->name('vpn.approve_it_mgr');
                Route::get('/show_data_it_mgr_approval', 'VpnController@show_data_it_mgr_approval')->name('vpn.show_data_it_mgr_approval');
                Route::get('/show_data_it_mgr_approval_ajax', 'VpnController@show_data_it_mgr_approval_ajax')->name('vpn.show_data_it_mgr_approval_ajax');
            });

            Route::group(['middleware' => ['can:can_execution']], function () {
                Route::get('/show_execution', 'VpnController@show_execution')->name('vpn.show_execution');
                Route::get('/show_execution_ajax', 'VpnController@show_execution_ajax')->name('vpn.show_execution_ajax');
                Route::post('/approve_execution', 'VpnController@approve_execution')->name('vpn.approve_execution');
                Route::get('/show_data_execution', 'VpnController@show_data_execution')->name('vpn.show_data_execution');
                Route::get('/show_data_execution_ajax', 'VpnController@show_data_execution_ajax')->name('vpn.show_data_execution_ajax');
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