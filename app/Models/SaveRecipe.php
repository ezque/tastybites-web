<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaveRecipe extends Model
{
    protected $table = 'save_recipes';
    protected $fillable = [
        'userID',
        'recipeID',
        'save_status',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }

    public function recipe()
    {
        return $this->belongsTo(Recipe::class, 'recipeID', 'id');
    }
}
