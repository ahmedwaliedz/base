<?php

declare(strict_types=1);

namespace Tests\Unit\Services;

use App\Contracts\CodeSenderInterface;
use App\Enums\OtpStatus;
use App\Enums\OtpType;
use App\Models\User;
use App\Services\Otp\OtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Mockery;
use Tests\TestCase;

class OtpServiceTest extends TestCase
{
    use RefreshDatabase;

    private CodeSenderInterface $codeSender;
    private OtpService $otpService;

    protected function setUp(): void
    {
        parent::setUp();

        config()->set('auth_codes.length', 6);
        config()->set('auth_codes.ttl_minutes', 10);

        $this->codeSender = Mockery::mock(CodeSenderInterface::class)->shouldIgnoreMissing();
        $this->otpService = new OtpService($this->codeSender);
    }

    public function test_send_otp_creates_six_digit_plaintext_otp(): void
    {
        $user = $this->createUser();

        $otp = $this->otpService->sendOtp($user, OtpType::ACTIVATE);

        $this->assertMatchesRegularExpression('/^\d{6}$/', $otp->verification_code);
        $this->assertSame($otp->verification_code, $user->otps()->first()->verification_code);

        $this->assertDatabaseHas('otps', [
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'type' => OtpType::ACTIVATE->value,
            'status' => OtpStatus::ACTIVE->value,
            'verification_code' => '123456',
        ]);
    }

    public function test_send_otp_creates_otp_with_phone_fields(): void
    {
        $user = $this->createUser();

        $this->otpService->sendOtp($user, OtpType::ACTIVATE, null, '20');

        $this->assertDatabaseHas('otps', [
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'type' => OtpType::ACTIVATE->value,
            'country_code' => '20',
        ]);
    }

    public function test_verify_otp_succeeds_with_stored_plaintext_code(): void
    {
        $user = $this->createUser();

        $otp = $this->otpService->sendOtp($user, OtpType::ACTIVATE);

        $this->assertTrue($this->otpService->verifyOtp($user, $otp->verification_code, OtpType::ACTIVATE));
        $this->assertEquals(OtpStatus::FINISHED, $otp->fresh()->status);
    }

    public function test_wrong_code_increments_tries(): void
    {
        $user = $this->createUser();

        $otp = $this->otpService->sendOtp($user, OtpType::ACTIVATE);

        $this->assertFalse($this->otpService->verifyOtp($user, '000000', OtpType::ACTIVATE));
        $this->assertSame(1, $otp->fresh()->tries);
    }

    public function test_send_otp_rejects_resend_during_cooldown(): void
    {
        $user = $this->createUser();

        $this->otpService->sendOtp($user, OtpType::ACTIVATE);

        $this->assertDatabaseHas('otps', [
            'otpable_type' => User::class,
            'otpable_id' => $user->id,
            'type' => OtpType::ACTIVATE->value,
            'status' => OtpStatus::ACTIVE->value,
        ]);

        $this->expectException(ThrottleRequestsException::class);

        $this->otpService->sendOtp($user, OtpType::ACTIVATE);
    }

    private function createUser(): User
    {
        return User::factory()->create([
            'phone' => '1234567890',
            'country_code' => '20',
            'phone_normalized' => '201234567890',
            'is_active' => false,
        ]);
    }
}
