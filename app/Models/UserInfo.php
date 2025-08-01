<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserInfo extends Model
{
    protected $table = 'user_info';

    protected $fillable = [
        'userID',
        'userName',
        'fullName',
        'gender',
        'profilePath',
        'coverPath',
        'experience',
        'credentials',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
