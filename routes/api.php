<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\RoomController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'auth'
], function () {
    Route::post('login', [AuthController::class, 'login'])->name('auth.login');
    Route::post('register', [AuthController::class, 'register'])->name('auth.register');
});

Route::group([
    'middleware' => 'auth:api'
], function () {
    /**
     * Room routes
     */
    Route::get('room', [RoomController::class, 'index'])->name('room.index');
    Route::middleware('role:admin|user')->post('room', [RoomController::class, 'create'])->name('room.create');
    Route::middleware('role:admin|user')->put('room/{id}', [RoomController::class, 'update'])->name('room.update');
    Route::middleware('role:admin|user')->delete('room/{id}', [RoomController::class, 'delete'])->name('room.delete');

    /**
     * Categories routes
     */
    Route::get('category', [CategoryController::class, 'index'])->name('category.index');
    Route::middleware('role:admin|user')->post('category', [CategoryController::class, 'create'])->name('category.create');
    Route::middleware('role:admin|user')->put('category/{id}', [CategoryController::class, 'update'])->name('category.update');
    Route::middleware('role:admin|user')->delete('category/{id}', [CategoryController::class, 'delete'])->name('category.delete');

    /**
     * Coupons routes
     */
    Route::get('coupon', [CouponController::class, 'index'])->name('coupon.index');
    Route::middleware('role:admin|user')->post('coupon', [CouponController::class, 'create'])->name('coupon.create');
    Route::middleware('role:admin|user')->put('coupon/{id}', [CouponController::class, 'update'])->name('coupon.update');
    Route::middleware('role:admin|user')->delete('coupon/{id}', [CouponController::class, 'delete'])->name('coupon.delete');
});
