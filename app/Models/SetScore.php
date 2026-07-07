<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SetScore extends Model
{
    protected $fillable = ['game_id', 'set_number', 'team1_score', 'team2_score'];

    public function game(): BelongsTo
    {
        return $this->belongsTo(Game::class);
    }
}
