<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // Satu user punya satu profil
    public function profile()
    {
        return $this->hasOne(UserProfile::class);
    }

    // Shortcut ke rekomendasi lewat profil
    public function recommendations()
    {
        return $this->hasManyThrough(
            Recommendation::class,
            UserProfile::class
        );
    }
}
