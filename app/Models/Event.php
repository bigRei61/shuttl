<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    protected $fillable = [
        'organizer_id', 'name', 'type',
        'location', 'start_date', 'end_date', 'status',
        'is_featured', 'description', 'max_participants', 'photo_path',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_featured' => 'boolean',
    ];

    public function organizer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function players(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'event_players')
            ->withPivot(['status', 'responded_at'])
            ->withTimestamps();
    }

    public function approvedPlayers(): BelongsToMany
    {
        return $this->players()->wherePivot('status', 'approved');
    }

    public function pendingPlayers(): BelongsToMany
    {
        return $this->players()->wherePivot('status', 'pending');
    }

    public function games(): HasMany
    {
        return $this->hasMany(Game::class);
    }

    public function photoUrl(): string
    {
        if ($this->photo_path) {
            return asset('storage/'.ltrim($this->photo_path, '/'));
        }

        return asset('landing/img/slider-1.png');
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }
}
