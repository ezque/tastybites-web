<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    protected $fillable = [
        'userID',
        'recipeID',
        'reaction_type'
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
