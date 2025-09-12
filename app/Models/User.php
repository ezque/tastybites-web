<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable, HasApiTokens;

    protected $table = 'users';

    protected $fillable = [
        'email',
        'status',
        'role',
        'password',
    ];


    protected $hidden = [
        'password',
        'remember_token',
    ];


    public function userInfo() {
        return $this->hasOne(UserInfo::class, 'userID', 'id');
    }
    public function reactions()
    {
        return $this->hasMany(Reaction::class, 'userID');
    }
    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'userID', 'id');
    }
    public function hiddenRecipes()
    {
        return $this->hasMany(HideRecipe::class, 'userID');
    }
    public function savedRecipes()
    {
        return $this->hasMany(SaveRecipe::class, 'userID', 'id');
    }



}
