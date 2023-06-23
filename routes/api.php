<?php

use App\Http\Controllers\AuthController;
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
    Route::get('room', [RoomController::class, 'index'])->name('room.index');
    Route::post('room', [RoomController::class, 'create'])->can('role:admin')->name('room.create');
    Route::put('room/{id}', [RoomController::class, 'update'])->can('role:admin')->name('room.update');
    Route::delete('room/{id}', [RoomController::class, 'delete'])->can('role:admin')->name('room.delete');
});