<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('the application returns a successful response', function () {
    $response = $this->get('/');

    $response->assertStatus(200);
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
