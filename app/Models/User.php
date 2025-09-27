<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'plays_tennis',
        'tennis_level',
        'usual_playing_location',
        'city',
        'state',
        'bio',
        'public_profile',
        'avatar',
        'instagram',
        'whatsapp',
        'slug',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
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
            'plays_tennis' => 'boolean',
            'public_profile' => 'boolean',
        ];
    }

    public function tennisCourts(): HasMany
    {
        return $this->hasMany(TennisCourt::class, 'user_id');
    }

    public function courtReviews(): HasMany
    {
        return $this->hasMany(CourtReview::class);
    }

    /**
     * Usar slug nas URLs (route model binding).
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

