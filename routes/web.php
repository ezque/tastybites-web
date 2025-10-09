<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\RecipeController;



Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register-post', [AuthController::class, 'registerPost']);
Route::post('/login-post', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::get('/user-dashboard', [UserController::class, 'dashboard']);
    Route::get('/chef-dashboard', [ChefController::class, 'dashboard']);
    Route::get('/admin-dashboard', [AdminController::class, 'dashboard']);
    Route::post('/accept-chef', [AdminController::class, 'acceptChef']);
    Route::post('/decline-chef', [AdminController::class, 'rejectChef']);
    Route::post('/block-user', [AdminController::class, 'blockUser']);
    Route::post('/buy-recipe', [RecipeController::class, 'buyRecipe']);

    Route::post('/purchase-accept/{id}', [ChefController::class, 'acceptPurchase']);
    Route::post('/purchase-reject/{id}', [ChefController::class, 'deniedPurchae']);

    Route::post('/react-recipe/{id}', [RecipeController::class, 'reactRecipe']);
    Route::get('/recipes/{id}/counts', [RecipeController::class, 'counts']);

    Route::post('/add-recipes', [RecipeController::class, 'addRecipe'])->name('recipes.store');
    Route::post('add-certificate', [ChefController::class, 'addCertificate'])->name('certificates.store');

    Route::post('/edit-personal-information', [AuthController::class, 'editPersonalInformation']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/update-profile-picture', [AuthController::class, 'updateProfilePicture']);
    Route::post('/hide-recipe/{id}', [RecipeController::class, 'hideUnhideRecipe']);
    Route::post('/save-recipe/{id}', [RecipeController::class, 'saveUnsiveRecipe']);

    Route::post('/recipes/{id}/status', [AdminController::class, 'updateChefRecipeStatus']);
    Route::post('/report/{id}', [RecipeController::class, 'reportRecipe'])->name('report.recipe');
    Route::post('/report/chef/{id}', [UserController::class, 'reportChef'])->name('report.chef');
});

