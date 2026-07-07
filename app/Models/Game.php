<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function gamePlayers(): HasMany
    {
        return $this->hasMany(GamePlayer::class);
    }

    public function setScores(): HasMany
    {
        return $this->hasMany(SetScore::class)->orderBy('set_number');
    }

    public function ratingChanges(): HasMany
    {
        return $this->hasMany(RatingChange::class);
    }
}
