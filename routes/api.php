<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;




/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/






Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('register', [AuthController::class, 'register'])->name('register');
    Route::post('login', [AuthController::class, 'login'])->name('login');
    Route::post('refresh', [AuthController::class, 'refresh'])->middleware('auth:api')->name('refresh');

    Route::middleware(['auth.jwt', 'role:admin'])->group( function() {
        Route::get('admin/panel', [AdminController::class, 'index']);
    });
    
    Route::middleware(['auth.jwt','role:user'])->group( function() {
      Route::get('user/panel', [UserController::class, 'index']);
    });
});