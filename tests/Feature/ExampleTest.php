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

test('landing featured carousel shows three newest upcoming events with truncated titles and card links', function () {
    Carbon::setTestNow('2026-07-14 09:00:00');

    $host = User::factory()->create();
    $viewer = User::factory()->create();
    $longTitle = 'Newest Featured Tournament With A Very Long Title That Should Definitely Use Dots At The End';
    $longDescription = 'This featured tournament description is intentionally long so it should be shortened with dots before it stretches the landing card layout too far.';
    $newest = createLandingFeaturedEvent($host, $longTitle, '2026-07-13 09:00:00', [
        'description' => $longDescription,
        'start_date' => '2026-07-18',
        'end_date' => '2026-07-19',
    ]);
    $secondNewest = createLandingFeaturedEvent($host, 'Second Newest Featured Tournament', '2026-07-12 09:00:00');
    $thirdNewest = createLandingFeaturedEvent($host, 'Third Newest Featured Tournament', '2026-07-11 09:00:00');
    $older = createLandingFeaturedEvent($host, 'Older Featured Tournament', '2026-07-10 09:00:00');
    $past = createLandingFeaturedEvent($host, 'Past Featured Tournament', '2026-07-14 10:00:00', [
        'start_date' => '2026-07-01',
        'end_date' => '2026-07-02',
    ]);
    $completed = createLandingFeaturedEvent($host, 'Completed Featured Tournament', '2026-07-14 11:00:00', [
        'status' => 'completed',
    ]);
    $notFeatured = createLandingFeaturedEvent($host, 'Unfeatured Upcoming Tournament', '2026-07-14 12:00:00', [
        'is_featured' => false,
    ]);

    $this->actingAs($viewer)
        ->get('/')
        ->assertSuccessful()
        ->assertViewHas('featured', function ($featured) use ($newest, $secondNewest, $thirdNewest) {
            expect($featured)->toHaveCount(3)
                ->and($featured->pluck('id')->all())->toBe([
                    $newest->id,
                    $secondNewest->id,
                    $thirdNewest->id,
                ]);

            return true;
        })
        ->assertSee(Str::limit($longTitle, 70, '...'))
        ->assertDontSee($longDescription)
        ->assertDontSee(Str::limit($longDescription, 120, '...'))
        ->assertDontSee('featured-description', false)
        ->assertSee('data-featured-event-url="'.route('events.show', $newest).'"', false)
        ->assertSee('window.location.assign(featuredCard.dataset.featuredEventUrl);', false)
        ->assertSee('text-overflow: ellipsis;', false)
        ->assertSeeInOrder([$newest->name, $secondNewest->name, $thirdNewest->name])
        ->assertDontSee($older->name)
        ->assertDontSee($past->name)
        ->assertDontSee($completed->name)
        ->assertDontSee($notFeatured->name);
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
