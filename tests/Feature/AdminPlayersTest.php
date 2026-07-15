<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows player ratings as whole numbers on the admin players page', function () {
    $admin = User::factory()->create(['role' => 'admin']);

    User::factory()->create([
        'name' => 'Rounded Rating Player',
        'role' => 'player',
        'rating_value' => 431.68,
    ]);

    $this->actingAs($admin)
        ->get(route('admin.players'))
        ->assertSuccessful()
        ->assertSee('Rounded Rating Player')
        ->assertSee('432')
        ->assertDontSee('431.68');
});

it('allows admins to deactivate and activate players without hard deleting them', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $player = User::factory()->create([
        'name' => 'Soft Delete Player',
        'role' => 'player',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.players.deactivate', $player))
        ->assertSessionHas('success');

    $deactivatedPlayer = User::withTrashed()->find($player->id);

    expect($deactivatedPlayer)->not->toBeNull()
        ->and($deactivatedPlayer->trashed())->toBeTrue();

    $this->actingAs($admin)
        ->get(route('admin.players'))
        ->assertSuccessful()
        ->assertSee('Soft Delete Player')
        ->assertSee('Deactivated')
        ->assertSee('Activate')
        ->assertSee(route('admin.players.activate', $deactivatedPlayer), false);

    $this->actingAs($admin)
        ->patch(route('admin.players.activate', $deactivatedPlayer))
        ->assertSessionHas('success');

    expect(User::withTrashed()->find($player->id)->trashed())->toBeFalse();
});
