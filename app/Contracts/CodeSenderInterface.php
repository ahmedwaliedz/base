<?php

declare(strict_types=1);

namespace App\Contracts;

interface CodeSenderInterface
{
    /**
     * Send activation code to phone number
     *
     * @param string $phone
     * @param string $code
     * @return void
     */
    public function sendCode(string $phone, string $code): void;
}
