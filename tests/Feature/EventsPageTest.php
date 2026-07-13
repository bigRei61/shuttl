<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('renders the events page successfully', function () {
    $view = view('events.index', ['events' => collect([]), 'casualGames' => collect([])]);
    $html = $view->render();

    expect($html)->toContain('Events')
        ->and($html)->toContain('Filter By')
        ->and($html)->toContain('Date')
        ->and($html)->toContain('Earliest')
        ->and($html)->toContain('Latest')
        ->and($html)->toContain('Type')
        ->and($html)->toContain('Quick Play')
        ->and($html)->toContain('Tournament')
        ->and($html)->toContain('data-events-filter-input')
        ->and($html)->toContain('data-events-filter-clear')
        ->and($html)->toContain('window.history.replaceState')
        ->and($html)->not->toContain('events-filter-apply')
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
        ->assertSee('window.history.replaceState', false)
        ->assertSee('applyEventFilters', false)
        ->assertSee('data-event-slide', false)
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

it('sorts events by latest date when requested', function () {
    $host = User::factory()->create();

    $earliestEvent = createEventsPageEvent($host, 'Earliest Requested Event', 1);
    $middleEvent = createEventsPageEvent($host, 'Middle Requested Event', 2);
    $latestEvent = createEventsPageEvent($host, 'Latest Requested Event', 3);

    $this->actingAs($host)
        ->get(route('events.index', ['date' => 'latest']))
        ->assertSuccessful()
        ->assertViewHas('date', 'latest')
        ->assertSeeInOrder([$latestEvent->name, $middleEvent->name, $earliestEvent->name]);
});

it('preselects quick play type for client-side filtering without removing loaded cards', function () {
    $host = User::factory()->create();

    $quickPlayEvent = createEventsPageEvent($host, 'Quick Play Filter Match', 1, 'quick_play');
    $tournamentEvent = createEventsPageEvent($host, 'Tournament Filter Cup', 2);

    $this->actingAs($host)
        ->get(route('events.index', ['type' => 'quick_play']))
        ->assertSuccessful()
        ->assertViewHas('type', 'quick_play')
        ->assertSee($quickPlayEvent->name)
        ->assertSee($tournamentEvent->name)
        ->assertSee('value="quick_play" data-events-filter-input checked', false)
        ->assertSee('data-event-type="quick_play"', false)
        ->assertSee('data-event-type="tournament"', false)
        ->assertSee('slide.dataset.eventType === type', false);
});

it('preselects combined tournament type filtering with latest date sorting', function () {
    $host = User::factory()->create();

    $olderTournament = createEventsPageEvent($host, 'Older Tournament Filter Cup', 1);
    $newerTournament = createEventsPageEvent($host, 'Newer Tournament Filter Cup', 3);
    $quickPlayEvent = createEventsPageEvent($host, 'Newest Quick Play Filter Match', 4, 'quick_play');

    $this->actingAs($host)
        ->get(route('events.index', ['date' => 'latest', 'type' => 'tournament']))
        ->assertSuccessful()
        ->assertViewHas('date', 'latest')
        ->assertViewHas('type', 'tournament')
        ->assertSee('value="latest" data-events-filter-input checked', false)
        ->assertSee('value="tournament" data-events-filter-input checked', false)
        ->assertSeeInOrder([$quickPlayEvent->name, $newerTournament->name, $olderTournament->name]);
});

it('falls back to earliest unfiltered events for invalid filter values', function () {
    $host = User::factory()->create();

    $earliestEvent = createEventsPageEvent($host, 'Fallback Earliest Event', 1);
    $latestEvent = createEventsPageEvent($host, 'Fallback Latest Event', 2, 'quick_play');

    $this->actingAs($host)
        ->get(route('events.index', ['date' => 'sideways', 'type' => 'badminton']))
        ->assertSuccessful()
        ->assertViewHas('date', 'earliest')
        ->assertViewHas('type', fn ($type) => $type === null)
        ->assertSee($earliestEvent->name)
        ->assertSee($latestEvent->name)
        ->assertSeeInOrder([$earliestEvent->name, $latestEvent->name]);
});

function createEventsPageEvent(User $host, string $name, int $startsInDays, string $type = 'tournament'): Event
{
    return Event::create([
        'organizer_id' => $host->id,
        'name' => $name,
        'type' => $type,
        'location' => 'Court 1',
        'start_date' => now()->addDays($startsInDays)->toDateString(),
        'end_date' => now()->addDays($startsInDays + 1)->toDateString(),
        'status' => 'open',
    ]);
}
