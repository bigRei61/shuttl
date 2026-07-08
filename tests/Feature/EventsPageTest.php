<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the events page successfully', function () {
    $view = view('events.index', ['events' => collect([]), 'casualGames' => collect([])]);
    $html = $view->render();

    expect($html)->toContain('Events')
        ->and($html)->toContain('Casual Matches');
});

it('paginates active events three per page', function () {
    $host = User::factory()->create();

    $firstEvent = createEventsPageEvent($host, 'First Event', 5);
    $secondEvent = createEventsPageEvent($host, 'Second Event', 4);
    $thirdEvent = createEventsPageEvent($host, 'Third Event', 3);
    $fourthEvent = createEventsPageEvent($host, 'Fourth Event', 2);

    $this->actingAs($host)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertSee($secondEvent->name)
        ->assertSee($thirdEvent->name)
        ->assertSee($fourthEvent->name)
        ->assertSeeInOrder([$fourthEvent->name, $thirdEvent->name, $secondEvent->name])
        ->assertDontSee($firstEvent->name)
        ->assertSee('?page=2', false);

    $this->actingAs($host)
        ->get(route('events.index', ['page' => 2]))
        ->assertSuccessful()
        ->assertSee($firstEvent->name)
        ->assertDontSee($fourthEvent->name);
});

function createEventsPageEvent(User $host, string $name, int $startsInDays): Event
{
    return Event::create([
        'organizer_id' => $host->id,
        'name' => $name,
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => now()->addDays($startsInDays)->toDateString(),
        'end_date' => now()->addDays($startsInDays + 1)->toDateString(),
        'status' => 'open',
    ]);
}
