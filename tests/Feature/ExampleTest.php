<?php

use App\Models\Event;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;

uses(RefreshDatabase::class);

afterEach(function () {
    Carbon::setTestNow();
});

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertSuccessful();
});

test('landing profile dropdown uses a native logout form', function () {
    $user = User::factory()->create();

    $this->actingAs($user)
        ->get('/')
        ->assertSuccessful()
        ->assertSee('header-profile-email', false)
        ->assertSee($user->email)
        ->assertSee('header-profile-logout', false)
        ->assertSee(route('logout'), false)
        ->assertDontSee('header-logout-form" action="'.route('logout').'" method="POST" style="display:none;', false);
});

test('landing featured carousel shows the earliest three active events with truncated titles and card links', function () {
    Carbon::setTestNow('2026-07-14 09:00:00');

    $host = User::factory()->create();
    $viewer = User::factory()->create();
    $longTitle = 'Earliest Active Featured Tournament With A Very Long Title That Should Definitely Use Dots At The End';
    $longDescription = 'This featured tournament description is intentionally long so it should be shortened with dots before it stretches the landing card layout too far.';
    $earliest = createLandingFeaturedEvent($host, $longTitle, '2026-07-13 09:00:00', [
        'description' => $longDescription,
        'start_date' => '2026-07-13',
        'end_date' => '2026-07-15',
        'status' => 'completed',
    ]);
    $secondEarliest = createLandingFeaturedEvent($host, 'Second Earliest Featured Tournament', '2026-07-12 09:00:00', [
        'start_date' => '2026-07-16',
        'end_date' => '2026-07-17',
    ]);
    $thirdEarliest = createLandingFeaturedEvent($host, 'Third Earliest Featured Tournament', '2026-07-11 09:00:00', [
        'start_date' => '2026-07-18',
        'end_date' => '2026-07-19',
    ]);
    $fourthEarliest = createLandingFeaturedEvent($host, 'Fourth Earliest Featured Tournament', '2026-07-10 09:00:00', [
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-21',
    ]);
    $past = createLandingFeaturedEvent($host, 'Past Featured Tournament', '2026-07-14 10:00:00', [
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-02',
    ]);
    $notFeatured = createLandingFeaturedEvent($host, 'Unfeatured Upcoming Tournament', '2026-07-14 12:00:00', [
        'is_featured' => false,
    ]);

    $this->actingAs($viewer)
        ->get('/')
        ->assertSuccessful()
        ->assertViewHas('featured', function ($featured) use ($earliest, $secondEarliest, $thirdEarliest) {
            expect($featured)->toHaveCount(3)
                ->and($featured->pluck('id')->all())->toBe([
                    $earliest->id,
                    $secondEarliest->id,
                    $thirdEarliest->id,
                ]);

            return true;
        })
        ->assertSee(Str::limit($longTitle, 70, '...'))
        ->assertDontSee($longDescription)
        ->assertDontSee(Str::limit($longDescription, 120, '...'))
        ->assertDontSee('featured-description', false)
        ->assertSee('href="'.route('events.show', $earliest).'"', false)
        ->assertDontSee('data-featured-event-url', false)
        ->assertDontSee('window.location.assign(featuredCard.dataset.featuredEventUrl);', false)
        ->assertSee('text-overflow: ellipsis;', false)
        ->assertSee('Request to Join')
        ->assertDontSee('Join Now')
        ->assertSeeInOrder([$earliest->name, $secondEarliest->name, $thirdEarliest->name])
        ->assertDontSee($fourthEarliest->name)
        ->assertDontSee($past->name)
        ->assertDontSee($notFeatured->name);
});

test('landing featured carousel marks hosted events instead of showing a join button', function () {
    Carbon::setTestNow('2026-07-14 09:00:00');

    $host = User::factory()->create();

    createLandingFeaturedEvent($host, 'Hosted Featured Tournament', '2026-07-13 09:00:00');

    $this->actingAs($host)
        ->get('/')
        ->assertSuccessful()
        ->assertSee('<span class="featured-pill">Host</span>', false)
        ->assertDontSee('Join Now')
        ->assertDontSee('Request to Join');
});

function createLandingFeaturedEvent(User $host, string $name, string $createdAt, array $attributes = []): Event
{
    $event = Event::create(array_merge([
        'organizer_id' => $host->id,
        'name' => $name,
        'type' => 'tournament',
        'location' => 'Court 1',
        'start_date' => '2026-07-20',
        'end_date' => '2026-07-21',
        'status' => 'open',
        'is_featured' => true,
    ], $attributes));

    $event->forceFill([
        'created_at' => Carbon::parse($createdAt),
        'updated_at' => Carbon::parse($createdAt),
    ])->save();

    return $event->refresh();
}
