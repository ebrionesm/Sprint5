<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Passport\HasApiTokens;

class Player extends Authenticatable
{

    use HasFactory, Notifiable, HasApiTokens/*, HasRoles*/;

    protected $fillable = ["nickname", "email", "password", "role"];
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    /*protected $fillable = [
        'name',
        'email',
        'password',
    ];*/

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
