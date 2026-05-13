<?php

namespace App\Traits\Response;

use App\Enums\OtpType;
use App\Services\Otp\OtpService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Symfony\Component\HttpFoundation\Response;

trait UserStateResponseTrait
{
    use FailResponseTrait;

    /**
     * Get the OTP service instance.
     */
    protected function otpService(): OtpService
    {
        return app(OtpService::class);
    }

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondBlocked(string $message = null): JsonResponse
    {
        // This method is used to respond with a blocked error message.
        return $this->respondWithFail($message ?? __('response.blocked_by_admin'), [], Response::HTTP_LOCKED);
    }

    /**
     * @param string|null $message
     * @param \App\Models\User|null $user
     * @return JsonResponse
     */
    public function respondNotActive(string $message = null, $user = null): JsonResponse
    {
        if ($user) {
            try {
                $this->otpService()->sendOtp($user, OtpType::ACTIVATE);
            } catch (ThrottleRequestsException $e) {
                return $this->respondWithFail($e->getMessage(), [], Response::HTTP_TOO_MANY_REQUESTS);
            }

            $token = $user->createToken('activation', ['activation'], now()->addMinutes(5))->plainTextToken;

            return $this->respondWithFail($message ?? __('response.need_activation'), [
                'token' => $token,
            ], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
        }

        return $this->respondWithFail($message ?? __('response.need_activation'), [], Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }
}
