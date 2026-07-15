<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

it('shows active event lifecycle statuses as read only and sorts by status before featured and earliest dates', function () {
    Carbon::setTestNow('2026-07-15 09:00:00');

    $admin = User::factory()->create(['role' => 'admin']);
    $host = User::factory()->create(['role' => 'player']);

    $featuredOpenEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Readonly Featured Open Cup',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-21',
        'status' => 'completed',
        'is_featured' => true,
    ]);
    $openEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Readonly Open Cup',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => '2026-07-18',
        'end_date' => '2026-07-19',
        'status' => 'completed',
    ]);
    $laterOpenEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Readonly Later Open Cup',
        'type' => 'tournament',
        'location' => 'Court 5',
        'start_date' => '2026-07-22',
        'end_date' => '2026-07-23',
        'status' => 'ongoing',
    ]);
    $ongoingEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Readonly Ongoing Cup',
        'type' => 'tournament',
        'location' => 'Court 3',
        'start_date' => '2026-07-14',
        'end_date' => '2026-07-16',
        'status' => 'open',
    ]);
    $closedEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Readonly Closed Cup',
        'type' => 'tournament',
        'location' => 'Court 4',
        'start_date' => '2026-07-10',
        'end_date' => '2026-07-11',
        'status' => 'ongoing',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.events'))
        ->assertSuccessful()
        ->assertSeeInOrder([
            $ongoingEvent->name,
            'Ongoing',
            $featuredOpenEvent->name,
            'Featured',
            'Open',
            $openEvent->name,
            'Open',
            $laterOpenEvent->name,
            'Open',
        ])
        ->assertDontSee($closedEvent->name)
        ->assertSee('Ongoing and Open')
        ->assertSee('bg-yellow-950 text-yellow-300', false)
        ->assertSee('bg-green-950 text-green-300', false)
        ->assertDontSee('name="status"', false)
        ->assertDontSee('admin.events.status');
});

it('filters admin events by derived lifecycle status and shows a clear search button', function () {
    Carbon::setTestNow('2026-07-15 09:00:00');

    $admin = User::factory()->create(['role' => 'admin']);
    $host = User::factory()->create(['role' => 'player']);

    $openEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Filter Open Cup',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-21',
        'status' => 'completed',
    ]);
    $ongoingEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Filter Ongoing Cup',
        'type' => 'tournament',
        'location' => 'Court 3',
        'start_date' => '2026-07-14',
        'end_date' => '2026-07-16',
        'status' => 'open',
    ]);
    $closedEvent = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Filter Closed Cup',
        'type' => 'tournament',
        'location' => 'Court 4',
        'start_date' => '2026-07-10',
        'end_date' => '2026-07-11',
        'status' => 'ongoing',
    ]);

    $this->actingAs($admin)
        ->get(route('admin.events'))
        ->assertSuccessful()
        ->assertSee($ongoingEvent->name)
        ->assertSee($openEvent->name)
        ->assertDontSee($closedEvent->name)
        ->assertSeeInOrder([
            'Ongoing and Open',
            'Ongoing',
            'Open',
            'Closed',
        ]);

    $this->actingAs($admin)
        ->get(route('admin.events', ['lifecycle' => 'ongoing']))
        ->assertSuccessful()
        ->assertSee($ongoingEvent->name)
        ->assertDontSee($openEvent->name)
        ->assertDontSee($closedEvent->name)
        ->assertSee('name="lifecycle"', false);

    $this->actingAs($admin)
        ->get(route('admin.events', ['lifecycle' => 'open']))
        ->assertSuccessful()
        ->assertSee($openEvent->name)
        ->assertDontSee($ongoingEvent->name)
        ->assertDontSee($closedEvent->name);

    $this->actingAs($admin)
        ->get(route('admin.events', ['lifecycle' => 'closed']))
        ->assertSuccessful()
        ->assertSee($closedEvent->name)
        ->assertSee('Activate')
        ->assertSee('bg-red-950 text-red-300', false)
        ->assertSee(route('admin.events.activate', $closedEvent), false)
        ->assertDontSee(route('admin.events.deactivate', $closedEvent), false)
        ->assertDontSee($ongoingEvent->name)
        ->assertDontSee($openEvent->name);

    $this->actingAs($admin)
        ->patch(route('admin.events.activate', $closedEvent))
        ->assertSessionMissing('success');

    expect($closedEvent->fresh()->end_date->toDateString())->toBe('2026-07-15');

    $this->actingAs($admin)
        ->get(route('admin.events'))
        ->assertSuccessful()
        ->assertSee($closedEvent->name)
        ->assertSee('Ongoing');

    $this->actingAs($admin)
        ->get(route('admin.events', [
            'search' => 'Filter',
            'type' => 'tournament',
            'lifecycle' => 'open',
        ]))
        ->assertSuccessful()
        ->assertSee('aria-label="Clear search"', false)
        ->assertSee('type=tournament', false)
        ->assertSee('lifecycle=open', false)
        ->assertDontSee('search=Filter', false);
});

