<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Website\ProjectTimelineController;
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
Route::get('/approved-project', 'Website\ApprovedProjectController@index')
    ->name('website.approved_project.index');

Route::get('/approved-project/create', 'Website\ApprovedProjectController@create')
    ->name('website.approved_project.create');

Route::post('/approved-project', 'Website\ApprovedProjectController@store')
    ->name('website.approved_project.store');

Route::get('/approved-project/{id}/edit', 'Website\ApprovedProjectController@edit')
    ->name('website.approved_project.edit');

Route::get('/approved-project/project/{projectId}/edit', 'Website\ApprovedProjectController@editProject')
    ->name('website.approved_project.edit_project');

Route::put('/approved-project/{id}', 'Website\ApprovedProjectController@update')
    ->name('website.approved_project.update');

Route::delete('/approved-project/{id}', 'Website\ApprovedProjectController@destroy')
    ->name('website.approved_project.destroy');

Route::delete('/approved-project/project/{id}', 'Website\ApprovedProjectController@destroyProject')
    ->name('website.approved_project.destroy_project');

Route::middleware(['auth'])->group(function () {
    Route::get('/approved-project', 'Website\ApprovedProjectController@index')
        ->name('website.approved_project.index');

    Route::get('/approved-project/create', 'Website\ApprovedProjectController@create')
        ->name('website.approved_project.create');

    Route::post('/approved-project', 'Website\ApprovedProjectController@store')
        ->name('website.approved_project.store');

    Route::get('/approved-project/{id}/edit', 'Website\ApprovedProjectController@edit')
        ->name('website.approved_project.edit');

    Route::put('/approved-project/{id}', 'Website\ApprovedProjectController@update')
        ->name('website.approved_project.update');

    Route::delete('/approved-project/{id}', 'Website\ApprovedProjectController@destroy')
        ->name('website.approved_project.destroy');

    Route::delete('/approved-project/project/{id}', 'Website\ApprovedProjectController@destroyProject')
        ->name('website.approved_project.destroy_project');
});

Route::middleware(['auth'])->group(function () {
    Route::get('/approved-project', 'Website\ApprovedProjectController@index')
        ->name('website.approved_project.index');

    Route::get('/approved-project/create', 'Website\ApprovedProjectController@create')
        ->name('website.approved_project.create');

    Route::post('/approved-project', 'Website\ApprovedProjectController@store')
        ->name('website.approved_project.store');

    Route::get('/approved-project/{id}/edit', 'Website\ApprovedProjectController@edit')
        ->name('website.approved_project.edit');

    Route::put('/approved-project/{id}', 'Website\ApprovedProjectController@update')
        ->name('website.approved_project.update');

    Route::delete('/approved-project/{id}', 'Website\ApprovedProjectController@destroy')
        ->name('website.approved_project.destroy');
});

