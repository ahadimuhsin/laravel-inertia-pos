<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

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

// route home
Route::get('/', function(){
    return Inertia::render('Auth/Login');
})->middleware('guest');

//prefix apps
Route::prefix('apps')->group(function(){
    //middleware auth
    Route::middleware(['auth'])->group(function () {
        // route dashboard
        Route::get('dashboard', App\Http\Controllers\Apps\DashboardController::class)
        ->name('apps.dashboard');
        //route permissions
        Route::get('/permissions', App\Http\Controllers\Apps\PermissionController::class)
        ->name('apps.permissions.index')
        ->middleware('permission:permissions.index');
        //route roles
        Route::resource('roles', App\Http\Controllers\Apps\RoleController::class, ['as' => 'apps'])
        ->middleware('permission:roles.index|roles.create|roles.edit|roles.delete');

        Route::resource('users', App\Http\Controllers\Apps\UserController::class, ['as' => 'apps'])
        ->middleware('permission:users.index|users.create|users.edit|users.delete');
    });
});
