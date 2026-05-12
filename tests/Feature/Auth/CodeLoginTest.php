<?php

declare(strict_types=1);

namespace Tests\Feature\Auth;

use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Models\Otp;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CodeLoginTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        \App\Models\Country::create(['code' => '20', 'is_active' => true]);
        \App\Models\Country::create(['code' => '966', 'is_active' => true]);
    }

    public function test_code_login_succeeds_with_valid_code(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
            'name' => 'John Doe',
            'is_complete_info' => true,
        ]);

        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '20',
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
                        'is_complete_info' => true,
                    ],
                ],
            ]);

        $this->assertNotEmpty($response->json('data.token'));

        $user->refresh();
        $this->assertTrue($user->is_active);

        $otp->refresh();
        $this->assertEquals(OtpStatus::FINISHED, $otp->status);
    }

    public function test_code_login_with_incomplete_profile(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
            'name' => 'Test User',
            'is_complete_info' => false,
        ]);

        Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '20',
            'code' => '123456',
        ]);

        $response->assertStatus(200);
    }

    public function test_code_login_fails_with_invalid_code(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '20',
            'code' => '654321',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid activation code.',
            ]);

        $user->refresh();
        $otp = $user->otps()->first();
        $this->assertEquals(1, $otp->tries);
    }

    public function test_code_login_fails_with_expired_code(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->subMinutes(1),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '20',
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
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 5,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '20',
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
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '20',
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
            'country_code' => '20',
            'code' => '123',
        ]);

        $response->assertStatus(422);
    }

    public function test_code_login_fails_with_different_country_code(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        $response = $this->postJson('/api/v1/auth/login-code', [
            'phone' => '1234567890',
            'country_code' => '966',
            'code' => '123456',
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid credentials.',
            ]);
    }

    public function test_request_code_endpoint(): void
    {
        $response = $this->postJson('/api/v1/auth/request-code', [
            'phone' => '1234567890',
            'country_code' => '20',
        ]);

        $response->assertStatus(204);

        $user = User::where('phone', '1234567890')->where('country_code', '20')->first();
        $this->assertNotNull($user);

        $otp = $user->otps()->where('type', OtpType::ACTIVATE)->first();
        $this->assertNotNull($otp);
        $this->assertNotEmpty($otp->verification_code);
    }

    public function test_request_code_with_existing_user(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        $response = $this->postJson('/api/v1/auth/request-code', [
            'phone' => '1234567890',
            'country_code' => '20',
        ]);

        $response->assertStatus(204);

        $user->refresh();
        $otp = $user->otps()->where('type', OtpType::ACTIVATE)->first();
        $this->assertNotNull($otp);
    }

    public function test_request_code_fails_during_cooldown(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);

        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);
        $otp->created_at = now()->subSeconds(30);
        $otp->save();

        $response = $this->postJson('/api/v1/auth/request-code', [
            'phone' => '1234567890',
            'country_code' => '20',
        ]);

        $response->assertStatus(429)
            ->assertJson([
                'status' => 'error',
                'message' => 'Too many requests. Please wait before requesting a new code.',
            ]);
    }
}