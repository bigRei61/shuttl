<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

it('passes active hosted and approved joined events to the calendar', function () {
    Carbon::setTestNow('2026-07-08 09:00:00');

    $player = User::factory()->create();
    $hostedToday = createCalendarEvent([
        'organizer_id' => $player->id,
        'name' => 'Hosted Today',
        'start_date' => '2026-07-08',
        'end_date' => '2026-07-10',
    ]);
    $joinedToday = createCalendarEvent([
        'name' => 'Joined Today',
        'start_date' => '2026-07-07',
        'end_date' => '2026-07-08',
    ]);
    $futureJoined = createCalendarEvent([
        'name' => 'Future Joined',
        'start_date' => '2026-07-09',
        'end_date' => '2026-07-09',
    ]);
    $pendingEvent = createCalendarEvent(['name' => 'Pending Event']);
    $rejectedEvent = createCalendarEvent(['name' => 'Rejected Event']);
    $unjoinedEvent = createCalendarEvent(['name' => 'Unjoined Event']);
    $completedEvent = createCalendarEvent([
        'name' => 'Completed Event',
        'status' => 'completed',
    ]);
    $pastEvent = createCalendarEvent([
        'name' => 'Past Event',
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-02',
    ]);

    $joinedToday->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $futureJoined->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pendingEvent->players()->attach($player->id, ['status' => 'pending']);
    $rejectedEvent->players()->attach($player->id, ['status' => 'rejected', 'responded_at' => now()]);
    $completedEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pastEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);

    $this->actingAs($player)
        ->get(route('calendar'))
        ->assertSuccessful()
        ->assertViewHas('events', function ($events) use ($hostedToday, $joinedToday, $futureJoined, $pendingEvent, $rejectedEvent, $unjoinedEvent, $completedEvent, $pastEvent) {
            $events = collect($events);
            $eventIds = $events->pluck('id')->all();

            expect($eventIds)->toContain($hostedToday->id)
                ->toContain($joinedToday->id)
                ->toContain($futureJoined->id)
                ->not->toContain($pendingEvent->id)
                ->not->toContain($rejectedEvent->id)
                ->not->toContain($unjoinedEvent->id)
                ->not->toContain($completedEvent->id)
                ->not->toContain($pastEvent->id)
                ->and($events->firstWhere('id', $hostedToday->id)['role'])->toBe('host')
                ->and($events->firstWhere('id', $joinedToday->id)['role'])->toBe('joined')
                ->and($events->firstWhere('id', $hostedToday->id)['start_date'])->toBe('2026-07-08')
                ->and($events->firstWhere('id', $hostedToday->id))->not->toHaveKey('time');

            return true;
        });
});

it('renders calendar role colors and future-only upcoming behavior', function () {
    Carbon::setTestNow('2026-07-08 09:00:00');

    $player = User::factory()->create();
    $hostedToday = createCalendarEvent([
        'organizer_id' => $player->id,
        'name' => 'Hosted Today',
        'start_date' => '2026-07-08',
        'end_date' => '2026-07-08',
    ]);
    $joinedTomorrow = createCalendarEvent([
        'name' => 'Joined Tomorrow',
        'start_date' => '2026-07-09',
        'end_date' => '2026-07-09',
    ]);

    $hostedToday->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $joinedTomorrow->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);

    $this->actingAs($player)
        ->get(route('calendar'))
        ->assertSuccessful()
        ->assertSee('.day-cell.has-joined-event { background:#4EDFCE; color:#131313; }', false)
        ->assertSee('.day-cell.has-past-host-event { background:#FFF0E8; color:#131313; }', false)
        ->assertSee('.day-cell.has-past-joined-event { background:#DEF3EE; color:#131313; }', false)
        ->assertSee('.role-pill.joined { background:#4EDFCE; color:#131313; }', false)
        ->assertSee('.day-cell.today { box-shadow:0 0 0 2px #E5E7EB; font-weight:700; }', false)
        ->assertSee('.day-cell.selected { box-shadow:0 0 0 4px #E5E7EB; font-weight:700; z-index:1; }', false)
        ->assertSee('.day-cell.today.selected { box-shadow:0 0 0 4px #E5E7EB; }', false)
        ->assertSee('.day-cell:hover { background:#E5E7EB; color:#131313; }', false)
        ->assertSee('has-past-${dayRole}-event', false)
        ->assertSee('let selectedDate = new Date(today);', false)
        ->assertSee('#4EDFCE')
        ->assertSee('#DEF3EE')
        ->assertSee('#EA7632')
        ->assertDontSee('#D04F6D')
        ->assertSee('date-focus-title')
        ->assertSee('today-focus-list')
        ->assertSee('height:496px', false)
        ->assertSee('overflow:hidden', false)
        ->assertSee('overflow-y:auto', false)
        ->assertDontSee('outline:2px solid #131313', false)
        ->assertSee('getEventsForDate(selectedDate)', false)
        ->assertDontSee('const todayEvents = getEventsForDate(today)', false)
        ->assertSee('eventStart(event) > today', false)
        ->assertSee('some(event => event.role === \'host\')', false)
        ->assertDontSee('6:00pm - 8:00pm')
        ->assertDontSee('Live session')
        ->assertDontSee('selected-date-time');
});

function createCalendarEvent(array $attributes = []): Event
{
    return Event::create(array_merge([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Calendar Event',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-08',
        'end_date' => '2026-07-09',
        'status' => 'open',
    ], $attributes));
}
