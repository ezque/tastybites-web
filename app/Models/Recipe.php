<?php

namespace App\Models;

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
    public function procedure()
    {
        return $this->hasMany(Procedure::class, 'recipeID');
    }
}
