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

it('renders active events in three-card client pages', function () {
    $host = User::factory()->create();

    $firstEvent = createEventsPageEvent($host, 'First Event', 5);
    $secondEvent = createEventsPageEvent($host, 'Second Event', 4);
    $thirdEvent = createEventsPageEvent($host, 'Third Event', 3);
    $fourthEvent = createEventsPageEvent($host, 'Fourth Event', 2);

    $this->actingAs($host)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertSee($firstEvent->name)
        ->assertSee($secondEvent->name)
        ->assertSee($thirdEvent->name)
        ->assertSee($fourthEvent->name)
        ->assertSeeInOrder([$fourthEvent->name, $thirdEvent->name, $secondEvent->name, $firstEvent->name])
        ->assertSee('data-page-count="2"', false)
        ->assertSee('events-pages-track', false)
        ->assertSee('events-page-slide', false)
        ->assertSee('pagination-dot', false)
        ->assertSee('pagination-side pagination-next', false)
        ->assertSee('data-client-pagination="next"', false)
        ->assertSee('data-client-dots', false)
        ->assertDontSee('?page=2', false)
        ->assertDontSee('data-pagination-url', false)
        ->assertDontSee('pagination-dot" href', false)
        ->assertDontSee('pagination-side pagination-next" href', false)
        ->assertDontSee('fetch(', false)
        ->assertDontSee('window.history.pushState', false)
        ->assertDontSee('window.location.href = url;', false)
        ->assertSee('profile-icon-button', false)
        ->assertSee('header-profile-email', false)
        ->assertSee('header-profile-logout', false)
        ->assertSee(route('logout'), false)
        ->assertSee($host->email)
        ->assertSee('events-page-fragment', false)
        ->assertSee('data-event-transition-link', false)
        ->assertDontSee('X-Requested-With', false);
});

it('compresses event client pagination after four numbered circles', function () {
    $host = User::factory()->create();

    foreach (range(1, 13) as $startsInDays) {
        createEventsPageEvent($host, "Paged Event {$startsInDays}", $startsInDays);
    }

    $this->actingAs($host)
        ->get(route('events.index'))
        ->assertSuccessful()
        ->assertSee('data-page-count="5"', false)
        ->assertSee('data-client-dots', false)
        ->assertDontSee('?page=2', false)
        ->assertDontSee('data-pagination-url', false)
        ->assertSee('pagination-ellipsis', false);
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
