<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Contracts\CodeSenderInterface;
use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class PasswordLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed(\Database\Seeders\Country\CountrySeeder::class);
    }

    public function test_password_login_succeeds_with_valid_credentials(): void
    {
        $user = User::factory()->create([
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
                    'is_complete_info',
                ],
            ])
            ->assertJson([
                'status' => 'success',
                'message' => 'api/auth.logged_in_successfully',
                'data' => [
                    'id' => $user->id,
                ],
            ]);
    }

    public function test_password_login_fails_with_invalid_phone(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => '9999999999',
            'country_code' => '966',
            'password' => 'password123',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_password_login_fails_with_invalid_password(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '966',
            'phone_normalized' => '9661234567890',
            'password' => 'password123',
            'is_active' => true,
        ]);

        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => '1234567890',
            'country_code' => '966',
            'password' => 'wrongpassword',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials',
            ]);
    }

    public function test_password_login_fails_with_inactive_user(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '966',
            'phone_normalized' => '9661234567890',
            'password' => 'password123',
            'is_active' => false,
        ]);

        $this->mock(CodeSenderInterface::class)
            ->shouldReceive('sendCode')
            ->once()
            ->with('9661234567890', Mockery::pattern('/^\d{6}$/'));

        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => '1234567890',
            'country_code' => '966',
            'password' => 'password123',
        ]);

        $response->assertStatus(203)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['token'],
            ]);

        $otp = $user->otps()->where('type', OtpType::ACTIVATE)->first();

        $this->assertNotNull($otp);
        $this->assertEquals(OtpStatus::ACTIVE, $otp->status);
        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp->verification_code);

        $loginCodeResponse = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '966',
            'code' => $otp->verification_code,
        ]);

        $loginCodeResponse->assertStatus(200);

        $user->refresh();
        $this->assertTrue($user->is_active);
    }

    public function test_password_login_validation_errors(): void
    {
        $response = $this->postJson('/api/v1/auth/login', [
            'login_value' => 'invalid',
            'country_code' => '966',
            'password' => '123',
        ]);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'login_value',
                ],
            ]);
    }

    public function test_password_login_requires_login_value_and_password(): void
    {
        $response = $this->postJson('/api/v1/auth/login', []);

        $response->assertStatus(422)
            ->assertJsonStructure([
                'status',
                'message',
                'errors' => [
                    'login_value',
                ],
            ]);
    }
}
