<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Recipe;
use App\Models\Ingredient;
use App\Models\Procedure;
use App\Models\User;
use App\Models\Report;
use App\Models\Purchase;
use App\Models\Reaction;
use App\Models\HideRecipe;
use App\Models\SaveRecipe;
use App\Models\Notification;
class RecipeController extends Controller
{
    public function addRecipe(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'recipeName' => 'required|string|max:255',
            'cuisineType' => 'required|string|max:255',
            'description' => 'required|string',
            'image_path' => 'required|image|mimes:jpeg,png,jpg,gif|max:100000',
            'video_path' => 'nullable|string|max:255',
            'gcash_number' => 'nullable|string|max:255',
            'price' => 'nullable|numeric',

            'gCash_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100000',
            'receipt_path' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:100000',

            'ingredients' => 'required|array|min:1',
            'ingredients.*.ingredientName' => 'required|string|max:255',
            'ingredients.*.quantity' => 'nullable|string|max:100',

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
            if ($isFree === 'premium') {
                $admins = User::where('role', 'admin')->get();

                foreach ($admins as $admin) {
                    Notification::create([
                        'userID'   => $admin->id,
                        'senderID' => auth()->id(),
                        'message'  => 'submitted a premium recipe. Review now.',
                        'status'   => 'unread',
                        'type'     => 'addPremiumRecipe',
                    ]);
                }
            }
            if ($isFree === 'free') {
                $admins = User::where('role', 'admin')->get();

                foreach ($admins as $admin) {
                    Notification::create([
                        'userID'   => $admin->id,
                        'senderID' => auth()->id(),
                        'message'  => 'submitted a free recipe. Review now.',
                        'status'   => 'unread',
                        'type'     => 'addFreeRecipe',
                    ]);
                }
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
    public function updateRecipe(Request $request, $id)
    {
        $recipe = Recipe::findOrFail($id);

        // ✅ Validation: only validate fields present in request
        $validator = Validator::make($request->all(), [
            'recipeName' => 'sometimes|required|string|max:255',
            'cuisineType' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'video_path' => 'sometimes|nullable|string|max:255',
            'price' => 'sometimes|nullable|numeric',
            'gcash_number' => 'sometimes|nullable|string|max:255',
            'image_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:100000',
            'gCash_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:100000',
            'receipt_path' => 'sometimes|nullable|image|mimes:jpeg,png,jpg|max:100000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // ✅ Update only fields included in the request
        $recipe->fill($request->only([
            'recipeName', 'cuisineType', 'description', 'video_path', 'price', 'gcash_number'
        ]));

        if ($request->has('price')) {
            $price = floatval($request->price);

            // price > 0 = premium
            $recipe->is_free = $price > 0 ? 'premium' : 'free';
            // from free to premium need to confirm by admin
            $recipe->status = $price > 0 ? 'pending' : 'active';
        }

        // ✅ Handle optional file uploads
        if ($request->hasFile('image_path')) {
            $recipe->image_path = $request->file('image_path')->store('recipes', 'public');
        }

        if ($request->hasFile('gCash_path')) {
            $recipe->gCash_path = $request->file('gCash_path')->store('gcash', 'public');
        }

        if ($request->hasFile('receipt_path')) {
            $recipe->receipt_path = $request->file('receipt_path')->store('receipts', 'public');
        }

        $recipe->save();

        // ✅ Update ingredients if included in request
        if ($request->filled('ingredients')) {
            $recipe->ingredient()->delete(); // delete existing ingredients
            foreach (json_decode($request->ingredients, true) as $ingredient) {
                $recipe->ingredient()->create($ingredient); // use singular relationship
            }
        }

        // ✅ Update procedures if included in request
        if ($request->filled('procedures')) {

            // Delete old procedures
            $recipe->procedure()->delete();

            // Insert updated procedures with auto step numbering
            $procedures = json_decode($request->procedures, true);

            foreach ($procedures as $index => $procedure) {
                $recipe->procedure()->create([
                    'step'        => $index + 1,               // auto numbering
                    'instruction' => $procedure['instruction'] // step text
                ]);
            }
        }

        return response()->json(['message' => 'Recipe updated successfully']);
    }

    public function deleteRecipe(Request $request, $id)
    {
        $recipe = Recipe::find($id);

        if (!$recipe) {
            return response()->json(['message' => 'Recipe not found'], 404);
        }

        // Optional: Check ownership
        if ($recipe->userID !== auth()->id()) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // This triggers the boot() deleting event above
        $recipe->delete();

        return response()->json(['message' => 'Recipe deleted successfully']);
    }




    public function buyRecipe(Request $request): \Illuminate\Http\JsonResponse
    {
        try {
            $request->validate([
                'recipeID'     => 'required|exists:recipes,id',
                'phone_number' => 'required|string',
                'amount'       => 'required|numeric',
                'reference'    => 'required|string',
                'proof'        => 'nullable|image|max:10048',
            ]);

            $proofPath = null;
            if ($request->hasFile('proof')) {
                $proofPath = $request->file('proof')->store('proofs', 'public');
            }

            $purchase = Purchase::create([
                'userID'       => auth()->id(),
                'recipeID'     => $request->recipeID,
                'status'       => 'Pending',
                'purchase_at'  => now(),
                'phone_number' => $request->phone_number,
                'amount'       => $request->amount,
                'reference'    => $request->reference,
                'proof_path'   => $proofPath,
            ]);
            $recipe = Recipe::findOrFail($request->recipeID);

            Notification::create([
                'userID' => $recipe->userID,
                'senderID' => auth()->id(),
                'message'  => 'Your recipe "' . $recipe->recipeName . '" has been purchased by '
                    . auth()->user()->userInfo->userName,
                'status'  => 'unread',
                'type'    => 'recipePurchased',
            ]);

            return response()->json([
                'status'   => 'success',
                'message'  => 'Recipe purchased successfully. Pending verification.',
                'purchase' => $purchase
            ], 201);

        } catch (\Throwable $e) {
            \Log::error('Buy Recipe Error: ' . $e->getMessage(), ['exception' => $e]);

            return response()->json([
                'status'  => 'error',
                'message' => 'Internal server error.',
            ], 500);
        }
    }

    public function reactRecipe(Request $request, $id)
    {
        try {
            $request->validate([
                'reaction_type' => 'required|in:1,2,3',
            ]);

            $userID = auth()->id();
            $recipeID = $id;
            $reactionType = $request->reaction_type;

            $reaction = Reaction::where('userID', $userID)
                ->where('recipeID', $recipeID)
                ->first();

            if ($reaction) {
                // Update existing reaction
                $reaction->update([
                    'reaction_type' => $reactionType,
                ]);
            } else {
                // Create new reaction
                $reaction = Reaction::create([
                    'userID' => $userID,
                    'recipeID' => $recipeID,
                    'reaction_type' => $reactionType,
                ]);
            }

            // Create a notification for recipe owner
            $recipe = Recipe::with('user.userInfo')->findOrFail($recipeID);

            if ($recipe->userID !== $userID) {
                $reactorName = auth()->user()->userInfo->fullName ?? 'Someone';

                // Map reaction_type to notification type
                $notificationType = null;
                if ($reactionType == 1) $notificationType = 'liked';
                elseif ($reactionType == 2) $notificationType = 'disliked';

                Notification::create([
                    'userID' => $recipe->userID,
                    'senderID' => $userID,
                    'recipeID' => $recipeID,
                    'message' => $reactorName . ' reacted to your recipe "' . $recipe->recipeName . '"',
                    'status' => 'unread',
                    'type' => $notificationType,
                ]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Reaction saved successfully',
                'reaction' => $reaction
            ], 200);

        } catch (\Throwable $e) {
            \Log::error('React Recipe Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    public function counts($id)
    {
        $recipe = Recipe::findOrFail($id);

        $likeCount = $recipe->reactions()->where('reaction_type', 1)->count();
        $dislikeCount = $recipe->reactions()->where('reaction_type', 2)->count();
        $userReaction = $recipe->reactions()->where('userID', auth()->id())->first();

        return response()->json([
            'likeCount' => $likeCount,
            'dislikeCount' => $dislikeCount,
            'reaction_type' => $userReaction ? $userReaction->reaction_type : null,
        ]);
    }


    public function hideUnhideRecipe(Request $request, $id)
    {
        $userId = Auth::id();

        $hideRecord = HideRecipe::where('userID', $userId)
            ->where('recipeID', $id)
            ->first();

        if ($hideRecord) {
            $hideRecord->is_hidden = $hideRecord->is_hidden == '1' ? '0' : '1';
            $hideRecord->save();
        } else {
            $hideRecord = HideRecipe::create([
                'userID'   => $userId,
                'recipeID' => $id,
                'is_hidden'=> '1',
            ]);
        }

        return response()->json([
            'success' => true,
            'hide'    => $hideRecord
        ]);
    }

    public function saveUnsiveRecipe(Request $request, $id)
    {
        $userId = Auth::id();

        $saveRecord = SaveRecipe::where('userID', $userId)
            ->where('recipeID', $id)
            ->first();

        if ($saveRecord) {
            // Toggle
            $saveRecord->save_status = $saveRecord->save_status == '1' ? '0' : '1';
            $saveRecord->save();
        } else {
            // Create new saved record
            $saveRecord = SaveRecipe::create([
                'userID' => $userId,
                'recipeID' => $id,
                'save_status' => '1',
            ]);
        }

        return response()->json([
            'success'     => true,
            'save_status' => (int) $saveRecord->save_status,
            'recipeID'    => $id,
        ]);
    }


    public function reportRecipe(Request $request, $id)
    {
        $request->validate([
            'type'   => 'required|in:user,recipe',
            'reason' => 'required|string|max:1000',
        ]);

        $reportData = [
            'reporterID'       => auth()->id(),
            'reportedUserID'   => null,
            'reportedRecipeID' => null,
            'reason'           => $request->reason,
            'status'           => 'pending',
        ];

        if ($request->type === 'user') {
            $reportData['reportedUserID'] = $id;
        } elseif ($request->type === 'recipe') {
            $reportData['reportedRecipeID'] = $id;
        }

        $report = Report::create($reportData);

        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            if ($report->reportedUserID !== null) {
                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => auth()->id(),
                    'message'  => 'A Chef has been reported.',
                    'status'   => 'unread',
                    'type'     => 'report',
                ]);
            } elseif ($report->reportedRecipeID !== null) {
                $recipe = Recipe::find($report->reportedRecipeID);

                Notification::create([
                    'userID'   => $admin->id,
                    'senderID' => auth()->id(),
                    'message'  => 'A recipe "' . ($recipe->recipeName ?? 'Unknown') . '" has been reported.',
                    'status'   => 'unread',
                    'type'     => 'report',
                    'reportID' => $report->id,
                ]);
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Report submitted successfully.',
            'report'  => $report,
        ]);
    }

}
