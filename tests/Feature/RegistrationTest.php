<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('registration form marks account fields as required', function () {
    $response = $this->get(route('register'));

    $response->assertSuccessful();

    $dom = new DOMDocument;
    libxml_use_internal_errors(true);
    $dom->loadHTML($response->getContent());
    libxml_clear_errors();

    $xpath = new DOMXPath($dom);

    foreach (['name', 'email', 'password', 'password_confirmation', 'phone', 'gender', 'date_of_birth'] as $field) {
        $input = $xpath->query("//*[@name='{$field}']")->item(0);

        expect($input)->not->toBeNull()
            ->and($input->hasAttribute('required'))->toBeTrue();
    }

    $response
        ->assertSeeText('+63')
        ->assertSee('placeholder="912 345 6789"', false);
});

test('registration requires profile fields for new accounts', function () {
    $this->from(route('register'))
        ->post(route('register.post'), [
            'name' => 'Jane Player',
            'email' => 'jane@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
        ])
        ->assertRedirect(route('register'))
        ->assertInvalid(['phone', 'gender', 'date_of_birth']);

    expect(User::where('email', 'jane@example.com')->exists())->toBeFalse();
});

test('new accounts can register with required profile fields and are logged in', function () {
    $response = $this->post(route('register.post'), [
        'name' => 'Jane Player',
        'email' => 'jane@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
        'phone' => '912 345 6789',
        'gender' => 'female',
        'date_of_birth' => '1995-05-20',
    ]);

    $user = User::where('email', 'jane@example.com')->first();

    $response
        ->assertRedirect(route('landing'))
        ->assertSessionMissing('success');

    $this->assertDatabaseHas('users', [
        'email' => 'jane@example.com',
        'phone' => '+63 912 345 6789',
        'gender' => 'female',
        'role' => 'player',
        'rating_value' => User::STARTING_RATING,
        'matches_played' => 0,
    ]);

    expect($user->date_of_birth->toDateString())->toBe('1995-05-20');

    $this->assertAuthenticatedAs($user);
});
