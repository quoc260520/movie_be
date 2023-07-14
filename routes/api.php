<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CouponController;
use App\Http\Controllers\MovieController;
use App\Http\Controllers\OrderMovieController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SlideController;
use App\Http\Controllers\TimeMovieController;
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
    Route::group([
        'middleware' => 'auth:api'
    ], function () {
        Route::get('me', [AuthController::class, 'me'])->name('auth.me');
        Route::get('logout', [AuthController::class, 'logout'])->name('auth.logout');
    });
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

    /**
     * Slides routes
     */
    Route::get('slide', [SlideController::class, 'index'])->name('slide.index');
    Route::middleware('role:admin|user')->post('slide', [SlideController::class, 'create'])->name('slide.create');
    Route::middleware('role:admin|user')->put('slide/{id}', [SlideController::class, 'update'])->name('slide.update');
    Route::middleware('role:admin|user')->delete('slide/{id}', [SlideController::class, 'delete'])->name('slide.delete');

    /**
     * Movies routes
     */
    Route::middleware('role:admin|user')->post('movie', [MovieController::class, 'create'])->name('movie.create');
    Route::middleware('role:admin|user')->put('movie/{id}', [MovieController::class, 'update'])->name('movie.update');
    Route::middleware('role:admin|user')->delete('movie/{id}', [MovieController::class, 'delete'])->name('movie.delete');
    
    /**
     * Time movies routes
     */
    Route::get('time-movie/day', [TimeMovieController::class, 'getTimeByDay'])->name('time-movie.day');
    Route::get('time-movie', [TimeMovieController::class, 'index'])->name('time-movie.index');
    Route::middleware('role:admin|user')->post('time-movie', [TimeMovieController::class, 'create'])->name('time-movie.create');
    Route::middleware('role:admin|user')->put('time-movie/{id}', [TimeMovieController::class, 'update'])->name('time-movie.update');
    Route::middleware('role:admin|user')->delete('time-movie/{id}', [TimeMovieController::class, 'delete'])->name('time-movie.delete');
    
    /**
     * Time movies routes
     */
    // Route::get('order-movie/day', [TimeMovieController::class, 'getTimeByDay'])->name('time-movie.day');
    // Route::get('order-movie', [TimeMovieController::class, 'index'])->name('order-movie.index');
    Route::middleware('role:admin|user')->get('order-movie/me', [OrderMovieController::class, 'getOrderByUser'])->name('order-movie.me');
    Route::middleware('role:admin|user')->post('order-movie', [OrderMovieController::class, 'create'])->name('order-movie.create');
    // Route::middleware('role:admin|user')->put('order-movie/{id}', [TimeMovieController::class, 'update'])->name('order-movie.update');
    // Route::middleware('role:admin|user')->delete('order-movie/{id}', [TimeMovieController::class, 'delete'])->name('order-movie.delete');
});

Route::get('movie', [MovieController::class, 'index'])->name('movie.index');
Route::get('movie/{id}', [MovieController::class, 'getMovieById'])->name('movie.id');
Route::get('movie-time/{movieId}', [MovieController::class, 'getMovieWithTime'])->name('movie.time');
