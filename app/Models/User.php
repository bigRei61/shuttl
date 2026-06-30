<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

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

    public function organizedEvents() {
        return $this->hasMany(Event::class, 'organizer_id');
    }

    public function joinedEvents() {
        return $this->belongsToMany(Event::class, 'event_players');
    }

    public function gamePlayers() {
        return $this->hasMany(GamePlayer::class, 'player_id');
    }

    public function ratingChanges() {
        return $this->hasMany(RatingChange::class, 'player_id');
    }

    public function isAdmin() {
        return $this->role === 'admin';
    }
}