<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ingredient extends Model
{
    protected $fillable = [
        'recipeID',
        'ingredientName',
        'quantity',
    ];

    public function recipe(){
        return $this->belongsTo(Recipe::class, 'recipeID');
    }
}
