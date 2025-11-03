<?php

declare(strict_types=1);

namespace App\Contracts;

use App\Models\User;

interface AuthServiceInterface
{
    /**
     * Login user with phone and password
     *
     * @param array $validatedData
     * @return array{user: User, token: string}
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function loginWithPassword(array $validatedData): array;

    /**
     * Login user with phone and activation code
     *
     * @param array $validatedData
     * @return array{user: User, token: string}
     * @throws \Illuminate\Auth\AuthenticationException
     */
    public function loginWithCode(array $validatedData): array;

    /**
     * Request activation code for phone number
     *
     * @param string $phone
     * @return void
     */
    public function requestActivationCode(string $phone): void;

    /**
     * Logout user and revoke token
     *
     * @param User $user
     * @param string|null $tokenId
     * @return void
     */
    public function logout(User $user, ?string $tokenId = null): void;
}
