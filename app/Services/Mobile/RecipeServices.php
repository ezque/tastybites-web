<?php

namespace App\Services\Mobile;

use App\Models\Recipe;

class RecipeServices
{

    public function getRecipeCardDetails()
    {
        $userId = auth()->id();

        $recipes = Recipe::with([
            'user.userInfo',
            'purchase' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            'userReaction',
            'ingredient',
            'procedure',
            'hidden' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            'savedBy' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
        ])
            ->where(function ($query) use ($userId) {
                $query->whereDoesntHave('hidden', function ($q) use ($userId) {
                    $q->where('userID', $userId)->where('is_hidden', 1);
                })
                    ->orWhereHas('hidden', function ($q) use ($userId) {
                        $q->where('userID', $userId)->where('is_hidden', 0);
                    });
            })

            ->withCount([
                'userReaction as total_likes' => function ($q) {
                    $q->where('reaction_type', '1');
                },
                'userReaction as total_dislikes' => function ($q) {
                    $q->where('reaction_type', '2');
                },
            ])
            ->select(
                'id',
                'recipeName',
                'description',
                'price',
                'cuisineType',
                'status',
                'image_path',
                'video_path',
                'userID',
                'is_free'
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $recipes->getCollection()->transform(function ($recipe) use ($userId) {
            $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
            $recipe->userReactedLike = $recipe->reaction_type === '1';
            $recipe->userReactedDislike = $recipe->reaction_type === '2';
            $recipe->is_hidden = $recipe->hidden?->is_hidden ?? 0;
            $recipe->is_saved = $recipe->savedBy?->save_status ?? 0;
            $recipe->is_owned = $recipe->userID === $userId;

            $recipe->is_purchased = $recipe->purchase !== null;

            if (!$recipe->purchase) {
                $recipe->purchase = (object)[
                    'status' => null,
                    'id' => null,
                    'proof_path' => null,
                ];
            }

            if ($recipe->is_purchased || $recipe->is_free === 'free' || $recipe->is_owned) {
                $recipe->ingredient;
                $recipe->procedure;
            } else {
                $recipe->setRelation('ingredient', collect());
                $recipe->setRelation('procedure', collect());
            }

            $recipe->image_url = $recipe->image_path
                ? asset('storage/' . $recipe->image_path)
                : null;

            return $recipe;
        });

        return $recipes;
    }



    public function getSavedPurchasedRecipes()
    {
        $userId = auth()->id();

        $recipes = Recipe::with([
            'user.userInfo',
            'purchase' => function ($q) use ($userId) {
                $q->where('userID', $userId); // only the logged-in user's purchase
            },
            'userReaction',
            'ingredient',
            'procedure',
            'hidden' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            'savedBy' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
        ])
            ->where(function ($query) use ($userId) {
                $query->whereHas('purchase', function ($q) use ($userId) {
                    $q->where('userID', $userId);
                })
                    ->orWhereHas('savedBy', function ($q) use ($userId) {
                        $q->where('userID', $userId)
                            ->where('save_status', '1');
                    })
                    ->orWhereHas('hidden', function ($q) use ($userId) {
                        $q->where('userID', $userId)
                            ->where('is_hidden', '1');
                    });
            })
            ->withCount([
                'userReaction as total_likes' => function ($q) {
                    $q->where('reaction_type', '1');
                },
                'userReaction as total_dislikes' => function ($q) {
                    $q->where('reaction_type', '2');
                }
            ])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        // Transform each recipe to include all details like getRecipeCardDetails()
        $recipes->getCollection()->transform(function ($recipe) use ($userId) {
            $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
            $recipe->userReactedLike = $recipe->reaction_type === '1';
            $recipe->userReactedDislike = $recipe->reaction_type === '2';
            $recipe->is_hidden = $recipe->hidden?->is_hidden ?? 0;
            $recipe->is_saved = $recipe->savedBy?->save_status == '1';
            $recipe->is_owned = $recipe->userID === $userId;
            $recipe->is_purchased = $recipe->purchase !== null;

            // Ensure purchase object is always set
            if (!$recipe->purchase) {
                $recipe->purchase = (object)[
                    'status' => null,
                    'id' => null,
                    'proof_path' => null,
                ];
            }

            // Include ingredients & procedures if purchased, free, or owned
            if ($recipe->is_purchased || $recipe->is_free === 'free' || $recipe->is_owned) {
                $recipe->ingredient;
                $recipe->procedure;
            } else {
                $recipe->setRelation('ingredient', collect());
                $recipe->setRelation('procedure', collect());
            }

            // Full image URL
            $recipe->image_url = $recipe->image_path ? asset('storage/' . $recipe->image_path) : null;

            return $recipe;
        });

        return $recipes;
    }





    public function getChefRecipes($chefId)
    {
        $userId = auth()->id(); // the viewer (could be customer)

        $recipes = Recipe::with([
            'user.userInfo',
            'purchase' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            'userReaction',
            'hidden' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            'savedBy' => function ($q) use ($userId) {
                $q->where('userID', $userId);
            },
            // ✅ Include these two relationships
            'ingredient',
            'procedure',
        ])
            ->where('userID', $chefId)
            ->whereHas('user', function ($q) {
                $q->where('role', 'chef');
            })
            ->withCount([
                'userReaction as total_likes' => function ($q) {
                    $q->where('reaction_type', '1');
                },
                'userReaction as total_dislikes' => function ($q) {
                    $q->where('reaction_type', '2');
                },
            ])
            ->select(
                'id',
                'recipeName',
                'description',
                'price',
                'cuisineType',
                'status',
                'image_path',
                'video_path',
                'userID',
                'is_free'
            )
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        $recipes->getCollection()->transform(function ($recipe) use ($userId) {
            $recipe->reaction_type = $recipe->userReaction->reaction_type ?? null;
            $recipe->userReactedLike = $recipe->reaction_type === '1';
            $recipe->userReactedDislike = $recipe->reaction_type === '2';
            $recipe->is_hidden = $recipe->hidden?->is_hidden ?? '0';
            $recipe->is_saved = $recipe->savedBy?->save_status ?? '0';
            $recipe->is_owned = $recipe->userID === $userId;
            $recipe->is_purchased = $recipe->purchase ? true : false;

            // ✅ Load or hide ingredient/procedure depending on access
            if ($recipe->is_purchased || $recipe->is_free === 'free' || $recipe->is_owned) {
                $recipe->ingredient;
                $recipe->procedure;
            } else {
                $recipe->setRelation('ingredient', collect());
                $recipe->setRelation('procedure', collect());
            }

            // ✅ Image URL
            $recipe->image_url = $recipe->image_path
                ? asset('storage/' . $recipe->image_path)
                : null;

            return $recipe;
        });

        return $recipes;
    }







}
