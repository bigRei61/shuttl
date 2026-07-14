<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('marks optional fields on the admin featured event form', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    User::factory()->create(['role' => 'player']);

    $this->actingAs($admin)
        ->get(route('admin.featured.create'))
        ->assertSuccessful()
        ->assertSee('Description <span class="text-xs text-gray-500">(Optional)</span>', false)
        ->assertSee('Tournament Photo <span class="text-xs text-gray-500">(Optional)</span>', false);
});

it('allows admins to create featured events without description or photo', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $host = User::factory()->create(['role' => 'player']);

    $this->actingAs($admin)
        ->post(route('admin.featured.store'), [
            'host_id' => $host->id,
            'name' => 'Optional Description Cup',
            'location' => 'Court 1',
            'start_date' => now()->addDay()->toDateString(),
            'end_date' => now()->addDays(2)->toDateString(),
            'max_participants' => 16,
        ])
        ->assertRedirect(route('admin.events'))
        ->assertSessionHas('success');

    $event = Event::where('name', 'Optional Description Cup')->first();

    expect($event)->not->toBeNull()
        ->and($event->description)->toBeNull()
        ->and($event->photo_path)->toBeNull()
        ->and($event->is_featured)->toBeTrue()
        ->and($event->approvedPlayers()->whereKey($host->id)->exists())->toBeTrue();
});
