<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name', 'email', 'password', 'phone',
        'gender', 'date_of_birth', 'role',
        'rating_value', 'matches_played',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'date_of_birth' => 'date',
        'password' => 'hashed',
    ];

    public function organizedEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function joinedEvents(): BelongsToMany
    {
        return $this->belongsToMany(Event::class, 'event_players')
            ->withPivot(['status', 'responded_at'])
            ->withTimestamps();
    }

    public function approvedEvents(): BelongsToMany
    {
        return $this->joinedEvents()->wherePivot('status', 'approved');
    }

    public function gamePlayers(): HasMany
    {
        return $this->hasMany(GamePlayer::class, 'player_id');
    }

    public function ratingChanges(): HasMany
    {
        return $this->hasMany(RatingChange::class, 'player_id');
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
