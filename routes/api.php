<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\RecipeController;
use App\Http\Controllers\Mobile\UserController;
use App\Http\Controllers\Mobile\NotificationController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('auth:sanctum')->get('/user-profile', [AuthController::class, 'getPersonalInformation']);
Route::middleware('auth:sanctum')->post('/edit-profile', [AuthController::class, 'updateProfile']);
Route::middleware('auth:sanctum')->post('/change-password', [AuthController::class, 'changePassword']);

Route::middleware('auth:sanctum')->get('/recipes', [RecipeController::class, 'getRecipeCards']);
Route::middleware('auth:sanctum')->get('/recipes/saved/purchase', [RecipeController::class, 'getPurchaseSavedRecipes']);
Route::middleware('auth:sanctum')->get('/recipes/{id}/reactions', [RecipeController::class, 'counts']);
Route::middleware('auth:sanctum')->post('/recipes/{id}/react', [RecipeController::class, 'reactRecipe']);
Route::middleware('auth:sanctum')->get('/all/chefs-information', [UserController::class, 'getChefs']);
Route::middleware('auth:sanctum')->get('/recipes/chef/{id}', [RecipeController::class, 'getRecipesByChef']);

Route::middleware('auth:sanctum')->post('/buy-recipe', [RecipeController::class, 'buyRecipe']);
Route::middleware('auth:sanctum')->post('/save-unsave-recipe/{id}', [RecipeController::class, 'saveUnsaveRecipe']);
Route::middleware('auth:sanctum')->post('/hide-unhide-recipe/{id}', [RecipeController::class, 'hideUnhideRecipe']);
Route::middleware('auth:sanctum')->get('/display-notification', [NotificationController::class, 'notification']);
