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
        ->assertSee('.calendar-event-dot.joined { background:#4EDFCE; }', false)
        ->assertSee('.calendar-event-dot.host { background:#EA7632; }', false)
        ->assertSee('.calendar-event-dot.joined.past { background:#DEF3EE; }', false)
        ->assertSee('.calendar-event-dot.host.past { background:#FFF0E8; }', false)
        ->assertSee('.calendar-grid td { text-align:center; vertical-align:top; padding:8px 0 6px; height:64px; width:14.28%; }', false)
        ->assertSee('.calendar-day { min-height:50px; display:flex; flex-direction:column; align-items:center; justify-content:flex-start; gap:5px; cursor:pointer; }', false)
        ->assertSee('.day-cell { display:inline-flex; align-items:center; justify-content:center; width:34px; height:34px; border-radius:50%; color:#131313; font-size:14px; font-weight:700; transition:all .25s; position:relative; }', false)
        ->assertSee('.day-cell.today, .day-cell.selected { width:30px; height:30px; margin:2px; }', false)
        ->assertSee('.calendar-event-dot { display:block; width:6px; height:6px; border-radius:50%; }', false)
        ->assertSee('.role-pill.joined { background:#4EDFCE; color:#131313; }', false)
        ->assertSee('.day-cell.today { box-shadow:0 0 0 2px #E15554; font-weight:700; }', false)
        ->assertSee('.day-cell.selected { box-shadow:0 0 0 2px #E5E7EB; font-weight:700; z-index:1; }', false)
        ->assertSee('.day-cell.today.selected { box-shadow:0 0 0 2px #E15554; }', false)
        ->assertSee('.calendar-day:hover .day-cell { background:#E5E7EB; color:#131313; }', false)
        ->assertSee('.calendar-day:hover .day-cell.today { background:#E15554; color:#fff; }', false)
        ->assertSee('eventsForDay.slice(0, 3).forEach', false)
        ->assertSee('return a.role === \'host\' ? -1 : 1;', false)
        ->assertSee('dot.className = `calendar-event-dot ${role}`;', false)
        ->assertSee('dot.classList.add(\'past\')', false)
        ->assertDontSee('.day-cell.has-joined-event', false)
        ->assertDontSee('has-past-${dayRole}-event', false)
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
