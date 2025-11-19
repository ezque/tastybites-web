<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ChefController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\ViewController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\RatingController;



Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register']);
Route::post('/register-post', [AuthController::class, 'registerPost']);
Route::post('/login-post', [AuthController::class, 'loginPost']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');


Route::middleware(['auth:sanctum'])->group(function () {
    Route::post('/edit-personal-information', [AuthController::class, 'editPersonalInformation']);
    Route::post('/change-password', [AuthController::class, 'changePassword']);
    Route::post('/update-profile-picture', [AuthController::class, 'updateProfilePicture']);

    Route::get('/user-dashboard', [ViewController::class, 'userDashboard']);
    Route::get('/chef-dashboard', [ViewController::class, 'chefDashboard']);
    Route::get('/admin-dashboard', [ViewController::class, 'adminDashboard']);

    Route::post('/accept-chef', [AdminController::class, 'acceptChef']);
    Route::post('/decline-chef', [AdminController::class, 'rejectChef']);
    Route::post('/update-user-status', [AdminController::class, 'updateUserStatus']);
    Route::post('/recipes/{id}/status', [AdminController::class, 'updateChefRecipeStatus']);
    Route::post('/respond/{id}', [AdminController::class, 'respondReport']);
    Route::get('/report/{id}', [AdminController::class, 'getReport']);

    Route::post('/purchase-status/{id}', [ChefController::class, 'purchaseStatus']);
    Route::post('add-certificate', [ChefController::class, 'addCertificate'])->name('certificates.store');

    Route::get('/recipes/{id}/counts', [RecipeController::class, 'counts']);
    Route::get('/all-recipes', [RecipeController::class, 'getAllRecipes']);
    Route::post('/react-recipe/{id}', [RecipeController::class, 'reactRecipe']);
    Route::post('/buy-recipe', [RecipeController::class, 'buyRecipe']);
    Route::post('/hide-recipe/{id}', [RecipeController::class, 'hideUnhideRecipe']);
    Route::post('/save-recipe/{id}', [RecipeController::class, 'saveUnsiveRecipe']);
    Route::post('/add-recipes', [RecipeController::class, 'addRecipe'])->name('recipes.store');
    Route::post('/report/{id}', [RecipeController::class, 'reportRecipe'])->name('report.recipe');
    Route::patch('/update-recipe/{id}', [RecipeController::class, 'updateRecipe']);
    Route::delete('/delete-recipe/{id}', [RecipeController::class, 'deleteRecipe']);

    Route::get('/chef-info', [UserController::class, 'getChefInfo']);
    Route::get('/top-chef', [UserController::class, 'getTopChefs']);
    Route::post('/report/chef/{id}', [UserController::class, 'reportChef'])->name('report.chef');
    Route::post('/follow/{id}', [UserController::class, 'follow']);

    //Notification
    Route::post('/read-all-notifications', [NotificationController::class, 'readAllNotifications']);
    Route::post('/read-notification/{id}', [NotificationController::class, 'readNotification']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'deleteNotification']);

    Route::get('/user-ratings/{id}', [RatingController::class, 'getUserRateForRecipe']);
    Route::post('/rate-recipe/{id}', [RatingController::class, 'rateRecipe']);
});

