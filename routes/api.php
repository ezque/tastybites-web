<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Mobile\AuthController;
use App\Http\Controllers\Mobile\RecipeController;


Route::post('/login', [AuthController::class, 'login']);
Route::post('/register', [AuthController::class, 'register']);
Route::middleware('auth:sanctum')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:sanctum')->get('/recipes', [RecipeController::class, 'getRecipeCards']);
Route::middleware('auth:sanctum')->get('/recipes/saved/purchase', [RecipeController::class, 'getPurchaseSavedRecipes']);
Route::middleware('auth:sanctum')->get('/recipes/{id}/reactions', [RecipeController::class, 'counts']);
Route::middleware('auth:sanctum')->post('/recipes/{id}/react', [RecipeController::class, 'reactRecipe']);


