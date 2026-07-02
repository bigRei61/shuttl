<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows the statistics page for an authenticated user', function () {
    $user = User::create([
        'name' => 'Test Player',
        'email' => 'player@example.com',
        'password' => bcrypt('password'),
        'role' => 'player',
        'rating_value' => 1200,
        'matches_played' => 0,
    ]);

    $this->actingAs($user)
        ->get('/history')
        ->assertOk()
        ->assertSee('Statistics')
        ->assertSee('Matches Played')
        ->assertSee('Recent Matches');
});
