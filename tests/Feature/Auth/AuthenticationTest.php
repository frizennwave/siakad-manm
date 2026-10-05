<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('users can authenticate using the login screen', function () {
    $user = User::factory()->create();

    $response = $this->withHeader('Referer', 'http://localhost')
        ->post('/api/login', [
            'email' => $user->email,
            'password' => 'password',
        ]);

    $this->assertAuthenticated();
    $response->assertOk();
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $this->withHeader('Referer', 'http://localhost')
        ->post('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);

    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $token = $user->createToken('auth_token')->plainTextToken;

    $response = $this->withToken($token)
        ->post('/api/logout');

    $response->assertOk();

    $response->assertJson([
        'message' => 'Logged out successfully',
    ]);

    $this->assertDatabaseCount('personal_access_tokens', 0);
});
