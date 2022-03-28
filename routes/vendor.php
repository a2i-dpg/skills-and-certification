<?php

use App\Http\Controllers\Admin\LocalizationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\UtilityAPI\ModelResourceFetchController;
use Illuminate\Support\Facades\Route;


Route::group(['prefix' => 'admin', 'as' => 'admin.', 'middleware' => ['auth', 'authAdmin']], function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'dashboard'])->name('dashboard');

    Route::post('loc-divisions/datatable', [App\Http\Controllers\GeoLocations\LocDivisionController::class, 'getDatatable'])->name('loc-divisions.datatable');
    Route::post('loc-districts/datatable', [App\Http\Controllers\GeoLocations\LocDistrictController::class, 'getDatatable'])->name('loc-districts.datatable');
    Route::post('loc-upazilas/datatable', [App\Http\Controllers\GeoLocations\LocUpazilaController::class, 'getDatatable'])->name('loc-upazilas.datatable');
    Route::resources([
        'loc-divisions' => App\Http\Controllers\GeoLocations\LocDivisionController::class,
        'loc-districts' => App\Http\Controllers\GeoLocations\LocDistrictController::class,
        'loc-upazilas' => App\Http\Controllers\GeoLocations\LocUpazilaController::class
    ]);

    Route::resource('/users', App\Http\Controllers\Admin\UserController::class);
    Route::get('/users/{user}/trainers', [App\Http\Controllers\Admin\UserController::class, 'trainerList'])->name('users.trainers');
    Route::resource('user-types', App\Http\Controllers\UserTypeController::class)
        ->except(['create', 'store', 'destroy']);

    Route::post('/users/datatable', [App\Http\Controllers\Admin\UserController::class, 'getDatatable'])->name('users.datatable');
    Route::post('/users/{user}/roles', [App\Http\Controllers\Admin\UserController::class, 'userRoleSync'])->name('users.role-sync');
    Route::get('/users/{user}/permissions', [App\Http\Controllers\Admin\UserController::class, 'userPermissionIndex'])->name('users.permissions');
    Route::post('/users/{user}/permissions', [App\Http\Controllers\Admin\UserController::class, 'userPermissionSync'])->name('users.permission-sync');
    Route::get('/check-user-email', [UserController::class, 'checkUserEmail'])->name('check-user-email');

    Route::get('roles/{role}/permissions', [App\Http\Controllers\RoleController::class, 'rolePermissionIndex'])->name('roles.permissions');
    Route::post('roles/{role}/permissions', [App\Http\Controllers\RoleController::class, 'rolePermissionSync'])->name('roles.permission-sync');
    Route::post('roles/datatable', [App\Http\Controllers\RoleController::class, 'getDatatable'])->name('roles.datatable');
    Route::post('permissions/datatable', [App\Http\Controllers\PermissionController::class, 'getDatatable'])->name('permissions.datatable');

    Route::resources([
        'roles' => App\Http\Controllers\RoleController::class,
        'permissions' => App\Http\Controllers\PermissionController::class
    ]);

    Route::get('row-status', [App\Http\Controllers\RowStatusController::class, 'index'])->name('row-status');
    Route::post('/trainers/datatable', [App\Http\Controllers\Admin\UserController::class, 'getTrainersDatatable'])->name('trainers.datatable');
    Route::get('/trainer/{user}/additional-info', [App\Http\Controllers\Admin\TrainerController::class, 'index'])->name('trainers.additional-info');

    Route::post('/trainer-info', [App\Http\Controllers\Admin\TrainerController::class, 'store'])->name('trainer-info.store');

    Route::post('trainer-daily-routine/datatable', [\App\Http\Controllers\Admin\DashboardController::class, 'getTrainerDailyRoutine'])
        ->name('trainer.daily-routine.datatable');

    Route::post('trainer-exam-routine/datatable', [\App\Http\Controllers\Admin\DashboardController::class, 'getTrainerExamRoutine'])
        ->name('trainer.exam-routine.datatable');
});

Route::group(['middleware' => ['auth']], function () {
    Route::post('/admin/typeahead/search', [App\Http\Controllers\UtilityAPI\TypeaheadAPI::class, 'index'])->name('admin.typeahead.search');
});

Route::group(['middleware' => ['web']], function () {
    Route::get('/admin/login/{redirect?}', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('login');
    Route::get('/user/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'showLoginForm'])->name('admin.login-form');
    Route::post('/admin/login', [App\Http\Controllers\Admin\Auth\LoginController::class, 'login'])->name('admin.login');
    Route::post('/admin/logout', [App\Http\Controllers\Admin\Auth\LoginController::class, 'logout'])->name('admin.logout');
    Route::get('/admin/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'showRegistrationForm'])->name('admin.register-form');
    Route::post('/admin/register', [App\Http\Controllers\Admin\Auth\RegisterController::class, 'register'])->name('admin.register');
    Route::get('/admin/forgot', [App\Http\Controllers\Admin\Auth\ForgotPasswordController::class, 'showForgotPasswordForm'])->name('admin.forgot-password-form');
});

Route::group(['prefix' => 'web-api', 'as' => 'web-api.'], function () {
    Route::post('model-resources', [ModelResourceFetchController::class, 'modelResources'])
        ->name('model-resources');
});

/** Change Language > options > Bangla|English */
Route::post('change-language/{language}', [LocalizationController::class, 'changeLanguage'])->name('change-language');
