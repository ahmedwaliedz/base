<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class CodeLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_code_login_succeeds_with_valid_code(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->addMinutes(10),
            'activation_attempts' => 0,
            'is_active' => true,
            'name' => 'John Doe',
            'profile_completed' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'phone',
                        'name',
                        'profile_completed',
                    ],
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Logged in successfully',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'name' => $user->name,
                        'profile_completed' => true,
                    ],
                ],
            ]);

        $this->assertNotEmpty($response->json('data.token'));
        
        // Verify code is cleared after successful login
        $user->refresh();
        $this->assertNull($user->activation_code);
        $this->assertNull($user->activation_expires_at);
        $this->assertEquals(0, $user->activation_attempts);
    }

    public function test_code_login_with_incomplete_profile(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->addMinutes(10),
            'activation_attempts' => 0,
            'is_active' => true,
            'name' => null, // Incomplete profile
            'profile_completed' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'token',
                    'user' => [
                        'id',
                        'phone',
                        'name',
                        'profile_completed',
                    ],
                    'profile_next_steps',
                ],
                'meta' => [
                    'requires_profile_completion',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'Logged in; profile incomplete',
                'data' => [
                    'user' => [
                        'id' => $user->id,
                        'phone' => $user->phone,
                        'name' => null,
                        'profile_completed' => false,
                    ],
                    'profile_next_steps' => ['name'],
                ],
                'meta' => [
                    'requires_profile_completion' => true,
                ],
            ]);
    }

    public function test_code_login_fails_with_invalid_code(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->addMinutes(10),
            'activation_attempts' => 0,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '+1234567890',
            'code' => '654321', // Wrong code
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid activation code.',
            ]);

        // Verify attempts are incremented
        $user->refresh();
        $this->assertEquals(1, $user->activation_attempts);
    }

    public function test_code_login_fails_with_expired_code(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->subMinutes(1), // Expired
            'activation_attempts' => 0,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Activation code has expired.',
            ]);
    }

    public function test_code_login_fails_with_max_attempts_exceeded(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'activation_code' => Hash::make('123456'),
            'activation_expires_at' => CarbonImmutable::now()->addMinutes(10),
            'activation_attempts' => 5, // Max attempts reached
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Too many failed attempts. Please request a new code.',
            ]);
    }

    public function test_code_login_fails_with_no_code(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'activation_code' => null,
            'activation_expires_at' => null,
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '+1234567890',
            'code' => '123456',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'No valid activation code found.',
            ]);
    }

    public function test_code_login_validation_errors(): void
    {
        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => 'invalid-phone',
            'code' => '123', // Too short
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'phone',
                    'code',
                ],
            ]);
    }

    public function test_request_code_endpoint(): void
    {
        $response = $this->postJson('/api/v1/auth/request-code', [
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(204);

        // Verify user was created
        $this->assertDatabaseHas('users', [
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
        ]);

        $user = User::where('phone', '+1234567890')->first();
        $this->assertNotNull($user->activation_code);
        $this->assertNotNull($user->activation_expires_at);
    }

    public function test_request_code_with_existing_user(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/request-code', [
            'phone' => '+1234567890',
        ]);

        $response->assertStatus(204);

        // Verify activation code was updated
        $user->refresh();
        $this->assertNotNull($user->activation_code);
        $this->assertNotNull($user->activation_expires_at);
    }
}
