<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Purchase extends Model
{
    protected $fillable = [
        'userID',
        'recipeID',
        'status',
        'purchase_at',
        'phone_number',
        'amount',
        'reference',
        'proof_path'
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
