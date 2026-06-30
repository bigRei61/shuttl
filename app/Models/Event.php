<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'name', 'type',
        'location', 'start_date', 'end_date', 'status',
        'is_featured', 'description', 'max_participants',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function organizer() {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function players() {
        return $this->belongsToMany(User::class, 'event_players');
    }

    public function games() {
        return $this->hasMany(Game::class);
    }

    public function scopeFeatured($query) {
        return $query->where('is_featured', true);
    }
}