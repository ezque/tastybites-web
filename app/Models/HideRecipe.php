<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HideRecipe extends Model
{
    protected $table = 'hide_recipe';

    protected $fillable = [
        'userID',
        'recipeID',
        'is_hidden',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'userID');
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipeID');
    }
}
