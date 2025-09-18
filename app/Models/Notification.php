<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table = 'notification';

    protected $fillable = [
        'userID',
        'message',
        'status',
        'type',
    ];
    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}
