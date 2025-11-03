<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Contracts\UserRepositoryInterface;
use App\Models\User;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Hash;

class UserRepository implements UserRepositoryInterface
{
    public function findByPhone(string $phone): ?User
    {
        return User::where('phone', $phone)
            ->orWhere('phone_normalized', $phone)
            ->first();
    }

    public function create(array $data): User
    {
        return User::create($data);
    }

    public function updateActivationCode(User $user, string $code, CarbonImmutable $expiresAt): User
    {
        $user->update([
            'activation_code' => Hash::make($code),
            'activation_expires_at' => $expiresAt,
            'activation_attempts' => 0, // Reset attempts when new code is generated
        ]);
        
        return $user->fresh();
    }

    public function incrementActivationAttempts(User $user): User
    {
        $user->increment('activation_attempts');
        return $user->fresh();
    }

    public function resetActivationAttempts(User $user): User
    {
        $user->update(['activation_attempts' => 0]);
        return $user->fresh();
    }

    public function updateLastActivationRequest(User $user): User
    {
        $user->update(['last_activation_requested_at' => CarbonImmutable::now()]);
        return $user->fresh();
    }
}
