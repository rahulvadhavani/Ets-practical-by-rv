<?php

use App\Http\Controllers\{CustomerController, HomeController, RoleController, SupplierController, UserController};
use Illuminate\Support\Facades\Auth;
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

Route::get('/', function () {
    return view('welcome');
});

Route::group(['prefix' => 'admin'], function () {
    Auth::routes(['register' => false]);
});

Route::group(['middleware' => ['auth'], 'prefix' => 'admin', 'as' => 'admin.'], function () {
    Route::group(['controller' => UserController::class], function () {
        Route::resource('users', UserController::class);
        Route::get('/get-cities/{stateId}', [UserController::class, 'getCitiesByState'])->name('get.cities');
        Route::post('users/delete-selected', [UserController::class, 'deleteSelected'])->name('users.delete-selected');

        Route::resource('roles', RoleController::class)->except('edit', 'update', 'create');
        Route::resource('suppliers', SupplierController::class)->except('edit', 'update', 'create');
        Route::post('suppliers/delete-selected', [SupplierController::class, 'deleteSelected'])->name('suppliers.delete-selected');

        Route::resource('customers', CustomerController::class)->except('edit', 'update', 'create');
        Route::post('customers/delete-selected', [CustomerController::class, 'deleteSelected'])->name('customers.delete-selected');
    });
    Route::group(['controller' => HomeController::class], function () {
        Route::get('/',  'index');
        Route::get('dashboard',  'index')->name('home');
        Route::get('profile', 'Profile')->name('profile');
        Route::post('update-profile', 'updateAdminProfile')->name('update_profile');
        Route::post('update-password', 'updatePassword')->name('update_password');
        Route::get('logout', 'logout')->name('logout');
    });
});
