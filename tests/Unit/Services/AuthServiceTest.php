<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Models\Otp;
use App\Models\User;
use App\Services\Auth\AuthService;
use App\Contracts\CodeSenderInterface;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use RefreshDatabase;

    private AuthService $authService;
    private $mockCodeSender;

    protected function setUp(): void
    {
        parent::setUp();

        $this->mockCodeSender = Mockery::mock(CodeSenderInterface::class);
        $this->mockCodeSender->shouldReceive('sendCode')->byDefault();

        $this->authService = new AuthService($this->mockCodeSender);
    }

private function createUserWithPhone(string $phone, bool $isActive = true): User
    {
        $strippedPhone = ltrim($phone, '+');
        $user = User::factory()->create([
            'phone' => $strippedPhone,
            'country_code' => '1',
            'phone_normalized' => '1' . $strippedPhone,
            'is_active' => $isActive,
            'password' => 'password',
        ]);
        return $user->fresh();
    }

    public function test_login_with_password_succeeds(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '1',
            'phone_normalized' => '11234567890',
            'is_active' => true,
            'password' => 'password',
        ]);

        $result = $this->authService->loginWithPassword([
            'login_value' => '1234567890',
            'country_code' => '1',
            'password' => 'password'
        ]);

        $this->assertEquals('success', $result['status']);
        $this->assertEquals($user->id, $result['user']->id);
        $this->assertIsString($result['token']);
    }

    public function test_login_with_password_fails_with_invalid_phone(): void
    {
        $result = $this->authService->loginWithPassword([
            'login_value' => '+19999999999',
            'country_code' => '1',
            'password' => 'password123'
        ]);

        $this->assertEquals('failed', $result['status']);
    }

    public function test_login_with_password_fails_with_invalid_password(): void
    {
        $user = $this->createUserWithPhone('+234567890', true);

        $result = $this->authService->loginWithPassword([
            'login_value' => '+234567890',
            'country_code' => '1',
            'password' => 'wrongpassword'
        ]);

        $this->assertEquals('failed', $result['status']);
    }

    public function test_login_with_code_succeeds(): void
    {
        $user = $this->createUserWithPhone('+1234567890', false);

        $code = '123456';
        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => $code,
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        $result = $this->authService->loginWithCode([
            'phone' => '+1234567890',
            'country_code' => '1',
            'code' => '123456'
        ]);

        $this->assertEquals($user->id, $result['user']->id);
        $this->assertIsString($result['token']);
        $this->assertTrue($result['user']->is_active);

        $otp->refresh();
        $this->assertEquals(OtpStatus::FINISHED, $otp->status);
    }

    public function test_login_with_code_fails_with_invalid_code(): void
    {
        $user = $this->createUserWithPhone('+1234567890', false);

        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        try {
            $this->authService->loginWithCode([
                'phone' => '+1234567890',
                'country_code' => '1',
                'code' => '654321'
            ]);
            $this->fail('Expected AuthenticationException was not thrown');
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            $this->assertStringContainsString('Invalid activation code.', $e->getMessage());
        }

        $user->refresh();
        $otp->refresh();
        $this->assertEquals(1, $otp->tries);
    }

