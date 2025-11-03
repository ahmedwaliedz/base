<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordLoginTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'phone' => '+1234567890',
            'password' => 'password123',
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
                        'is_complete_info',
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
                        'is_complete_info' => $user->is_complete_info,
                    ],
                ],
            ]);

        $this->assertNotEmpty($response->json('data.token'));
    }

    public function test_password_login_fails_with_invalid_phone(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'phone' => '+9999999999',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_password_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'password' => Hash::make('password123'),
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'phone' => '+1234567890',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_password_login_fails_with_inactive_user(): void
    {
        $user = User::factory()->create([
            'phone' => '+1234567890',
            'phone_normalized' => '+1234567890',
            'password' => Hash::make('password123'),
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'phone' => '+1234567890',
            'password' => 'password123',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_password_login_validation_errors(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'phone' => 'invalid-phone',
            'password' => '123', // Too short
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'phone',
                    'password',
                ],
            ]);
    }

    public function test_password_login_requires_phone_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'phone',
                    'password',
                ],
            ]);
    }
}
