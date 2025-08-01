<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Procedure;
class RecipeController extends Controller
{
    public function addRecipe(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipeName' => 'required|string|max:255',
            'cuisineType' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:20048',
            'video_path' => 'nullable|string|max:255',
            'gcash_number' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',

            'gCash_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20048',
            'receipt_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:20048',

            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredientName' => 'required|string|max:255',
            'ingredients.*.quantity' => 'required|string|max:100',

            'procedures' => 'required|array|min:1',
            'procedures.*.instruction' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $imagePath = $request->hasFile('image_path')
                ? $request->file('image_path')->store('recipe_images', 'public')
                : null;

            $gCashPath = $request->hasFile('gCash_path')
                ? $request->file('gCash_path')->store('gcash_qr_codes', 'public')
                : null;

            $receiptPath = $request->hasFile('receipt_path')
                ? $request->file('receipt_path')->store('payment_receipts', 'public')
                : null;

            $price = floatval($request->price ?? 0);



            if ($price > 0) {
                $isFree = 'premium';
                $status = 'pending';
            } else {
                $isFree = 'free';
                $status = 'active';
            }


            $recipe = Recipe::create([
                'userID' => auth()->id(),
                'recipeName' => $request->recipeName,
                'cuisineType' => $request->cuisineType,
                'description' => $request->description,
                'image_path' => $imagePath,
                'video_path' => $request->video_path,
                'gcash_number' => $request->gcash_number,
                'gCash_path' => $gCashPath,
                'receipt_path' => $receiptPath,
                'is_free' => $isFree,
                'price' => $price,
                'status' => $status,
            ]);

            foreach ($request->ingredients as $ingredient) {
                Ingredient::create([
                    'recipeID' => $recipe->id,
                    'ingredientName' => $ingredient['ingredientName'],
                    'quantity' => $ingredient['quantity'],
                ]);
            }

            foreach ($request->procedures as $index => $procedure) {
                Procedure::create([
                    'recipeID' => $recipe->id,
                    'step' => $index + 1,
                    'instruction' => $procedure['instruction'],
                ]);
            }

            return response()->json([
                'status' => 'success',
                'message' => 'Recipe added successfully.',
            ]);
        } catch (\Throwable $e) {
            \Log::error('Add Recipe Error: ' . $e->getMessage(), ['exception' => $e]);
            return response()->json([
                'status' => 'error',
                'message' => 'Internal server error.',
            ], 500);
        }
    }


}
