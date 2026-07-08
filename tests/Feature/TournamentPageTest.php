<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows active joined and hosted events for players', function () {
    $player = User::factory()->create();
    $approvedTournament = createTournament([
        'name' => 'Approved Cup',
    ]);
    $hostedTournament = createTournament([
        'organizer_id' => $player->id,
        'name' => 'Hosted Cup',
    ]);
    $pendingTournament = createTournament([
        'name' => 'Pending Cup',
    ]);
    $rejectedTournament = createTournament([
        'name' => 'Rejected Cup',
    ]);
    $unjoinedTournament = createTournament([
        'name' => 'Open Cup',
    ]);
    $approvedQuickPlay = createTournament([
        'name' => 'Approved Quick Play',
        'type' => 'quick_play',
    ]);
    $completedEvent = createTournament([
        'name' => 'Completed Cup',
        'status' => 'completed',
    ]);
    $pastEvent = createTournament([
        'name' => 'Past Cup',
        'start_date' => now()->subDays(3)->toDateString(),
        'end_date' => now()->subDay()->toDateString(),
    ]);

    $approvedTournament->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pendingTournament->players()->attach($player->id, ['status' => 'pending']);
    $rejectedTournament->players()->attach($player->id, ['status' => 'rejected', 'responded_at' => now()]);
    $approvedQuickPlay->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $completedEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    $pastEvent->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);

    $this->actingAs($player)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('Approved Cup')
        ->assertSee(route('events.show', $approvedTournament), false)
        ->assertSee('Hosted Cup')
        ->assertSee(route('events.show', $hostedTournament), false)
        ->assertSee('Approved Quick Play')
        ->assertSee(route('events.show', $approvedQuickPlay), false)
        ->assertDontSee('Pending Cup')
        ->assertDontSee('Rejected Cup')
        ->assertDontSee('Open Cup')
        ->assertDontSee('Completed Cup')
        ->assertDontSee('Past Cup');

    expect($hostedTournament->exists)->toBeTrue()
        ->and($unjoinedTournament->exists)->toBeTrue();
});

it('shows all active events to admins', function () {
    $admin = User::factory()->create(['role' => 'admin']);
    $tournament = createTournament(['name' => 'Admin Visible Cup']);
    $quickPlay = createTournament([
        'name' => 'Admin Visible Quick Play',
        'type' => 'quick_play',
    ]);
    $completedEvent = createTournament([
        'name' => 'Admin Hidden Completed Cup',
        'status' => 'completed',
    ]);
    $pastEvent = createTournament([
        'name' => 'Admin Hidden Past Cup',
        'start_date' => now()->subDays(3)->toDateString(),
        'end_date' => now()->subDay()->toDateString(),
    ]);

    $this->actingAs($admin)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('Admin Visible Cup')
        ->assertSee('Admin Visible Quick Play')
        ->assertDontSee('Admin Hidden Completed Cup')
        ->assertDontSee('Admin Hidden Past Cup');

    expect($quickPlay->exists)->toBeTrue()
        ->and($tournament->exists)->toBeTrue()
        ->and($completedEvent->exists)->toBeTrue()
        ->and($pastEvent->exists)->toBeTrue();
});

it('renders tournament page events in three-card client pages', function () {
    $player = User::factory()->create();

    $firstEvent = createTournament([
        'name' => 'First Tournament Page Event',
        'start_date' => now()->addDays(5)->toDateString(),
        'end_date' => now()->addDays(6)->toDateString(),
    ]);
    $secondEvent = createTournament([
        'name' => 'Second Tournament Page Event',
        'start_date' => now()->addDays(4)->toDateString(),
        'end_date' => now()->addDays(5)->toDateString(),
    ]);
    $thirdEvent = createTournament([
        'name' => 'Third Tournament Page Event',
        'start_date' => now()->addDays(3)->toDateString(),
        'end_date' => now()->addDays(4)->toDateString(),
    ]);
    $fourthEvent = createTournament([
        'name' => 'Fourth Tournament Page Event',
        'start_date' => now()->addDays(2)->toDateString(),
        'end_date' => now()->addDays(3)->toDateString(),
    ]);

    collect([$firstEvent, $secondEvent, $thirdEvent, $fourthEvent])->each(
        fn (Event $event) => $event->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()])
    );

    $this->actingAs($player)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee($firstEvent->name)
        ->assertSee($secondEvent->name)
        ->assertSee($thirdEvent->name)
        ->assertSee($fourthEvent->name)
        ->assertSeeInOrder([$fourthEvent->name, $thirdEvent->name, $secondEvent->name, $firstEvent->name])
        ->assertSee('data-page-count="2"', false)
        ->assertSee('tp-pages-track', false)
        ->assertSee('tp-page-slide', false)
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
        ->assertSee($player->email)
        ->assertSee('tournament-page-fragment', false)
        ->assertSee('data-event-transition-link', false)
        ->assertDontSee('X-Requested-With', false);
});

it('compresses tournament client pagination after four numbered circles', function () {
    $player = User::factory()->create();

    foreach (range(1, 13) as $startsInDays) {
        $event = createTournament([
            'name' => "Paged Tournament {$startsInDays}",
            'start_date' => now()->addDays($startsInDays)->toDateString(),
            'end_date' => now()->addDays($startsInDays + 1)->toDateString(),
        ]);

        $event->players()->attach($player->id, ['status' => 'approved', 'responded_at' => now()]);
    }

    $this->actingAs($player)
        ->get(route('tournaments'))
        ->assertSuccessful()
        ->assertSee('data-page-count="5"', false)
        ->assertSee('data-client-dots', false)
        ->assertDontSee('?page=2', false)
        ->assertDontSee('data-pagination-url', false)
        ->assertSee('pagination-ellipsis', false);
});

function createTournament(array $attributes = []): Event
{
    return Event::create(array_merge([
        'organizer_id' => User::factory()->create()->id,
        'name' => 'Tournament',
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => now()->addDay()->toDateString(),
        'end_date' => now()->addDays(2)->toDateString(),
        'status' => 'open',
    ], $attributes));
}
