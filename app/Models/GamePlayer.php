<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GamePlayer extends Model
{
    protected $fillable = ['game_id', 'player_id', 'team_side', 'role'];

    public function game() {
        return $this->belongsTo(Game::class);
    }

    public function player() {
        return $this->belongsTo(User::class, 'player_id');
    }
}