it('allows admins to deactivate and activate events without hard deleting them', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $host = User::factory()->create(['role' => 'player']);
    $viewer = User::factory()->create(['role' => 'player']);
    $event = Event::create([
        'organizer_id' => $host->id,
        'name' => 'Soft Delete Cup',
        'type' => 'tournament',
        'location' => 'Court 4',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
        'status' => 'open',
    ]);

    $this->actingAs($admin)
        ->patch(route('admin.events.deactivate', $event))
        ->assertSessionMissing('success');

    $deactivatedEvent = Event::withTrashed()->find($event->id);

    expect($deactivatedEvent)->not->toBeNull()
        ->and($deactivatedEvent->trashed())->toBeTrue();

    $this->actingAs($viewer)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertDontSee('Soft Delete Cup');

    $this->actingAs($admin)
        ->get(route('admin.events'))
        ->assertSuccessful()
        ->assertSee('Soft Delete Cup')
        ->assertSee('Open')
        ->assertSee('Activate')
        ->assertSee(route('admin.events.activate', $deactivatedEvent), false);

    $this->actingAs($admin)
        ->patch(route('admin.events.activate', $deactivatedEvent))
        ->assertSessionMissing('success');

    expect(Event::withTrashed()->find($event->id)->trashed())->toBeFalse();
});

it('counts only activated events and ongoing events on the admin dashboard', function () {
    Carbon::setTestNow('2026-07-15 09:00:00');

    $admin = User::factory()->create(['role' => 'admin']);
    $host = User::factory()->create(['role' => 'player']);

    Event::create([
        'organizer_id' => $host->id,
        'name' => 'Dashboard Active Cup',
        'type' => 'tournament',
        'location' => 'Court 2',
        'start_date' => '2026-07-15',
        'end_date' => '2026-07-16',
        'status' => 'completed',
    ]);
    Event::create([
        'organizer_id' => $host->id,
        'name' => 'Dashboard Open Cup',
        'type' => 'tournament',
        'location' => 'Court 3',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-21',
        'status' => 'open',
    ]);
    Event::create([
        'organizer_id' => $host->id,
        'name' => 'Dashboard Closed Cup',
        'type' => 'tournament',
        'location' => 'Court 4',
        'start_date' => '2026-07-10',
        'end_date' => '2026-07-11',
        'status' => 'open',
    ]);
    Event::create([
        'organizer_id' => $host->id,
        'name' => 'Dashboard Deactivated Ongoing Cup',
        'type' => 'tournament',
        'location' => 'Court 5',
        'start_date' => '2026-07-14',
        'end_date' => '2026-07-16',
        'status' => 'open',
    ])->delete();

    $this->actingAs($admin)
        ->get(route('admin.dashboard'))
        ->assertSuccessful()
        ->assertViewHas('stats', function ($stats) {
            expect($stats['active_events'])->toBe(3)
                ->and($stats['ongoing_events'])->toBe(1);

            return true;
        })
        ->assertDontSeeText('Total Events')
        ->assertSeeTextInOrder([
            'Active Events',
            '3',
            'Featured Tournaments',
            '0',
            'Ongoing Events',
            '1',
        ]);
});
