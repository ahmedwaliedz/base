<?php

declare(strict_types=1);

namespace Tests\Feature\Api\Auth;

use App\Models\Admin;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Country\CountrySeeder::class);
    }

    public function test_admin_login_fails_with_invalid_credentials(): void
    {
        Admin::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('Password123!'),
        ]);

        $response = $this->postJson('/admin/login', [
            'email' => 'admin@test.com',
            'password' => 'WrongPass123!',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);
    }

    public function test_admin_login_fails_missing_fields(): void
    {
        $response = $this->postJson('/admin/login', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'message',
                'errors',
            ]);
    }

    public function test_user_login_with_password_succeeds(): void
    {
        $user = \App\Models\User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '966',
            'phone_normalized' => '9661234567890',
            'password' => 'password123',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => '1234567890',
            'country_code' => '966',
            'password' => 'password123',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => [
                    'id',
                    'phone',
                    'name',
                    'token',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'api/auth.logged_in_successfully',
            ]);
    }

    public function test_user_login_with_password_fails_invalid_credentials(): void
    {
        \App\Models\User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '966',
            'phone_normalized' => '9661234567890',
            'password' => 'password123',
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => '1234567890',
            'country_code' => '966',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
            ]);
    }

    public function test_user_login_rate_limiting_applies(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'login_value' => '9999999999',
                'country_code' => '966',
                'password' => 'password',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => '9999999999',
            'country_code' => '966',
            'password' => 'password',
        ]);

        $response->assertStatus(429);
    }

    public function test_login_rate_limiting_applies(): void
    {
        for ($i = 0; $i < 6; $i++) {
            $response = $this->postJson('/api/v1/auth/login', [
                'login_value' => 'nonexistent@test.com',
                'password' => 'password',
            ]);
        }

        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => 'nonexistent@test.com',
            'password' => 'password',
        ]);

        $response->assertStatus(429);
    }
}