Route::group(['namespace' => 'Website', 'as' => 'website.'], function () {
    Route::get('login', 'AuthController@showLoginPage')->name('auth.login');
    Route::post('login', 'AuthController@authenticate')->name('auth.authenticate');
    Route::get('register', 'RegisterController@showRegisterForm')->name('auth.register');
    Route::post('register', 'RegisterController@register')->name('auth.create');
    Route::get('logout', 'AuthController@logout')->name('auth.logout');

    Route::get('alert', 'AlertController@alert')->name('alert');
    Route::get('alert_view', 'AlertController@alert_view')->name('alert_view');
    Route::get('/email_manager', 'ReminderController@email_manager')->name('reminder.email_manager');

    Route::get('/update_status', 'SupportController@update_status')->name('support.update_status');
    Route::post('/recap', 'TicketController@recap');

    Route::get('/', 'HomeController@type')->name('type');
    // TICKET
    Route::group(['prefix' => 'ticket'], function () {
        Route::get('/list', 'TicketController@list')->name('ticket.list');
        Route::get('/list_ajax', 'TicketController@list_ajax')->name('ticket.list_ajax');
        Route::get('/create', 'TicketController@create')->name('ticket.create');
        Route::post('/store', 'TicketController@store')->name('ticket.store');
        Route::get('/review/{id}', 'TicketController@review')->name('ticket.review');
        Route::post('/rate/{id}', 'TicketController@rate')->name('ticket.rate');
        Route::get('/reminder', 'TicketController@reminder')->name('ticket.reminder');

        Route::group(['middleware' => ['auth.web']], function () {
            Route::get('/it_approval', 'TicketController@it_approval')->name('ticket.it_approval');
            Route::get('/it_approval_ajax', 'TicketController@it_approval_ajax')->name('ticket.it_approval_ajax');
            Route::post('/it_approve', 'TicketController@it_approve')->name('ticket.it_approve');
            Route::get('/it_approved', 'TicketController@it_approved')->name('ticket.it_approved');
            Route::get('/it_approved_ajax', 'TicketController@it_approved_ajax')->name('ticket.it_approved_ajax');
        });
    });

    Route::middleware('auth.web')->group(function () {
        Route::group(['middleware' => ['permission:apps_fiola']], function () {
            // Route::get('/', 'HomeController@index')->name('home');
            Route::get('/home_ajax', 'HomeController@home_ajax')->name('home_ajax');
            Route::get('/home', 'HomeController@index')->name('home');
            Route::get('/mail', function () {
                \Illuminate\Support\Facades\Mail::send(new \App\Mail\TaskReminder());
                return view('website.pages.home');
            });
            Route::get('/get_approval_count', 'AppHelperController@getApprovalCount')->name('get_approval_count');
            Route::get('/get_rating', 'HomeController@get_rating')->name('get_rating');
            Route::get('/metrics', 'HomeController@metrics')->name('metrics');
            // MASTER //
            Route::group(['prefix' => 'reminder'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'ReminderController@create')->name('reminder.create');
                    Route::post('/store', 'ReminderController@store')->name('reminder.store');
                    Route::post('/edit', 'ReminderController@edit')->name('reminder.edit');
                    Route::post('/destroy', 'ReminderController@destroy')->name('reminder.destroy');
                    Route::get('/list', 'ReminderController@list')->name('reminder.list');
                    Route::get('/list_ajax', 'ReminderController@list_ajax')->name('reminder.list_ajax');
                });
            });

            Route::group(['prefix' => 'support'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'SupportController@create')->name('support.create');
                    Route::post('/store', 'SupportController@store')->name('support.store');
                    Route::post('/status', 'SupportController@status')->name('support.status');
                    Route::get('/edit/{id}', 'SupportController@edit')->name('support.edit');
                    Route::post('/update/{id}', 'SupportController@update')->name('support.update');
                    Route::post('/destroy', 'SupportController@destroy')->name('support.destroy');
                    Route::get('/list', 'SupportController@list')->name('support.list');
                    Route::get('/list_ajax', 'SupportController@list_ajax')->name('support.list_ajax');
                });
            });

            Route::group(['prefix' => 'alert'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'AlertController@create')->name('alert.create');
                    Route::post('/store', 'AlertController@store')->name('alert.store');
                    Route::get('/edit/{id}', 'AlertController@edit')->name('alert.edit');
                    Route::post('/update/{id}', 'AlertController@update')->name('alert.update');
                    Route::post('/destroy', 'AlertController@destroy')->name('alert.destroy');
                    Route::get('/list', 'AlertController@list')->name('alert.list');
                    Route::get('/list_ajax', 'AlertController@list_ajax')->name('alert.list_ajax');
                });
            });

            Route::group(['prefix' => 'app'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::post('/store', 'AppController@store')->name('app.store');
                    Route::post('/update', 'AppController@update')->name('app.update');
                    Route::post('/destroy', 'AppController@destroy')->name('app.destroy');
                    Route::get('/list', 'AppController@list')->name('app.list');
                    Route::get('/list_ajax', 'AppController@list_ajax')->name('app.list_ajax');
                });
            });

            Route::group(['prefix' => 'device'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'DeviceController@create')->name('device.create');
                    Route::post('/store', 'DeviceController@store')->name('device.store');
                    Route::get('/edit/{id}', 'DeviceController@edit')->name('device.edit');
                    Route::post('/update/{id}', 'DeviceController@update')->name('device.update');
                    Route::post('/destroy', 'DeviceController@destroy')->name('device.destroy');
                    Route::get('/list', 'DeviceController@list')->name('device.list');
                    Route::get('/list_ajax', 'DeviceController@list_ajax')->name('device.list_ajax');
                });
            });

            Route::group(['prefix' => 'department'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'DepartmentController@create')->name('department.create');
                    Route::post('/store', 'DepartmentController@store')->name('department.store');
                    Route::post('/edit', 'DepartmentController@edit')->name('department.edit');
                    Route::post('/destroy', 'DepartmentController@destroy')->name('department.destroy');
                    Route::get('/show_data_department', 'DepartmentController@show_data_department')->name('department.show_data_department');
                    Route::get('/show_data_department_ajax', 'DepartmentController@show_data_department_ajax')->name('department.show_data_department_ajax');
                });
            });

            Route::group(['prefix' => 'folder'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'FolderController@create')->name('folder.create');
                    Route::post('/store', 'FolderController@store')->name('folder.store');
                    Route::get('/edit/{id}', 'FolderController@edit')->name('folder.edit');
                    Route::post('/update/{id}', 'FolderController@update')->name('folder.update');
                    Route::post('/destroy', 'FolderController@destroy')->name('folder.destroy');
                    Route::get('/list', 'FolderController@list')->name('folder.list');
                    Route::get('/list_ajax', 'FolderController@list_ajax')->name('folder.list_ajax');
                });
            });

            Route::group(['prefix' => 'subfolder'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'SubFolderController@create')->name('subfolder.create');
                    Route::post('/store', 'SubFolderController@store')->name('subfolder.store');
                    Route::get('/edit/{id}', 'SubFolderController@edit')->name('subfolder.edit');
                    Route::post('/update/{id}', 'SubFolderController@update')->name('subfolder.update');
                    Route::post('/destroy', 'SubFolderController@destroy')->name('subfolder.destroy');
                    Route::get('/list', 'SubFolderController@list')->name('subfolder.list');
                    Route::get('/list_ajax', 'SubFolderController@list_ajax')->name('subfolder.list_ajax');
                    Route::get('/export', 'SubFolderController@export')->name('subfolder.export');
                });
            });

            Route::group(['prefix' => 'guide'], function () {
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'GuideController@create')->name('guide.create');
                    Route::post('/store', 'GuideController@store')->name('guide.store');
                    Route::get('/edit/{uuid}', 'GuideController@edit')->name('guide.edit');
                    Route::post('/update/{uuid}', 'GuideController@update')->name('guide.update');
                    Route::post('/destroy', 'GuideController@destroy')->name('guide.destroy');
                    Route::get('/list', 'GuideController@list')->name('guide.list');
                    Route::get('/list_ajax', 'GuideController@list_ajax')->name('guide.list_ajax');
                });
            });

            Route::group(['prefix' => 'user'], function () {
                Route::get('/edit', 'UserController@edit')->name('user.edit');
                Route::put('/update', 'UserController@update')->name('user.update');

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/create', 'UserController@create')->name('user.create');
                    Route::post('/store', 'UserController@store')->name('user.store');
                    Route::post('/destroy', 'UserController@destroy')->name('user.destroy');
                    Route::get('/list', 'UserController@list')->name('user.list');
                    Route::get('/list_ajax', 'UserController@list_ajax')->name('user.list_ajax');
                });
            });


            // FORM ACCOUNT //
            Route::group(['prefix' => 'account'], function () {
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'AccountController@it_approval')->name('account.it_approval');
                    Route::get('/it_approval_ajax', 'AccountController@it_approval_ajax')->name('account.it_approval_ajax');
                    Route::post('/it_approve', 'AccountController@it_approve')->name('account.it_approve');
                    Route::get('/it_approved', 'AccountController@it_approved')->name('account.it_approved');
                    Route::get('/it_approved_ajax', 'AccountController@it_approved_ajax')->name('account.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'AccountController@it_mgr_approval')->name('account.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'AccountController@it_mgr_approval_ajax')->name('account.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'AccountController@it_mgr_approve')->name('account.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'AccountController@it_mgr_approved')->name('account.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'AccountController@it_mgr_approved_ajax')->name('account.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'AccountController@execution')->name('account.execution');
                    Route::get('/execution_ajax', 'AccountController@execution_ajax')->name('account.execution_ajax');
                    Route::post('/execution_approve', 'AccountController@execution_approve')->name('account.execution_approve');
                    Route::get('/finished', 'AccountController@finished')->name('account.finished');
                    Route::get('/finished_ajax', 'AccountController@finished_ajax')->name('account.finished_ajax');
                });
            });
            // FORM FOLDER ACCESS //
            Route::group(['prefix' => 'folder-access'], function () {
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'FolderAccessController@it_approval')->name('folder-access.it_approval');
                    Route::get('/it_approval_ajax', 'FolderAccessController@it_approval_ajax')->name('folder-access.it_approval_ajax');
                    Route::post('/it_approve', 'FolderAccessController@it_approve')->name('folder-access.it_approve');
                    Route::get('/it_approved', 'FolderAccessController@it_approved')->name('folder-access.it_approved');
                    Route::get('/it_approved_ajax', 'FolderAccessController@it_approved_ajax')->name('folder-access.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'FolderAccessController@it_mgr_approval')->name('folder-access.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'FolderAccessController@it_mgr_approval_ajax')->name('folder-access.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'FolderAccessController@it_mgr_approve')->name('folder-access.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'FolderAccessController@it_mgr_approved')->name('folder-access.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'FolderAccessController@it_mgr_approved_ajax')->name('folder-access.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'FolderAccessController@execution')->name('folder-access.execution');
                    Route::get('/execution_ajax', 'FolderAccessController@execution_ajax')->name('folder-access.execution_ajax');
                    Route::post('/execution_approve', 'FolderAccessController@execution_approve')->name('folder-access.execution_approve');
                    Route::get('/finished', 'FolderAccessController@finished')->name('folder-access.finished');
                    Route::get('/finished_ajax', 'FolderAccessController@finished_ajax')->name('folder-access.finished_ajax');
                });
            });
            // FORM NEW FOLDER //
            Route::group(['prefix' => 'new-folder'], function () {
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'NewFolderController@it_approval')->name('new-folder.it_approval');
                    Route::get('/it_approval_ajax', 'NewFolderController@it_approval_ajax')->name('new-folder.it_approval_ajax');
                    Route::post('/it_approve', 'NewFolderController@it_approve')->name('new-folder.it_approve');
                    Route::get('/it_approved', 'NewFolderController@it_approved')->name('new-folder.it_approved');
                    Route::get('/it_approved_ajax', 'NewFolderController@it_approved_ajax')->name('new-folder.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'NewFolderController@it_mgr_approval')->name('new-folder.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'NewFolderController@it_mgr_approval_ajax')->name('new-folder.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'NewFolderController@it_mgr_approve')->name('new-folder.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'NewFolderController@it_mgr_approved')->name('new-folder.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'NewFolderController@it_mgr_approved_ajax')->name('new-folder.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'NewFolderController@execution')->name('new-folder.execution');
                    Route::get('/execution_ajax', 'NewFolderController@execution_ajax')->name('new-folder.execution_ajax');
                    Route::post('/execution_approve', 'NewFolderController@execution_approve')->name('new-folder.execution_approve');
                    Route::get('/finished', 'NewFolderController@finished')->name('new-folder.finished');
                    Route::get('/finished_ajax', 'NewFolderController@finished_ajax')->name('new-folder.finished_ajax');
                });
            });
            // FORM SOFTWARE //
            Route::group(['prefix' => 'software'], function () {
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'SoftwareController@it_approval')->name('software.it_approval');
                    Route::get('/it_approval_ajax', 'SoftwareController@it_approval_ajax')->name('software.it_approval_ajax');
                    Route::post('/it_approve', 'SoftwareController@it_approve')->name('software.it_approve');
                    Route::get('/it_approved', 'SoftwareController@it_approved')->name('software.it_approved');
                    Route::get('/it_approved_ajax', 'SoftwareController@it_approved_ajax')->name('software.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'SoftwareController@it_mgr_approval')->name('software.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'SoftwareController@it_mgr_approval_ajax')->name('software.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'SoftwareController@it_mgr_approve')->name('software.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'SoftwareController@it_mgr_approved')->name('software.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'SoftwareController@it_mgr_approved_ajax')->name('software.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'SoftwareController@execution')->name('software.execution');
                    Route::get('/execution_ajax', 'SoftwareController@execution_ajax')->name('software.execution_ajax');
                    Route::post('/execution_approve', 'SoftwareController@execution_approve')->name('software.execution_approve');
                    Route::get('/finished', 'SoftwareController@finished')->name('software.finished');
                    Route::get('/finished_ajax', 'SoftwareController@finished_ajax')->name('software.finished_ajax');
                });
            });
            // FORM HARDWARE //
            Route::group(['prefix' => 'hardware'], function () {
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'HardwareController@it_approval')->name('hardware.it_approval');
                    Route::get('/it_approval_ajax', 'HardwareController@it_approval_ajax')->name('hardware.it_approval_ajax');
                    Route::post('/it_approve', 'HardwareController@it_approve')->name('hardware.it_approve');
                    Route::get('/it_approved', 'HardwareController@it_approved')->name('hardware.it_approved');
                    Route::get('/it_approved_ajax', 'HardwareController@it_approved_ajax')->name('hardware.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'HardwareController@it_mgr_approval')->name('hardware.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'HardwareController@it_mgr_approval_ajax')->name('hardware.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'HardwareController@it_mgr_approve')->name('hardware.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'HardwareController@it_mgr_approved')->name('hardware.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'HardwareController@it_mgr_approved_ajax')->name('hardware.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'HardwareController@execution')->name('hardware.execution');
                    Route::get('/execution_ajax', 'HardwareController@execution_ajax')->name('hardware.execution_ajax');
                    Route::post('/execution_approve', 'HardwareController@execution_approve')->name('hardware.execution_approve');
                    Route::get('/finished', 'HardwareController@finished')->name('hardware.finished');
                    Route::get('/finished_ajax', 'HardwareController@finished_ajax')->name('hardware.finished_ajax');
                });
            });
            // FORM VPN //
            Route::group(['prefix' => 'vpn'], function () {
                Route::get('/create', 'VpnController@create')->name('vpn.create');
                Route::post('/store', 'VpnController@store')->name('vpn.store');
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'VpnController@it_approval')->name('vpn.it_approval');
                    Route::get('/it_approval_ajax', 'VpnController@it_approval_ajax')->name('vpn.it_approval_ajax');
                    Route::post('/it_approve', 'VpnController@it_approve')->name('vpn.it_approve');
                    Route::get('/it_approved', 'VpnController@it_approved')->name('vpn.it_approved');
                    Route::get('/it_approved_ajax', 'VpnController@it_approved_ajax')->name('vpn.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'VpnController@it_mgr_approval')->name('vpn.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'VpnController@it_mgr_approval_ajax')->name('vpn.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'VpnController@it_mgr_approve')->name('vpn.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'VpnController@it_mgr_approved')->name('vpn.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'VpnController@it_mgr_approved_ajax')->name('vpn.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'VpnController@execution')->name('vpn.execution');
                    Route::get('/execution_ajax', 'VpnController@execution_ajax')->name('vpn.execution_ajax');
                    Route::post('/execution_approve', 'VpnController@execution_approve')->name('vpn.execution_approve');
                    Route::get('/finished', 'VpnController@finished')->name('vpn.finished');
                    Route::get('/finished_ajax', 'VpnController@finished_ajax')->name('vpn.finished_ajax');
                });
            });
            // FORM NETWORK //
            Route::group(['prefix' => 'network'], function () {
                Route::get('/create', 'NetworkController@create')->name('network.create');
                Route::post('/store', 'NetworkController@store')->name('network.store');
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
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'NetworkController@it_approval')->name('network.it_approval');
                    Route::get('/it_approval_ajax', 'NetworkController@it_approval_ajax')->name('network.it_approval_ajax');
                    Route::post('/it_approve', 'NetworkController@it_approve')->name('network.it_approve');
                    Route::get('/it_approved', 'NetworkController@it_approved')->name('network.it_approved');
                    Route::get('/it_approved_ajax', 'NetworkController@it_approved_ajax')->name('network.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'NetworkController@it_mgr_approval')->name('network.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'NetworkController@it_mgr_approval_ajax')->name('network.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'NetworkController@it_mgr_approve')->name('network.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'NetworkController@it_mgr_approved')->name('network.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'NetworkController@it_mgr_approved_ajax')->name('network.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'NetworkController@execution')->name('network.execution');
                    Route::get('/execution_ajax', 'NetworkController@execution_ajax')->name('network.execution_ajax');
                    Route::post('/execution_approve', 'NetworkController@execution_approve')->name('network.execution_approve');
                    Route::get('/finished', 'NetworkController@finished')->name('network.finished');
                    Route::get('/finished_ajax', 'NetworkController@finished_ajax')->name('network.finished_ajax');
                });
            });
            // FORM PROJECT //
            Route::group(['prefix' => 'project'], function () {
                Route::get('/create', 'ProjectController@create')->name('project.create');
                Route::post('/store', 'ProjectController@store')->name('project.store');
                Route::get('/list', 'ProjectController@list')->name('project.list');
                Route::get('/list_ajax', 'ProjectController@list_ajax')->name('project.list_ajax');
                Route::get('/check_month_limit', 'ProjectController@check_month_limit')->name('project.check_month_limit');
                Route::post('/approve_form', 'ProjectController@approve_form')->name('project.approve_form');
                Route::post('/delete_form', 'ProjectController@delete_form')->name('project.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'ProjectController@manager_approval')->name('project.manager_approval');
                    Route::get('/manager_approval_ajax', 'ProjectController@manager_approval_ajax')->name('project.manager_approval_ajax');
                    Route::post('/manager_approve', 'ProjectController@manager_approve')->name('project.manager_approve');
                    Route::get('/manager_approved', 'ProjectController@manager_approved')->name('project.manager_approved');
                    Route::get('/manager_approved_ajax', 'ProjectController@manager_approved_ajax')->name('project.manager_approved_ajax');
                });
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/dir_approval', 'ProjectController@dir_approval')->name('project.dir_approval');
                    Route::get('/dir_approval_ajax', 'ProjectController@dir_approval_ajax')->name('project.dir_approval_ajax');
                    Route::post('/dir_approve', 'ProjectController@dir_approve')->name('project.dir_approve');
                    Route::get('/dir_approved', 'ProjectController@dir_approved')->name('project.dir_approved');
                    Route::get('/dir_approved_ajax', 'ProjectController@dir_approved_ajax')->name('project.dir_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/reschedule_notifications', 'ProjectController@reschedule_notifications')->name('project.reschedule_notifications');
                    Route::get('/reschedule_notifications_ajax', 'ProjectController@reschedule_notifications_ajax')->name('project.reschedule_notifications_ajax');
                    Route::post('/target_respond', 'ProjectController@target_respond')->name('project.target_respond');
                });
            });
            // FORM FITUR //
            Route::group(['prefix' => 'fitur'], function () {
                Route::get('/create', 'FiturController@create')->name('fitur.create');
                Route::post('/store', 'FiturController@store')->name('fitur.store');
                Route::get('/list', 'FiturController@list')->name('fitur.list');
                Route::get('/list_ajax', 'FiturController@list_ajax')->name('fitur.list_ajax');
                Route::post('/approve_form', 'FiturController@approve_form')->name('fitur.approve_form');
                Route::post('/delete_form', 'FiturController@delete_form')->name('fitur.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'FiturController@manager_approval')->name('fitur.manager_approval');
                    Route::get('/manager_approval_ajax', 'FiturController@manager_approval_ajax')->name('fitur.manager_approval_ajax');
                    Route::post('/manager_approve', 'FiturController@manager_approve')->name('fitur.manager_approve');
                    Route::get('/manager_approved', 'FiturController@manager_approved')->name('fitur.manager_approved');
                    Route::get('/manager_approved_ajax', 'FiturController@manager_approved_ajax')->name('fitur.manager_approved_ajax');
                });
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'FiturController@it_approval')->name('fitur.it_approval');
                    Route::get('/it_approval_ajax', 'FiturController@it_approval_ajax')->name('fitur.it_approval_ajax');
                    Route::post('/it_approve', 'FiturController@it_approve')->name('fitur.it_approve');
                    Route::get('/it_approved', 'FiturController@it_approved')->name('fitur.it_approved');
                    Route::get('/it_approved_ajax', 'FiturController@it_approved_ajax')->name('fitur.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'FiturController@it_mgr_approval')->name('fitur.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'FiturController@it_mgr_approval_ajax')->name('fitur.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'FiturController@it_mgr_approve')->name('fitur.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'FiturController@it_mgr_approved')->name('fitur.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'FiturController@it_mgr_approved_ajax')->name('fitur.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'FiturController@execution')->name('fitur.execution');
                    Route::get('/execution_ajax', 'FiturController@execution_ajax')->name('fitur.execution_ajax');
                    Route::post('/execution_approve', 'FiturController@execution_approve')->name('fitur.execution_approve');
                    Route::get('/finished', 'FiturController@finished')->name('fitur.finished');
                    Route::get('/finished_ajax', 'FiturController@finished_ajax')->name('fitur.finished_ajax');
                });
            });
            // FORM RELAYOUT //
            Route::group(['prefix' => 'relayout'], function () {
                Route::get('/create', 'RelayoutController@create')->name('relayout.create');
                Route::post('/store', 'RelayoutController@store')->name('relayout.store');
                Route::get('/list', 'RelayoutController@list')->name('relayout.list');
                Route::get('/list_ajax', 'RelayoutController@list_ajax')->name('relayout.list_ajax');
                Route::post('/approve_form', 'RelayoutController@approve_form')->name('relayout.approve_form');
                Route::post('/delete_form', 'RelayoutController@delete_form')->name('relayout.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'RelayoutController@manager_approval')->name('relayout.manager_approval');
                    Route::get('/manager_approval_ajax', 'RelayoutController@manager_approval_ajax')->name('relayout.manager_approval_ajax');
                    Route::post('/manager_approve', 'RelayoutController@manager_approve')->name('relayout.manager_approve');
                    Route::get('/manager_approved', 'RelayoutController@manager_approved')->name('relayout.manager_approved');
                    Route::get('/manager_approved_ajax', 'RelayoutController@manager_approved_ajax')->name('relayout.manager_approved_ajax');
                });
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'RelayoutController@it_approval')->name('relayout.it_approval');
                    Route::get('/it_approval_ajax', 'RelayoutController@it_approval_ajax')->name('relayout.it_approval_ajax');
                    Route::post('/it_approve', 'RelayoutController@it_approve')->name('relayout.it_approve');
                    Route::get('/it_approved', 'RelayoutController@it_approved')->name('relayout.it_approved');
                    Route::get('/it_approved_ajax', 'RelayoutController@it_approved_ajax')->name('relayout.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'RelayoutController@it_mgr_approval')->name('relayout.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'RelayoutController@it_mgr_approval_ajax')->name('relayout.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'RelayoutController@it_mgr_approve')->name('relayout.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'RelayoutController@it_mgr_approved')->name('relayout.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'RelayoutController@it_mgr_approved_ajax')->name('relayout.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'RelayoutController@execution')->name('relayout.execution');
                    Route::get('/execution_ajax', 'RelayoutController@execution_ajax')->name('relayout.execution_ajax');
                    Route::post('/execution_approve', 'RelayoutController@execution_approve')->name('relayout.execution_approve');
                    Route::get('/finished', 'RelayoutController@finished')->name('relayout.finished');
                    Route::get('/finished_ajax', 'RelayoutController@finished_ajax')->name('relayout.finished_ajax');
                });
            });
            // FORM AKSES SISTEM //
            Route::group(['prefix' => 'akses_sistem'], function () {
                Route::get('/create', 'AksesSistemController@create')->name('akses_sistem.create');
                Route::post('/store', 'AksesSistemController@store')->name('akses_sistem.store');
                Route::get('/list', 'AksesSistemController@list')->name('akses_sistem.list');
                Route::get('/list_ajax', 'AksesSistemController@list_ajax')->name('akses_sistem.list_ajax');
                Route::post('/approve_form', 'AksesSistemController@approve_form')->name('akses_sistem.approve_form');
                Route::post('/delete_form', 'AksesSistemController@delete_form')->name('akses_sistem.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'AksesSistemController@manager_approval')->name('akses_sistem.manager_approval');
                    Route::get('/manager_approval_ajax', 'AksesSistemController@manager_approval_ajax')->name('akses_sistem.manager_approval_ajax');
                    Route::post('/manager_approve', 'AksesSistemController@manager_approve')->name('akses_sistem.manager_approve');
                    Route::get('/manager_approved', 'AksesSistemController@manager_approved')->name('akses_sistem.manager_approved');
                    Route::get('/manager_approved_ajax', 'AksesSistemController@manager_approved_ajax')->name('akses_sistem.manager_approved_ajax');
                });
                Route::group(['middleware' => ['can:approve_gm']], function () {
                    Route::get('/gm_approval', 'AksesSistemController@gm_approval')->name('akses_sistem.gm_approval');
                    Route::get('/gm_approval_ajax', 'AksesSistemController@gm_approval_ajax')->name('akses_sistem.gm_approval_ajax');
                    Route::post('/gm_approve', 'AksesSistemController@gm_approve')->name('akses_sistem.gm_approve');
                    Route::get('/gm_approved', 'AksesSistemController@gm_approved')->name('akses_sistem.gm_approved');
                    Route::get('/gm_approved_ajax', 'AksesSistemController@gm_approved_ajax')->name('akses_sistem.gm_approved_ajax');
                });
                Route::group(['middleware' => ['can:approve_dir']], function () {
                    Route::get('/dir_approval', 'AksesSistemController@dir_approval')->name('akses_sistem.dir_approval');
                    Route::get('/dir_approval_ajax', 'AksesSistemController@dir_approval_ajax')->name('akses_sistem.dir_approval_ajax');
                    Route::post('/dir_approve', 'AksesSistemController@dir_approve')->name('akses_sistem.dir_approve');
                    Route::get('/dir_approved', 'AksesSistemController@dir_approved')->name('akses_sistem.dir_approved');
                    Route::get('/dir_approved_ajax', 'AksesSistemController@dir_approved_ajax')->name('akses_sistem.dir_approved_ajax');
                });
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'AksesSistemController@it_approval')->name('akses_sistem.it_approval');
                    Route::get('/it_approval_ajax', 'AksesSistemController@it_approval_ajax')->name('akses_sistem.it_approval_ajax');
                    Route::post('/it_approve', 'AksesSistemController@it_approve')->name('akses_sistem.it_approve');
                    Route::get('/it_approved', 'AksesSistemController@it_approved')->name('akses_sistem.it_approved');
                    Route::get('/it_approved_ajax', 'AksesSistemController@it_approved_ajax')->name('akses_sistem.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'AksesSistemController@it_mgr_approval')->name('akses_sistem.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'AksesSistemController@it_mgr_approval_ajax')->name('akses_sistem.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'AksesSistemController@it_mgr_approve')->name('akses_sistem.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'AksesSistemController@it_mgr_approved')->name('akses_sistem.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'AksesSistemController@it_mgr_approved_ajax')->name('akses_sistem.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'AksesSistemController@execution')->name('akses_sistem.execution');
                    Route::get('/execution_ajax', 'AksesSistemController@execution_ajax')->name('akses_sistem.execution_ajax');
                    Route::post('/execution_approve', 'AksesSistemController@execution_approve')->name('akses_sistem.execution_approve');
                    Route::get('/finished', 'AksesSistemController@finished')->name('akses_sistem.finished');
                    Route::get('/finished_ajax', 'AksesSistemController@finished_ajax')->name('akses_sistem.finished_ajax');
                });
            });
            // FORM INCIDENT REPORT //
            Route::group(['prefix' => 'incident_report'], function () {
                Route::get('/create', 'IncidentReportController@create')->name('incident_report.create');
                Route::post('/store', 'IncidentReportController@store')->name('incident_report.store');
                Route::get('/subfolder_ajax', 'IncidentReportController@subfolder_ajax')->name('incident_report.subfolder_ajax');
                Route::get('/list', 'IncidentReportController@list')->name('incident_report.list');
                Route::get('/list_ajax', 'IncidentReportController@list_ajax')->name('incident_report.list_ajax');
                Route::post('/approve_form', 'IncidentReportController@approve_form')->name('incident_report.approve_form');
                Route::post('/delete_form', 'IncidentReportController@delete_form')->name('incident_report.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'IncidentReportController@manager_approval')->name('incident_report.manager_approval');
                    Route::get('/manager_approval_ajax', 'IncidentReportController@manager_approval_ajax')->name('incident_report.manager_approval_ajax');
                    Route::post('/manager_approve', 'IncidentReportController@manager_approve')->name('incident_report.manager_approve');
                    Route::get('/manager_approved', 'IncidentReportController@manager_approved')->name('incident_report.manager_approved');
                    Route::get('/manager_approved_ajax', 'IncidentReportController@manager_approved_ajax')->name('incident_report.manager_approved_ajax');
                });
                Route::group(['middleware' => ['can:approve_gm']], function () {
                    Route::get('/gm_approval', 'IncidentReportController@gm_approval')->name('incident_report.gm_approval');
                    Route::get('/gm_approval_ajax', 'IncidentReportController@gm_approval_ajax')->name('incident_report.gm_approval_ajax');
                    Route::post('/gm_approve', 'IncidentReportController@gm_approve')->name('incident_report.gm_approve');
                    Route::get('/gm_approved', 'IncidentReportController@gm_approved')->name('incident_report.gm_approved');
                    Route::get('/gm_approved_ajax', 'IncidentReportController@gm_approved_ajax')->name('incident_report.gm_approved_ajax');
                });
                Route::group(['middleware' => ['can:approve_dir']], function () {
                    Route::get('/dir_approval', 'IncidentReportController@dir_approval')->name('incident_report.dir_approval');
                    Route::get('/dir_approval_ajax', 'IncidentReportController@dir_approval_ajax')->name('incident_report.dir_approval_ajax');
                    Route::post('/dir_approve', 'IncidentReportController@dir_approve')->name('incident_report.dir_approve');
                    Route::get('/dir_approved', 'IncidentReportController@dir_approved')->name('incident_report.dir_approved');
                    Route::get('/dir_approved_ajax', 'IncidentReportController@dir_approved_ajax')->name('incident_report.dir_approved_ajax');
                });
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'IncidentReportController@it_approval')->name('incident_report.it_approval');
                    Route::get('/it_approval_ajax', 'IncidentReportController@it_approval_ajax')->name('incident_report.it_approval_ajax');
                    Route::post('/it_approve', 'IncidentReportController@it_approve')->name('incident_report.it_approve');
                    Route::get('/it_approved', 'IncidentReportController@it_approved')->name('incident_report.it_approved');
                    Route::get('/it_approved_ajax', 'IncidentReportController@it_approved_ajax')->name('incident_report.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'IncidentReportController@it_mgr_approval')->name('incident_report.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'IncidentReportController@it_mgr_approval_ajax')->name('incident_report.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'IncidentReportController@it_mgr_approve')->name('incident_report.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'IncidentReportController@it_mgr_approved')->name('incident_report.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'IncidentReportController@it_mgr_approved_ajax')->name('incident_report.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'IncidentReportController@execution')->name('incident_report.execution');
                    Route::get('/execution_ajax', 'IncidentReportController@execution_ajax')->name('incident_report.execution_ajax');
                    Route::post('/execution_approve', 'IncidentReportController@execution_approve')->name('incident_report.execution_approve');
                    Route::get('/finished', 'IncidentReportController@finished')->name('incident_report.finished');
                    Route::get('/finished_ajax', 'IncidentReportController@finished_ajax')->name('incident_report.finished_ajax');
                });
            });
            // FORM IZIN //
            Route::group(['prefix' => 'izin'], function () {
                Route::get('/create', 'IzinController@create')->name('izin.create');
                Route::post('/store', 'IzinController@store')->name('izin.store');
                Route::get('/subfolder_ajax', 'IzinController@subfolder_ajax')->name('izin.subfolder_ajax');
                Route::get('/list', 'IzinController@list')->name('izin.list');
                Route::get('/list_ajax', 'IzinController@list_ajax')->name('izin.list_ajax');
                Route::post('/approve_form', 'IzinController@approve_form')->name('izin.approve_form');
                Route::post('/delete_form', 'IzinController@delete_form')->name('izin.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'IzinController@manager_approval')->name('izin.manager_approval');
                    Route::get('/manager_approval_ajax', 'IzinController@manager_approval_ajax')->name('izin.manager_approval_ajax');
                    Route::post('/manager_approve', 'IzinController@manager_approve')->name('izin.manager_approve');
                    Route::get('/manager_approved', 'IzinController@manager_approved')->name('izin.manager_approved');
                    Route::get('/manager_approved_ajax', 'IzinController@manager_approved_ajax')->name('izin.manager_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'IzinController@it_approval')->name('izin.it_approval');
                    Route::get('/it_approval_ajax', 'IzinController@it_approval_ajax')->name('izin.it_approval_ajax');
                    Route::post('/it_approve', 'IzinController@it_approve')->name('izin.it_approve');
                    Route::get('/it_approved', 'IzinController@it_approved')->name('izin.it_approved');
                    Route::get('/it_approved_ajax', 'IzinController@it_approved_ajax')->name('izin.it_approved_ajax');
                });

                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'IzinController@it_mgr_approval')->name('izin.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'IzinController@it_mgr_approval_ajax')->name('izin.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'IzinController@it_mgr_approve')->name('izin.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'IzinController@it_mgr_approved')->name('izin.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'IzinController@it_mgr_approved_ajax')->name('izin.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'IzinController@execution')->name('izin.execution');
                    Route::get('/execution_ajax', 'IzinController@execution_ajax')->name('izin.execution_ajax');
                    Route::post('/execution_approve', 'IzinController@execution_approve')->name('izin.execution_approve');
                    Route::get('/finished', 'IzinController@finished')->name('izin.finished');
                    Route::get('/finished_ajax', 'IzinController@finished_ajax')->name('izin.finished_ajax');
                });
            });
            // FORM CCTV //
            Route::group(['prefix' => 'cctv'], function () {
                Route::get('/create', 'CctvController@create')->name('cctv.create');
                Route::post('/store', 'CctvController@store')->name('cctv.store');
                Route::get('/subfolder_ajax', 'CctvController@subfolder_ajax')->name('cctv.subfolder_ajax');
                Route::get('/list', 'CctvController@list')->name('cctv.list');
                Route::get('/list_ajax', 'CctvController@list_ajax')->name('cctv.list_ajax');
                Route::post('/approve_form', 'CctvController@approve_form')->name('cctv.approve_form');
                Route::post('/delete_form', 'CctvController@delete_form')->name('cctv.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'CctvController@manager_approval')->name('cctv.manager_approval');
                    Route::get('/manager_approval_ajax', 'CctvController@manager_approval_ajax')->name('cctv.manager_approval_ajax');
                    Route::post('/manager_approve', 'CctvController@manager_approve')->name('cctv.manager_approve');
                    Route::get('/manager_approved', 'CctvController@manager_approved')->name('cctv.manager_approved');
                    Route::get('/manager_approved_ajax', 'CctvController@manager_approved_ajax')->name('cctv.manager_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'CctvController@it_approval')->name('cctv.it_approval');
                    Route::get('/it_approval_ajax', 'CctvController@it_approval_ajax')->name('cctv.it_approval_ajax');
                    Route::post('/it_approve', 'CctvController@it_approve')->name('cctv.it_approve');
                    Route::get('/it_approved', 'CctvController@it_approved')->name('cctv.it_approved');
                    Route::get('/it_approved_ajax', 'CctvController@it_approved_ajax')->name('cctv.it_approved_ajax');
                });

                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'CctvController@it_mgr_approval')->name('cctv.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'CctvController@it_mgr_approval_ajax')->name('cctv.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'CctvController@it_mgr_approve')->name('cctv.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'CctvController@it_mgr_approved')->name('cctv.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'CctvController@it_mgr_approved_ajax')->name('cctv.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'CctvController@execution')->name('cctv.execution');
                    Route::get('/execution_ajax', 'CctvController@execution_ajax')->name('cctv.execution_ajax');
                    Route::post('/execution_approve', 'CctvController@execution_approve')->name('cctv.execution_approve');
                    Route::get('/finished', 'CctvController@finished')->name('cctv.finished');
                    Route::get('/finished_ajax', 'CctvController@finished_ajax')->name('cctv.finished_ajax');
                });
            });
            // FORM IT NEEDS //
            Route::group(['prefix' => 'it_needs'], function () {
                Route::get('/create', 'ItNeedsController@create')->name('it_needs.create');
                Route::post('/store', 'ItNeedsController@store')->name('it_needs.store');
                Route::get('/subfolder_ajax', 'ItNeedsController@subfolder_ajax')->name('it_needs.subfolder_ajax');
                Route::get('/list', 'ItNeedsController@list')->name('it_needs.list');
                Route::get('/list_ajax', 'ItNeedsController@list_ajax')->name('it_needs.list_ajax');
                Route::post('/approve_form', 'ItNeedsController@approve_form')->name('it_needs.approve_form');
                Route::post('/delete_form', 'ItNeedsController@delete_form')->name('it_needs.delete_form');

                Route::group(['middleware' => ['can:approve_mgr']], function () {
                    Route::get('/manager_approval', 'ItNeedsController@manager_approval')->name('it_needs.manager_approval');
                    Route::get('/manager_approval_ajax', 'ItNeedsController@manager_approval_ajax')->name('it_needs.manager_approval_ajax');
                    Route::post('/manager_approve', 'ItNeedsController@manager_approve')->name('it_needs.manager_approve');
                    Route::get('/manager_approved', 'ItNeedsController@manager_approved')->name('it_needs.manager_approved');
                    Route::get('/manager_approved_ajax', 'ItNeedsController@manager_approved_ajax')->name('it_needs.manager_approved_ajax');
                });
                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/it_approval', 'ItNeedsController@it_approval')->name('it_needs.it_approval');
                    Route::get('/it_approval_ajax', 'ItNeedsController@it_approval_ajax')->name('it_needs.it_approval_ajax');
                    Route::post('/it_approve', 'ItNeedsController@it_approve')->name('it_needs.it_approve');
                    Route::get('/it_approved', 'ItNeedsController@it_approved')->name('it_needs.it_approved');
                    Route::get('/it_approved_ajax', 'ItNeedsController@it_approved_ajax')->name('it_needs.it_approved_ajax');
                });
                Route::group(['middleware' => ['permission:approve_mgr', 'auth.web']], function () {
                    Route::get('/it_mgr_approval', 'ItNeedsController@it_mgr_approval')->name('it_needs.it_mgr_approval');
                    Route::get('/it_mgr_approval_ajax', 'ItNeedsController@it_mgr_approval_ajax')->name('it_needs.it_mgr_approval_ajax');
                    Route::post('/it_mgr_approve', 'ItNeedsController@it_mgr_approve')->name('it_needs.it_mgr_approve');
                    Route::get('/it_mgr_approved', 'ItNeedsController@it_mgr_approved')->name('it_needs.it_mgr_approved');
                    Route::get('/it_mgr_approved_ajax', 'ItNeedsController@it_mgr_approved_ajax')->name('it_needs.it_mgr_approved_ajax');
                });

                Route::group(['middleware' => ['auth.web']], function () {
                    Route::get('/execution', 'ItNeedsController@execution')->name('it_needs.execution');
                    Route::get('/execution_ajax', 'ItNeedsController@execution_ajax')->name('it_needs.execution_ajax');
                    Route::post('/execution_approve', 'ItNeedsController@execution_approve')->name('it_needs.execution_approve');
                    Route::get('/finished', 'ItNeedsController@finished')->name('it_needs.finished');
                    Route::get('/finished_ajax', 'ItNeedsController@finished_ajax')->name('it_needs.finished_ajax');
                });
            });
        });
    });
});

Route::group(['namespace' => 'Admin', 'as' => 'admin.', 'prefix' => 'admin'], function () {
    Route::get('login', 'AuthController@showLoginPage')->name('auth.login');
    Route::post('login', 'AuthController@authenticate')->name('auth.authenticate');

    Route::middleware('auth.admin')->group(function () {
        Route::get('/', 'HomeController@index')->name('home');
        Route::get('/home', 'HomeController@index')->name('auth.home');
        Route::get('logout', 'AuthController@logout')->name('auth.logout');
    });
});

Route::middleware(['auth'])->group(function () {
    Route::get('/project-timeline', [ProjectTimelineController::class, 'index'])->name('website.project_timeline.index');
    Route::post('/project-timeline/request-join', [ProjectTimelineController::class, 'requestJoin'])->name('website.project_timeline.request_join');
    Route::post('/project-timeline/{id}/approve', [ProjectTimelineController::class, 'approve'])->name('website.project_timeline.approve');
    Route::post('/project-timeline/{id}/reject', [ProjectTimelineController::class, 'reject'])->name('website.project_timeline.reject');
});
