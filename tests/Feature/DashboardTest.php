<?php

declare(strict_types=1);

use App\Models\User;

test('guests are redirected to the login page', function () {
    $response = $this->get(route('artists'));
    $response->assertRedirect(route('login'));
});

test('authenticated users can visit the artists', function () {
    $user = User::factory()->create();
    $this->actingAs($user);

    $response = $this->get(route('artists'));
    $response->assertStatus(200);
});