public function test_login_with_code_fails_with_expired_code(): void
    {
        $user = $this->createUserWithPhone('+234567890', false);

        $expiredAt = \Carbon\Carbon::now()->subMinute();

        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => $expiredAt,
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);

        try {
            $this->authService->loginWithCode([
                'phone' => '+234567890',
                'country_code' => '1',
                'code' => '123456'
            ]);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('expired', $e->getMessage());
            return;
        }
        $this->fail('Expected exception was not thrown');
    }

    public function test_login_with_code_fails_with_max_attempts_exceeded(): void
    {
        $user = $this->createUserWithPhone('+234567890', false);

        Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '123456',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 5,
        ]);

        try {
            $this->authService->loginWithCode([
                'phone' => '+234567890',
                'country_code' => '1',
                'code' => '123456'
            ]);
        } catch (\Throwable $e) {
            $this->assertStringContainsString('attempts', $e->getMessage());
            return;
        }
        $this->fail('Expected exception was not thrown');
    }

    public function test_login_with_code_fails_with_no_otp(): void
    {
        $user = $this->createUserWithPhone('+1234567890', false);

        try {
            $this->authService->loginWithCode([
                'phone' => '+1234567890',
                'country_code' => '1',
                'code' => '123456'
            ]);
            $this->fail('Expected AuthenticationException was not thrown');
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            $this->assertStringContainsString('No valid activation code found.', $e->getMessage());
        }
    }

    public function test_request_activation_code_creates_new_user_and_otp(): void
    {
        $this->mockCodeSender->shouldReceive('sendCode')
            ->once()
            ->with('201234567890', Mockery::any())
            ->andReturnNull();

        $this->authService->requestActivationCode('1234567890', '20');

        $user = User::where('phone', '1234567890')->where('country_code', '20')->first();
        $this->assertNotNull($user);
        $this->assertEquals('New User', $user->name);
        $this->assertFalse($user->is_active);
        $this->assertFalse($user->is_complete_info);

        $otp = $user->otps()->where('type', OtpType::ACTIVATE)->first();
        $this->assertNotNull($otp);
        $this->assertEquals(OtpStatus::ACTIVE, $otp->status);
    }

    public function test_request_activation_code_updates_existing_user(): void
    {
        $user = $this->createUserWithPhone('+234567890', false);

        $this->mockCodeSender->shouldReceive('sendCode')
            ->once()
            ->andReturnNull();

        $this->authService->requestActivationCode('234567890', '1');

        $user->refresh();
        $otp = $user->otps()->where('type', OtpType::ACTIVATE)->first();
        $this->assertNotNull($otp);
    }

    public function test_request_activation_code_invalidates_previous_active_otp(): void
    {
        $user = $this->createUserWithPhone('+234567890', false);

        $oldOtp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '111111',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);
        $oldOtp->created_at = now()->subSeconds(70);
        $oldOtp->save();

        $this->mockCodeSender->shouldReceive('sendCode')
            ->once()
            ->andReturnNull();

        $this->authService->requestActivationCode('234567890', '1');

        $otps = $user->otps()->where('type', OtpType::ACTIVATE)->get();
        $this->assertEquals(2, $otps->count());

        $activeOtp = $otps->where('status', OtpStatus::ACTIVE)->first();
        $this->assertNotNull($activeOtp);

        $finishedOtp = $otps->where('status', OtpStatus::FINISHED)->first();
        $this->assertNotNull($finishedOtp);
        $this->assertEquals('111111', $finishedOtp->verification_code);
    }

    public function test_login_with_code_fails_with_different_country_code(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '1',
            'phone_normalized' => '11234567890',
            'is_active' => false,
            'password' => 'password',
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

        try {
            $this->authService->loginWithCode([
                'phone' => '+1234567890',
                'country_code' => '20',
                'code' => '123456'
            ]);
            $this->fail('Expected AuthenticationException was not thrown');
        } catch (\Illuminate\Auth\AuthenticationException $e) {
            $this->assertStringContainsString('Invalid credentials', $e->getMessage());
        }
    }

    public function test_login_with_code_succeeds_using_phone_normalized(): void
    {
        $user = User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '1',
            'phone_normalized' => '11234567890',
            'is_active' => false,
            'password' => 'password',
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

        $result = $this->authService->loginWithCode([
            'phone' => '+1234567890',
            'country_code' => '1',
            'code' => '123456'
        ]);

        $this->assertEquals($user->id, $result['user']->id);
    }

    public function test_request_activation_code_fails_during_cooldown(): void
    {
        $user = $this->createUserWithPhone('+234567890', false);

        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '111111',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);
        $otp->created_at = now()->subSeconds(30);
        $otp->save();

        $this->mockCodeSender->shouldNotReceive('sendCode');

        try {
            $this->authService->requestActivationCode('234567890', '1');
            $this->fail('Expected ThrottleRequestsException was not thrown');
        } catch (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            $this->assertStringContainsString('Too many requests', $e->getMessage());
        }
    }

    public function test_request_activation_code_succeeds_after_cooldown(): void
    {
        $user = $this->createUserWithPhone('+234567890', false);

        $otp = Otp::create([
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'verification_code' => '111111',
            'verification_code_expire_at' => now()->addMinutes(10),
            'type' => OtpType::ACTIVATE,
            'status' => OtpStatus::ACTIVE,
            'tries' => 0,
        ]);
        $otp->created_at = now()->subSeconds(70);
        $otp->save();

        $this->mockCodeSender->shouldReceive('sendCode')
            ->once()
            ->andReturnNull();

        $this->authService->requestActivationCode('234567890', '1');

        $otps = $user->otps()->where('type', OtpType::ACTIVATE)->get();
        $this->assertEquals(2, $otps->count());
    }
}