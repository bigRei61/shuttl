<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('allows active accounts to log in normally', function () {
    $user = User::factory()->create([
        'email' => 'active@example.com',
        'role' => 'player',
    ]);

    $this->post(route('login.post'), [
        'email' => 'active@example.com',
        'password' => 'password',
    ])
        ->assertRedirect(route('landing'))
        ->assertSessionHasNoErrors();

    $this->assertAuthenticatedAs($user);
});

it('shows the deactivated account prompt when a deactivated user logs in', function () {
    $user = User::factory()->create([
        'email' => 'deactivated@example.com',
        'role' => 'player',
    ]);
    $user->delete();

    $this->from(route('login'))
        ->post(route('login.post'), [
            'email' => 'deactivated@example.com',
            'password' => 'password',
        ])
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors([
            'email' => User::DEACTIVATED_MESSAGE,
        ]);

    $this->assertGuest();
});

it('redirects a deactivated authenticated session to login with the account prompt', function () {
    $user = User::factory()->create(['role' => 'player']);

    $this->actingAs($user);
    $user->delete();

    $this->get(route('events.index'))
        ->assertRedirect(route('login'))
        ->assertSessionHasErrors([
            'email' => User::DEACTIVATED_MESSAGE,
        ]);

    $this->assertGuest();
});
