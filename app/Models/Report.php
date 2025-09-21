<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    protected $table = 'reports';
    protected $fillable = [
        'reporterID',
        'reportedUserID',
        'reportedRecipeID',
        'reason',
        'status',
    ];

    public function reporter()
    {
        return $this->belongsTo(User::class, 'reporterID');
    }
    public function reportedUser()
    {
        return $this->belongsTo(User::class, 'reportedUserID');
    }
    public function reportedRecipe()
    {
        return $this->belongsTo(Recipe::class, 'reportedRecipeID');
    }
}
