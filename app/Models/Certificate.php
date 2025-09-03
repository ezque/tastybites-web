<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $table = 'certificates';
    protected $fillable = [
        'userID',
        'certificate_path',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'userID', 'id');
    }
}
