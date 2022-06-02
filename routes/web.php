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

        Route::resource('categories', App\Http\Controllers\Apps\CategoryController::class, ['as' => 'apps'])
        ->middleware('permission:categories.index|categories.create|categories.edit|categories.delete');

        Route::resource('products', App\Http\Controllers\Apps\ProductController::class, ['as' => 'apps'])
        ->middleware('permission:products.index|products.create|products.edit|products.delete');

        Route::resource('customers', App\Http\Controllers\Apps\CustomerController::class, ['as' => 'apps'])
        ->middleware('permission:customers.index|customers.create|customers.edit|customers.delete');

        /**
         * Transaction Routes
         */
        Route::get('/transactions', [App\Http\Controllers\Apps\TransactionController::class, 'index'])
        ->name('apps.transaction.index');

        Route::post('/transactions/search-product', [App\Http\Controllers\Apps\TransactionController::class, 'searchProduct'])
        ->name('apps.transaction.search-product');

        Route::post('/transactions/add-to-cart', [App\Http\Controllers\Apps\TransactionController::class, 'addToCart'])
        ->name('apps.transaction.add-to-cart');

        Route::delete('/transactions/destroy-cart', [App\Http\Controllers\Apps\TransactionController::class, 'destroyCart'])
        ->name('apps.transaction.destroy-cart');

        Route::post('/transactions/store', [App\Http\Controllers\Apps\TransactionController::class, 'store'])
        ->name('apps.transaction.store');

        Route::get('/transactions/print', [App\Http\Controllers\Apps\TransactionController::class, 'print'])
        ->name('apps.transaction.print');

        /**
         * Route Sales
         */
        Route::get('/sales', [\App\Http\Controllers\Apps\SaleController::class, 'index'])
        ->middleware('permission:sales.index')->name('apps.sales.index');

        Route::get('/sales/filter', [\App\Http\Controllers\Apps\SaleController::class, 'filter'])
        ->name('apps.sales.filter');

        Route::get('/sales/export', [\App\Http\Controllers\Apps\SaleController::class, 'export'])
        ->name('apps.sales.export');

        Route::get('/sales/pdf', [\App\Http\Controllers\Apps\SaleController::class, 'pdf'])
        ->name('apps.sales.pdf');

    });
});
