<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\HideRecipe;
use App\Models\Notification;
use App\Models\Purchase;
use App\Models\Reaction;
use App\Models\Recipe;
use App\Models\SaveRecipe;
use Illuminate\Http\Request;
use App\Services\Mobile\RecipeServices;
use Illuminate\Support\Facades\Auth;

class RecipeController extends Controller
{
    protected $recipeServices;

    public function __construct(RecipeServices $recipeServices)
    {
        $this->recipeServices = $recipeServices;
    }

    public function getRecipeCards(Request $request)
    {
        $recipes = $this->recipeServices->getRecipeCardDetails();
        return response()->json($recipes);
    }

    public function getPurchaseSavedRecipes(Request $request)
    {
        $recipe = $this->recipeServices->getSavedPurchasedRecipes();
        return response()->json($recipe);
    }
    public function counts($id)
    {
        $recipe = Recipe::findOrFail($id);

        $likeCount = $recipe->reactions()->where('reaction_type', 1)->count();
        $dislikeCount = $recipe->reactions()->where('reaction_type', 2)->count();
        $userReaction = $recipe->reactions()->where('userID', auth()->id())->first();

        return response()->json([
            'total_likes'    => $likeCount,
            'total_dislikes' => $dislikeCount,
            'user_reaction'  => $userReaction ? (int) $userReaction->reaction_type : null,
        ]);
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
    public function getRecipesByChef($id)
    {
        $recipes = $this->recipeServices->getChefRecipes($id);
        return response()->json($recipes);
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
    public function saveUnsaveRecipe(Request $request, $id)
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




}

