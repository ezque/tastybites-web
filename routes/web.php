<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChefController;



Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register-post', [AuthController::class, 'registerPost']);
Route::post('/login-post', [AuthController::class, 'loginPost']);


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user-dashboard', [UserController::class, 'dashboard']);
    Route::get('/chef-dashboard', [ChefController::class, 'dashboard']);
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard']);
});
