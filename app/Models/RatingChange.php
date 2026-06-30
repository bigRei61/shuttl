<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RatingChange extends Model
{
    protected $fillable = ['game_id', 'player_id', 'rating_before', 'rating_after', 'delta'];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function player() {
        return $this->belongsTo(User::class, 'player_id');
    }
}