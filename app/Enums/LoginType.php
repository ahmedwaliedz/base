<?php

namespace App\Enums;

use App\Traits\Enums\GeneralEnumTrait;

enum LoginType: string
{
    use GeneralEnumTrait;

    case EMAIL = 'email';
    case PHONE = 'phone';

    public static function label(self $type): string
    {
        return match ($type) {
            self::EMAIL => __('api/auth.email'),
            self::PHONE => __('api/auth.phone'),
        };
    }
}
