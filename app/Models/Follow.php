<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Follow extends Model
{
    protected $table = 'follows';
    protected $fillable = [
        'followerID',
        'followedID',
        'status',
    ];

    // The user who is following
    public function follower()
    {
        return $this->belongsTo(User::class, 'followerID');
    }

    // The user being followed
    public function followed()
    {
        return $this->belongsTo(User::class, 'followedID');
    }
}
