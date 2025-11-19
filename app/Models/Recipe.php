<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Recipe extends Model
{
    protected $fillable = [
        'userID',
        'recipeName',
        'cuisineType',
        'description',
        'image_path',
        'video_path',
        'gCash_path',
        'receipt_path',
        'gcash_number',
        'is_free',
        'price',
        'status',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'userID');
    }

    public function ingredient(){
        return $this->hasMany(Ingredient::class, 'recipeID');
    }
//    public function procedure()
//    {
//        return $this->hasMany(Procedure::class, 'recipeID');
//    }
    public function procedure()
    {
        return $this->hasMany(Procedure::class, 'recipeID', 'id')->orderBy('step', 'asc');
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class, 'recipeID')->where('userID', auth()->id());
    }
    public function allPurchases()
    {
        return $this->hasMany(Purchase::class, 'recipeID');
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'recipeID');
    }
    public function userReaction()
    {
        return $this->hasOne(Reaction::class, 'recipeID')
            ->where('userID', auth()->id());
    }
    public function hidden()
    {
        return $this->hasOne(HideRecipe::class, 'recipeID', 'id')
            ->where('userID', auth()->id());
    }
    public function savedBy()
    {
        return $this->hasOne(SaveRecipe::class, 'recipeID')
            ->where('userID', auth()->id());
    }
    public function reports()
    {
        return $this->hasMany(Report::class, 'reportedRecipeID');
    }
    public function ratings()
    {
        return $this->hasMany(RecipeRating::class, 'recipe_id');
    }

    public function userRating()
    {
        return $this->hasOne(RecipeRating::class, 'recipe_id')
            ->where('user_id', auth()->id());
    }
    protected function averageRating(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ratings()->avg('rating')
                ? round($this->ratings()->avg('rating'), 1)
                : 0
        );
    }

    protected function ratingsCount(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->ratings()->count()
        );
    }


    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($recipe) {
            // Delete related data
            $recipe->ingredient()->delete();
            $recipe->procedure()->delete();
            $recipe->reactions()->delete();
            $recipe->reports()->delete();
            $recipe->allPurchases()->delete(); // if you want to delete purchases

            // Delete files from storage
            if ($recipe->image_path && file_exists(public_path('storage/' . $recipe->image_path))) {
                unlink(public_path('storage/' . $recipe->image_path));
            }
            if ($recipe->video_path && file_exists(public_path('storage/' . $recipe->video_path))) {
                unlink(public_path('storage/' . $recipe->video_path));
            }
            if ($recipe->gCash_path && file_exists(public_path('storage/' . $recipe->gCash_path))) {
                unlink(public_path('storage/' . $recipe->gCash_path));
            }
            if ($recipe->receipt_path && file_exists(public_path('storage/' . $recipe->receipt_path))) {
                unlink(public_path('storage/' . $recipe->receipt_path));
            }
        });
    }

}
