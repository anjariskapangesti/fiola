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
    Route::get('logout', 'AuthController@logout')->name('auth.logout');

    Route::get('alert', 'AlertController@alert')->name('alert');
    Route::get('alert_view', 'AlertController@alert_view')->name('alert_view');
    Route::get('/email_manager', 'ReminderController@email_manager')->name('reminder.email_manager');

    Route::middleware('auth.web')->group(function () {
        Route::group(['middleware' => ['permission:apps_fiola']], function () {
            Route::get('/', 'HomeController@index')->name('home');
            Route::get('/home_ajax', 'HomeController@home_ajax')->name('home_ajax');
            Route::get('/home', 'HomeController@index')->name('auth.home');
            Route::get('/mail', function () {
                \Illuminate\Support\Facades\Mail::send(new \App\Mail\TaskReminder());

                return view ('website.pages.home');
            });
            Route::get('/get_approval_count', 'AppHelperController@getApprovalCount')->name('get_approval_count');

            // MASTER //
            Route::group(['prefix' => 'reminder'], function(){
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'ReminderController@create')->name('reminder.create');
                    Route::post('/store', 'ReminderController@store')->name('reminder.store');
                    Route::post('/edit', 'ReminderController@edit')->name('reminder.edit');
                    Route::post('/destroy', 'ReminderController@destroy')->name('reminder.destroy');
                    Route::get('/list', 'ReminderController@list')->name('reminder.list');
                    Route::get('/list_ajax', 'ReminderController@list_ajax')->name('reminder.list_ajax');
                });
            });

            Route::group(['prefix' => 'alert'], function(){
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'AlertController@create')->name('alert.create');
                    Route::post('/store', 'AlertController@store')->name('alert.store');
                    Route::get('/edit/{id}', 'AlertController@edit')->name('alert.edit');
                    Route::post('/update/{id}', 'AlertController@update')->name('alert.update');
                    Route::post('/destroy', 'AlertController@destroy')->name('alert.destroy');
                    Route::get('/list', 'AlertController@list')->name('alert.list');
                    Route::get('/list_ajax', 'AlertController@list_ajax')->name('alert.list_ajax');
                });
            });

            Route::group(['prefix' => 'device'], function(){
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'DeviceController@create')->name('device.create');
                    Route::post('/store', 'DeviceController@store')->name('device.store');
                    Route::post('/edit', 'DeviceController@edit')->name('device.edit');
                    Route::post('/destroy', 'DeviceController@destroy')->name('device.destroy');
                    Route::get('/list', 'DeviceController@list')->name('device.list');
                    Route::get('/list_ajax', 'DeviceController@list_ajax')->name('device.list_ajax');
                });
            });

            Route::group(['prefix' => 'department'], function(){
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'DepartmentController@create')->name('department.create');
                    Route::post('/store', 'DepartmentController@store')->name('department.store');
                    Route::post('/edit', 'DepartmentController@edit')->name('department.edit');
                    Route::post('/destroy', 'DepartmentController@destroy')->name('department.destroy');
                    Route::get('/show_data_department', 'DepartmentController@show_data_department')->name('department.show_data_department');
                    Route::get('/show_data_department_ajax', 'DepartmentController@show_data_department_ajax')->name('department.show_data_department_ajax');
                });
            });

            Route::group(['prefix' => 'folder'], function(){
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'FolderController@create')->name('folder.create');
                    Route::post('/store', 'FolderController@store')->name('folder.store');
                    Route::get('/edit/{id}', 'FolderController@edit')->name('folder.edit');
                    Route::post('/update/{id}', 'FolderController@update')->name('folder.update');
                    Route::post('/destroy', 'FolderController@destroy')->name('folder.destroy');
                    Route::get('/list', 'FolderController@list')->name('folder.list');
                    Route::get('/list_ajax', 'FolderController@list_ajax')->name('folder.list_ajax');
                });
            });

            Route::group(['prefix' => 'subfolder'], function(){
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'SubFolderController@create')->name('subfolder.create');
                    Route::post('/store', 'SubFolderController@store')->name('subfolder.store');
                    Route::get('/edit/{id}', 'SubFolderController@edit')->name('subfolder.edit');
                    Route::post('/update/{id}', 'SubFolderController@update')->name('subfolder.update');
                    Route::post('/destroy', 'SubFolderController@destroy')->name('subfolder.destroy');
                    Route::get('/list', 'SubFolderController@list')->name('subfolder.list');
                    Route::get('/list_ajax', 'SubFolderController@list_ajax')->name('subfolder.list_ajax');
                });
            });

            Route::group(['prefix' => 'user'], function(){
                Route::get('/edit', 'UserController@edit')->name('user.edit');
                Route::put('/update', 'UserController@update')->name('user.update');

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/create', 'UserController@create')->name('user.create');
                    Route::post('/store', 'UserController@store')->name('user.store');
                    Route::post('/destroy', 'UserController@destroy')->name('user.destroy');
                    Route::get('/list', 'UserController@list')->name('user.list');
                    Route::get('/list_ajax', 'UserController@list_ajax')->name('user.list_ajax');
                });
            });


            // FORM ACCOUNT //
            Route::group(['prefix' => 'account'], function(){
                Route::get('/create', 'AccountController@create')->name('account.create');
                Route::post('/store', 'AccountController@store')->name('account.store');
                Route::get('/edit/{id}', 'AccountController@edit')->name('account.edit');
                Route::post('/update/{id}', 'AccountController@update')->name('account.update');
                Route::get('/list', 'AccountController@list')->name('account.list');
                Route::get('/list_ajax', 'AccountController@list_ajax')->name('account.list_ajax');
                Route::post('/approve_form', 'AccountController@approve_form')->name('account.approve_form');
                Route::post('/delete_form', 'AccountController@delete_form')->name('account.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'AccountController@manager_approval')->name('account.manager_approval');
                    Route::get('/manager_approval_ajax', 'AccountController@manager_approval_ajax')->name('account.manager_approval_ajax');
                    Route::post('/manager_approve', 'AccountController@manager_approve')->name('account.manager_approve');
                    Route::get('/manager_approved', 'AccountController@manager_approved')->name('account.manager_approved');
                    Route::get('/manager_approved_ajax', 'AccountController@manager_approved_ajax')->name('account.manager_approved_ajax');

                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'AccountController@it_approval')->name('account.it_approval');
                    Route::get('/it_approval_ajax', 'AccountController@it_approval_ajax')->name('account.it_approval_ajax');
                    Route::post('/it_approve', 'AccountController@it_approve')->name('account.it_approve');
                    Route::get('/it_approved', 'AccountController@it_approved')->name('account.it_approved');
                    Route::get('/it_approved_ajax', 'AccountController@it_approved_ajax')->name('account.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'AccountController@it_mgr_approval')->name('account.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'AccountController@it_mgr_approval_ajax')->name('account.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'AccountController@it_mgr_approve')->name('account.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'AccountController@it_mgr_approved')->name('account.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'AccountController@it_mgr_approved_ajax')->name('account.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'AccountController@execution')->name('account.execution');
                    Route::get('/execution_ajax', 'AccountController@execution_ajax')->name('account.execution_ajax');
                    Route::post('/execution_approve', 'AccountController@execution_approve')->name('account.execution_approve');
                    Route::get('/finished', 'AccountController@finished')->name('account.finished');
                    Route::get('/finished_ajax', 'AccountController@finished_ajax')->name('account.finished_ajax');
                });

            });
            // FORM FOLDER ACCESS //
            Route::group(['prefix' => 'folder-access'], function(){
                Route::get('/create', 'FolderAccessController@create')->name('folder-access.create');
                Route::post('/store', 'FolderAccessController@store')->name('folder-access.store');
                Route::get('/subfolder_ajax', 'FolderAccessController@subfolder_ajax')->name('folder-access.subfolder_ajax');
                Route::get('/list', 'FolderAccessController@list')->name('folder-access.list');
                Route::get('/list_ajax', 'FolderAccessController@list_ajax')->name('folder-access.list_ajax'); 
                Route::post('/approve_form', 'FolderAccessController@approve_form')->name('folder-access.approve_form');  
                Route::post('/delete_form', 'FolderAccessController@delete_form')->name('folder-access.delete_form');
                Route::get('/get_data_subfolder', 'FolderAccessController@get_data_subfolder')->name('folder-access.get_data_subfolder');  

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'FolderAccessController@manager_approval')->name('folder-access.manager_approval');
                    Route::get('/manager_approval_ajax', 'FolderAccessController@manager_approval_ajax')->name('folder-access.manager_approval_ajax');
                    Route::post('/manager_approve', 'FolderAccessController@manager_approve')->name('folder-access.manager_approve');
                    Route::get('/manager_approved', 'FolderAccessController@manager_approved')->name('folder-access.manager_approved');
                    Route::get('/manager_approved_ajax', 'FolderAccessController@manager_approved_ajax')->name('folder-access.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'FolderAccessController@it_approval')->name('folder-access.it_approval');
                    Route::get('/it_approval_ajax', 'FolderAccessController@it_approval_ajax')->name('folder-access.it_approval_ajax');
                    Route::post('/it_approve', 'FolderAccessController@it_approve')->name('folder-access.it_approve');
                    Route::get('/it_approved', 'FolderAccessController@it_approved')->name('folder-access.it_approved');
                    Route::get('/it_approved_ajax', 'FolderAccessController@it_approved_ajax')->name('folder-access.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'FolderAccessController@it_mgr_approval')->name('folder-access.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'FolderAccessController@it_mgr_approval_ajax')->name('folder-access.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'FolderAccessController@it_mgr_approve')->name('folder-access.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'FolderAccessController@it_mgr_approved')->name('folder-access.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'FolderAccessController@it_mgr_approved_ajax')->name('folder-access.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'FolderAccessController@execution')->name('folder-access.execution');
                    Route::get('/execution_ajax', 'FolderAccessController@execution_ajax')->name('folder-access.execution_ajax');
                    Route::post('/execution_approve', 'FolderAccessController@execution_approve')->name('folder-access.execution_approve');
                    Route::get('/finished', 'FolderAccessController@finished')->name('folder-access.finished');
                    Route::get('/finished_ajax', 'FolderAccessController@finished_ajax')->name('folder-access.finished_ajax');
                });
            });
            // FORM NEW FOLDER //
            Route::group(['prefix' => 'new-folder'], function(){
                Route::get('/create', 'NewFolderController@create')->name('new-folder.create');
                Route::post('/store', 'NewFolderController@store')->name('new-folder.store');
                Route::get('/subfolder_ajax', 'NewFolderController@subfolder_ajax')->name('new-folder.subfolder_ajax');
                Route::get('/list', 'NewFolderController@list')->name('new-folder.list');
                Route::get('/list_ajax', 'NewFolderController@list_ajax')->name('new-folder.list_ajax');  
                Route::post('/approve_form', 'NewFolderController@approve_form')->name('new-folder.approve_form');
                Route::post('/delete_form', 'NewFolderController@delete_form')->name('new-folder.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'NewFolderController@manager_approval')->name('new-folder.manager_approval');
                    Route::get('/manager_approval_ajax', 'NewFolderController@manager_approval_ajax')->name('new-folder.manager_approval_ajax');
                    Route::post('/manager_approve', 'NewFolderController@manager_approve')->name('new-folder.manager_approve');
                    Route::get('/manager_approved', 'NewFolderController@manager_approved')->name('new-folder.manager_approved');
                    Route::get('/manager_approved_ajax', 'NewFolderController@manager_approved_ajax')->name('new-folder.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'NewFolderController@it_approval')->name('new-folder.it_approval');
                    Route::get('/it_approval_ajax', 'NewFolderController@it_approval_ajax')->name('new-folder.it_approval_ajax');
                    Route::post('/it_approve', 'NewFolderController@it_approve')->name('new-folder.it_approve');
                    Route::get('/it_approved', 'NewFolderController@it_approved')->name('new-folder.it_approved');
                    Route::get('/it_approved_ajax', 'NewFolderController@it_approved_ajax')->name('new-folder.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'NewFolderController@it_mgr_approval')->name('new-folder.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'NewFolderController@it_mgr_approval_ajax')->name('new-folder.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'NewFolderController@it_mgr_approve')->name('new-folder.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'NewFolderController@it_mgr_approved')->name('new-folder.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'NewFolderController@it_mgr_approved_ajax')->name('new-folder.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'NewFolderController@execution')->name('new-folder.execution');
                    Route::get('/execution_ajax', 'NewFolderController@execution_ajax')->name('new-folder.execution_ajax');
                    Route::post('/execution_approve', 'NewFolderController@execution_approve')->name('new-folder.execution_approve');
                    Route::get('/finished', 'NewFolderController@finished')->name('new-folder.finished');
                    Route::get('/finished_ajax', 'NewFolderController@finished_ajax')->name('new-folder.finished_ajax');
                });
            });
            // FORM SOFTWARE //
            Route::group(['prefix' => 'software'], function(){
                Route::get('/create', 'SoftwareController@create')->name('software.create');
                Route::post('/store', 'SoftwareController@store')->name('software.store');
                Route::get('/subfolder_ajax', 'SoftwareController@subfolder_ajax')->name('software.subfolder_ajax');
                Route::get('/list', 'SoftwareController@list')->name('software.list');
                Route::get('/list_ajax', 'SoftwareController@list_ajax')->name('software.list_ajax'); 
                Route::post('/approve_form', 'SoftwareController@approve_form')->name('software.approve_form');
                Route::post('/delete_form', 'SoftwareController@delete_form')->name('software.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'SoftwareController@manager_approval')->name('software.manager_approval');
                    Route::get('/manager_approval_ajax', 'SoftwareController@manager_approval_ajax')->name('software.manager_approval_ajax');
                    Route::post('/manager_approve', 'SoftwareController@manager_approve')->name('software.manager_approve');
                    Route::get('/manager_approved', 'SoftwareController@manager_approved')->name('software.manager_approved');
                    Route::get('/manager_approved_ajax', 'SoftwareController@manager_approved_ajax')->name('software.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'SoftwareController@it_approval')->name('software.it_approval');
                    Route::get('/it_approval_ajax', 'SoftwareController@it_approval_ajax')->name('software.it_approval_ajax');
                    Route::post('/it_approve', 'SoftwareController@it_approve')->name('software.it_approve');
                    Route::get('/it_approved', 'SoftwareController@it_approved')->name('software.it_approved');
                    Route::get('/it_approved_ajax', 'SoftwareController@it_approved_ajax')->name('software.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'SoftwareController@it_mgr_approval')->name('software.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'SoftwareController@it_mgr_approval_ajax')->name('software.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'SoftwareController@it_mgr_approve')->name('software.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'SoftwareController@it_mgr_approved')->name('software.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'SoftwareController@it_mgr_approved_ajax')->name('software.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'SoftwareController@execution')->name('software.execution');
                    Route::get('/execution_ajax', 'SoftwareController@execution_ajax')->name('software.execution_ajax');
                    Route::post('/execution_approve', 'SoftwareController@execution_approve')->name('software.execution_approve');
                    Route::get('/finished', 'SoftwareController@finished')->name('software.finished');
                    Route::get('/finished_ajax', 'SoftwareController@finished_ajax')->name('software.finished_ajax');
                });
            });
            // FORM HARDWARE //
            Route::group(['prefix' => 'hardware'], function(){
                Route::get('/create', 'HardwareController@create')->name('hardware.create');
                Route::post('/store', 'HardwareController@store')->name('hardware.store');
                Route::get('/edit/{id}', 'HardwareController@edit')->name('hardware.edit');
                Route::post('/update/{id}', 'HardwareController@update')->name('hardware.update');
                Route::get('/subfolder_ajax', 'HardwareController@subfolder_ajax')->name('hardware.subfolder_ajax');
                Route::get('/list', 'HardwareController@list')->name('hardware.list');
                Route::get('/list_ajax', 'HardwareController@list_ajax')->name('hardware.list_ajax'); 
                Route::post('/approve_form', 'HardwareController@approve_form')->name('hardware.approve_form');
                Route::post('/delete_form', 'HardwareController@delete_form')->name('hardware.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'HardwareController@manager_approval')->name('hardware.manager_approval');
                    Route::get('/manager_approval_ajax', 'HardwareController@manager_approval_ajax')->name('hardware.manager_approval_ajax');
                    Route::post('/manager_approve', 'HardwareController@manager_approve')->name('hardware.manager_approve');
                    Route::get('/manager_approved', 'HardwareController@manager_approved')->name('hardware.manager_approved');
                    Route::get('/manager_approved_ajax', 'HardwareController@manager_approved_ajax')->name('hardware.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'HardwareController@it_approval')->name('hardware.it_approval');
                    Route::get('/it_approval_ajax', 'HardwareController@it_approval_ajax')->name('hardware.it_approval_ajax');
                    Route::post('/it_approve', 'HardwareController@it_approve')->name('hardware.it_approve');
                    Route::get('/it_approved', 'HardwareController@it_approved')->name('hardware.it_approved');
                    Route::get('/it_approved_ajax', 'HardwareController@it_approved_ajax')->name('hardware.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'HardwareController@it_mgr_approval')->name('hardware.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'HardwareController@it_mgr_approval_ajax')->name('hardware.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'HardwareController@it_mgr_approve')->name('hardware.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'HardwareController@it_mgr_approved')->name('hardware.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'HardwareController@it_mgr_approved_ajax')->name('hardware.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'HardwareController@execution')->name('hardware.execution');
                    Route::get('/execution_ajax', 'HardwareController@execution_ajax')->name('hardware.execution_ajax');
                    Route::post('/execution_approve', 'HardwareController@execution_approve')->name('hardware.execution_approve');
                    Route::get('/finished', 'HardwareController@finished')->name('hardware.finished');
                    Route::get('/finished_ajax', 'HardwareController@finished_ajax')->name('hardware.finished_ajax');
                });
            });
            // FORM VPN //
            Route::group(['prefix' => 'vpn'], function(){
                Route::get('/create', 'VpnController@create')->name('vpn.create');
                Route::post('/store', 'VpnController@store')->name('vpn.store');
                Route::get('/subfolder_ajax', 'VpnController@subfolder_ajax')->name('vpn.subfolder_ajax');
                Route::get('/list', 'VpnController@list')->name('vpn.list');
                Route::get('/list_ajax', 'VpnController@list_ajax')->name('vpn.list_ajax');   
                Route::post('/approve_form', 'VpnController@approve_form')->name('vpn.approve_form');
                Route::post('/delete_form', 'VpnController@delete_form')->name('vpn.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'VpnController@manager_approval')->name('vpn.manager_approval');
                    Route::get('/manager_approval_ajax', 'VpnController@manager_approval_ajax')->name('vpn.manager_approval_ajax');
                    Route::post('/manager_approve', 'VpnController@manager_approve')->name('vpn.manager_approve');
                    Route::get('/manager_approved', 'VpnController@manager_approved')->name('vpn.manager_approved');
                    Route::get('/manager_approved_ajax', 'VpnController@manager_approved_ajax')->name('vpn.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'VpnController@it_approval')->name('vpn.it_approval');
                    Route::get('/it_approval_ajax', 'VpnController@it_approval_ajax')->name('vpn.it_approval_ajax');
                    Route::post('/it_approve', 'VpnController@it_approve')->name('vpn.it_approve');
                    Route::get('/it_approved', 'VpnController@it_approved')->name('vpn.it_approved');
                    Route::get('/it_approved_ajax', 'VpnController@it_approved_ajax')->name('vpn.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'VpnController@it_mgr_approval')->name('vpn.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'VpnController@it_mgr_approval_ajax')->name('vpn.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'VpnController@it_mgr_approve')->name('vpn.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'VpnController@it_mgr_approved')->name('vpn.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'VpnController@it_mgr_approved_ajax')->name('vpn.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'VpnController@execution')->name('vpn.execution');
                    Route::get('/execution_ajax', 'VpnController@execution_ajax')->name('vpn.execution_ajax');
                    Route::post('/execution_approve', 'VpnController@execution_approve')->name('vpn.execution_approve');
                    Route::get('/finished', 'VpnController@finished')->name('vpn.finished');
                    Route::get('/finished_ajax', 'VpnController@finished_ajax')->name('vpn.finished_ajax');
                });
            });
            // FORM NETWORK //
            Route::group(['prefix' => 'network'], function(){
                Route::get('/create', 'NetworkController@create')->name('network.create');
                Route::post('/store', 'NetworkController@store')->name('network.store');
                Route::get('/subfolder_ajax', 'NetworkController@subfolder_ajax')->name('network.subfolder_ajax');
                Route::get('/list', 'NetworkController@list')->name('network.list');
                Route::get('/list_ajax', 'NetworkController@list_ajax')->name('network.list_ajax');   
                Route::post('/approve_form', 'NetworkController@approve_form')->name('network.approve_form');
                Route::post('/delete_form', 'NetworkController@delete_form')->name('network.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'NetworkController@manager_approval')->name('network.manager_approval');
                    Route::get('/manager_approval_ajax', 'NetworkController@manager_approval_ajax')->name('network.manager_approval_ajax');
                    Route::post('/manager_approve', 'NetworkController@manager_approve')->name('network.manager_approve');
                    Route::get('/manager_approved', 'NetworkController@manager_approved')->name('network.manager_approved');
                    Route::get('/manager_approved_ajax', 'NetworkController@manager_approved_ajax')->name('network.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'NetworkController@it_approval')->name('network.it_approval');
                    Route::get('/it_approval_ajax', 'NetworkController@it_approval_ajax')->name('network.it_approval_ajax');
                    Route::post('/it_approve', 'NetworkController@it_approve')->name('network.it_approve');
                    Route::get('/it_approved', 'NetworkController@it_approved')->name('network.it_approved');
                    Route::get('/it_approved_ajax', 'NetworkController@it_approved_ajax')->name('network.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'NetworkController@it_mgr_approval')->name('network.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'NetworkController@it_mgr_approval_ajax')->name('network.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'NetworkController@it_mgr_approve')->name('network.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'NetworkController@it_mgr_approved')->name('network.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'NetworkController@it_mgr_approved_ajax')->name('network.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'NetworkController@execution')->name('network.execution');
                    Route::get('/execution_ajax', 'NetworkController@execution_ajax')->name('network.execution_ajax');
                    Route::post('/execution_approve', 'NetworkController@execution_approve')->name('network.execution_approve');
                    Route::get('/finished', 'NetworkController@finished')->name('network.finished');
                    Route::get('/finished_ajax', 'NetworkController@finished_ajax')->name('network.finished_ajax');
                });
            });
            // FORM PROJECT //
            Route::group(['prefix' => 'project'], function(){
                Route::get('/create', 'ProjectController@create')->name('project.create');
                Route::post('/store', 'ProjectController@store')->name('project.store');
                Route::get('/subfolder_ajax', 'ProjectController@subfolder_ajax')->name('project.subfolder_ajax');
                Route::get('/list', 'ProjectController@list')->name('project.list');
                Route::get('/list_ajax', 'ProjectController@list_ajax')->name('project.list_ajax');   
                Route::post('/approve_form', 'ProjectController@approve_form')->name('project.approve_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'ProjectController@manager_approval')->name('project.manager_approval');
                    Route::get('/manager_approval_ajax', 'ProjectController@manager_approval_ajax')->name('project.manager_approval_ajax');
                    Route::post('/manager_approve', 'ProjectController@manager_approve')->name('project.manager_approve');
                    Route::get('/manager_approved', 'ProjectController@manager_approved')->name('project.manager_approved');
                    Route::get('/manager_approved_ajax', 'ProjectController@manager_approved_ajax')->name('project.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'ProjectController@it_approval')->name('project.it_approval');
                    Route::get('/it_approval_ajax', 'ProjectController@it_approval_ajax')->name('project.it_approval_ajax');
                    Route::post('/it_approve', 'ProjectController@it_approve')->name('project.it_approve');
                    Route::get('/it_approved', 'ProjectController@it_approved')->name('project.it_approved');
                    Route::get('/it_approved_ajax', 'ProjectController@it_approved_ajax')->name('project.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'ProjectController@it_mgr_approval')->name('project.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'ProjectController@it_mgr_approval_ajax')->name('project.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'ProjectController@it_mgr_approve')->name('project.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'ProjectController@it_mgr_approved')->name('project.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'ProjectController@it_mgr_approved_ajax')->name('project.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'ProjectController@execution')->name('project.execution');
                    Route::get('/execution_ajax', 'ProjectController@execution_ajax')->name('project.execution_ajax');
                    Route::post('/execution_approve', 'ProjectController@execution_approve')->name('project.execution_approve');
                    Route::get('/finished', 'ProjectController@finished')->name('project.finished');
                    Route::get('/finished_ajax', 'ProjectController@finished_ajax')->name('project.finished_ajax');
                });
            });
            // FORM FITUR //
            Route::group(['prefix' => 'fitur'], function(){
                Route::get('/create', 'FiturController@create')->name('fitur.create');
                Route::post('/store', 'FiturController@store')->name('fitur.store');
                Route::get('/subfolder_ajax', 'FiturController@subfolder_ajax')->name('fitur.subfolder_ajax');
                Route::get('/list', 'FiturController@list')->name('fitur.list');
                Route::get('/list_ajax', 'FiturController@list_ajax')->name('fitur.list_ajax');   
                Route::post('/approve_form', 'FiturController@approve_form')->name('fitur.approve_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'FiturController@manager_approval')->name('fitur.manager_approval');
                    Route::get('/manager_approval_ajax', 'FiturController@manager_approval_ajax')->name('fitur.manager_approval_ajax');
                    Route::post('/manager_approve', 'FiturController@manager_approve')->name('fitur.manager_approve');
                    Route::get('/manager_approved', 'FiturController@manager_approved')->name('fitur.manager_approved');
                    Route::get('/manager_approved_ajax', 'FiturController@manager_approved_ajax')->name('fitur.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'FiturController@it_approval')->name('fitur.it_approval');
                    Route::get('/it_approval_ajax', 'FiturController@it_approval_ajax')->name('fitur.it_approval_ajax');
                    Route::post('/it_approve', 'FiturController@it_approve')->name('fitur.it_approve');
                    Route::get('/it_approved', 'FiturController@it_approved')->name('fitur.it_approved');
                    Route::get('/it_approved_ajax', 'FiturController@it_approved_ajax')->name('fitur.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'FiturController@it_mgr_approval')->name('fitur.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'FiturController@it_mgr_approval_ajax')->name('fitur.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'FiturController@it_mgr_approve')->name('fitur.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'FiturController@it_mgr_approved')->name('fitur.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'FiturController@it_mgr_approved_ajax')->name('fitur.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'FiturController@execution')->name('fitur.execution');
                    Route::get('/execution_ajax', 'FiturController@execution_ajax')->name('fitur.execution_ajax');
                    Route::post('/execution_approve', 'FiturController@execution_approve')->name('fitur.execution_approve');
                    Route::get('/finished', 'FiturController@finished')->name('fitur.finished');
                    Route::get('/finished_ajax', 'FiturController@finished_ajax')->name('fitur.finished_ajax');
                });
            });
            // FORM RELAYOUT //
            Route::group(['prefix' => 'relayout'], function(){
                Route::get('/create', 'RelayoutController@create')->name('relayout.create');
                Route::post('/store', 'RelayoutController@store')->name('relayout.store');
                Route::get('/subfolder_ajax', 'RelayoutController@subfolder_ajax')->name('relayout.subfolder_ajax');
                Route::get('/list', 'RelayoutController@list')->name('relayout.list');
                Route::get('/list_ajax', 'RelayoutController@list_ajax')->name('relayout.list_ajax');   
                Route::post('/approve_form', 'RelayoutController@approve_form')->name('relayout.approve_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'RelayoutController@manager_approval')->name('relayout.manager_approval');
                    Route::get('/manager_approval_ajax', 'RelayoutController@manager_approval_ajax')->name('relayout.manager_approval_ajax');
                    Route::post('/manager_approve', 'RelayoutController@manager_approve')->name('relayout.manager_approve');
                    Route::get('/manager_approved', 'RelayoutController@manager_approved')->name('relayout.manager_approved');
                    Route::get('/manager_approved_ajax', 'RelayoutController@manager_approved_ajax')->name('relayout.manager_approved_ajax');                
                });
                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/it_approval', 'RelayoutController@it_approval')->name('relayout.it_approval');
                    Route::get('/it_approval_ajax', 'RelayoutController@it_approval_ajax')->name('relayout.it_approval_ajax');
                    Route::post('/it_approve', 'RelayoutController@it_approve')->name('relayout.it_approve');
                    Route::get('/it_approved', 'RelayoutController@it_approved')->name('relayout.it_approved');
                    Route::get('/it_approved_ajax', 'RelayoutController@it_approved_ajax')->name('relayout.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'can_dept:ITD']], function () {
                    Route::get('/it_mgr_approval', 'RelayoutController@it_mgr_approval')->name('relayout.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'RelayoutController@it_mgr_approval_ajax')->name('relayout.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'RelayoutController@it_mgr_approve')->name('relayout.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'RelayoutController@it_mgr_approved')->name('relayout.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'RelayoutController@it_mgr_approved_ajax')->name('relayout.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['can_dept:ITD']], function () {
                    Route::get('/execution', 'RelayoutController@execution')->name('relayout.execution');
                    Route::get('/execution_ajax', 'RelayoutController@execution_ajax')->name('relayout.execution_ajax');
                    Route::post('/execution_approve', 'RelayoutController@execution_approve')->name('relayout.execution_approve');
                    Route::get('/finished', 'RelayoutController@finished')->name('relayout.finished');
                    Route::get('/finished_ajax', 'RelayoutController@finished_ajax')->name('relayout.finished_ajax');
                });
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