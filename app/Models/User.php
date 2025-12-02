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
    public function notifications()
    {
        return $this->hasMany(Notification::class, 'userID', 'id');
    }
    public function reportsMade()
    {
        return $this->hasMany(Report::class, 'reporterID');
    }

    public function reportsReceived()
    {
        return $this->hasMany(Report::class, 'reportedUserID');
    }
    public function ownedRecipes()
    {
        return $this->hasMany(Recipe::class, 'userID');
    }
    // Users I am following
    public function following()
    {
        return $this->hasMany(Follow::class, 'followerID');
    }

    // Users following me
    public function followers()
    {
        return $this->hasMany(Follow::class, 'followedID');
    }
    public function recipePurchases()
    {
        return $this->hasManyThrough(
            Purchase::class,    // Final model
            Recipe::class,      // Middle model
            'userID',           // recipes.userID = chef id
            'recipeID',         // purchases.recipeID = recipe id
            'id',               // users.id
            'id'                // recipes.id
        );
    }



}
