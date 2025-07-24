<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Procedure extends Model
{
    protected $fillable = [
        'recipeID',
        'step',
        'instruction',
    ];

    public function recipe(){
        return $this->belongsTo(Recipe::class, 'recipeID');
    }
}
