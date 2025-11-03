<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

interface UserRepositoryInterface
{
    /**
     * Find user by phone number (checks both phone and phone_normalized)
     *
     * @param string $phone
     * @return User|null
     */
    public function findByPhone(string $phone): ?User;

    /**
     * Create new user
     *
     * @param array $data
     * @return User
     */
    public function create(array $data): User;

    /**
     * Update user activation code fields
     *
     * @param User $user
     * @param string $code
     * @param \Carbon\CarbonImmutable $expiresAt
     * @return User
     */
    public function updateActivationCode(User $user, string $code, \Carbon\CarbonImmutable $expiresAt): User;

    /**
     * Increment activation attempts
     *
     * @param User $user
     * @return User
     */
    public function incrementActivationAttempts(User $user): User;

    /**
     * Reset activation attempts
     *
     * @param User $user
     * @return User
     */
    public function resetActivationAttempts(User $user): User;

    /**
     * Update last activation request time
     *
     * @param User $user
     * @return User
     */
    public function updateLastActivationRequest(User $user): User;
}
