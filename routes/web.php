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

Route::group(['namespace' => 'Website', 'as' => 'website.'], function () {
    Route::get('login', 'AuthController@showLoginPage')->name('auth.login');
    Route::post('login', 'AuthController@authenticate')->name('auth.authenticate');
    Route::get('register', 'RegisterController@showRegisterForm')->name('auth.register');
    Route::post('register', 'RegisterController@register')->name('auth.create');
    Route::get('logout', 'AuthController@logout')->name('auth.logout');

    Route::get('/', 'HomeController@type')->name('type');

    Route::middleware('auth.web')->group(function () {
        Route::group(['middleware' => ['permission:apps_fiola']], function () {
            Route::get('/home_ajax', 'HomeController@home_ajax')->name('home_ajax');
            Route::get('/home', 'HomeController@index')->name('home');
            Route::get('/get_approval_count', 'AppHelperController@getApprovalCount')->name('get_approval_count');
            Route::get('/get_rating', 'HomeController@get_rating')->name('get_rating');
            Route::get('/metrics', 'HomeController@metrics')->name('metrics');

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
                    Route::get('/finished', 'ProjectController@finished')->name('project.finished');
                    Route::get('/finished_ajax', 'ProjectController@finished_ajax')->name('project.finished_ajax');
                    Route::get('/reschedule_notifications', 'ProjectController@reschedule_notifications')->name('project.reschedule_notifications');
                    Route::get('/reschedule_notifications_ajax', 'ProjectController@reschedule_notifications_ajax')->name('project.reschedule_notifications_ajax');
                    Route::post('/target_respond', 'ProjectController@target_respond')->name('project.target_respond');
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
