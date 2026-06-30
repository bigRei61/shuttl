<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Game extends Model
{
    protected $fillable = [
        'event_id', 'format', 'competitive_type',
        'winning_side', 'team1_sets_won', 'team2_sets_won',
        'scheduled_at', 'played_at', 'status',
    ];

    protected $casts = [
        'scheduled_at' => 'datetime',
        'played_at' => 'datetime',
    ];

    public function event() {
        return $this->belongsTo(Event::class);
    }

    public function gamePlayers() {
        return $this->hasMany(GamePlayer::class);
    }

    public function setScores() {
        return $this->hasMany(SetScore::class);
    }

    public function ratingChanges() {
        return $this->hasMany(RatingChange::class);
    }
}