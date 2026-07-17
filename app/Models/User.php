<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    public const STARTING_RATING = 400.00;

    public const AVERAGE_RATING = 1200.00;

    public const RATING_SCALE = 400.00;

    public const RATING_K_FACTOR = 32.00;

    public const DEACTIVATED_MESSAGE = 'Account currently cannot be reached';

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
        'rating_value' => 'decimal:2',
        'matches_played' => 'integer',
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
