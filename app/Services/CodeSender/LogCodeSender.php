<?php

declare(strict_types=1);

namespace App\Services\CodeSender;

use App\Contracts\CodeSenderInterface;
use Illuminate\Support\Facades\Log;

class LogCodeSender implements CodeSenderInterface
{
    public function sendCode(string $phone, string $code): void
    {
        Log::info('Activation code sent', [
            'phone' => $phone,
            'code' => $code,
            'message' => "Your activation code is: {$code}. Valid for " . config('auth_codes.ttl_minutes') . " minutes.",
        ]);
        
        // In production, replace this with actual SMS gateway implementation
        // Example: $this->smsGateway->send($phone, "Your activation code is: {$code}");
    }
}
