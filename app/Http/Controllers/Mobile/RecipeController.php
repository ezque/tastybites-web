<?php

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Models\Notification;
use App\Models\Reaction;
use App\Models\Recipe;
use Illuminate\Http\Request;
use App\Services\Mobile\RecipeServices;

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


}

