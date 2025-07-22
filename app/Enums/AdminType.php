<?php

namespace App\Enums;

enum AdminType: string
{
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    public function label(): string
    {
        return match ($this) {
            self::ADMIN => __('admin/main.admin'),            // or __('adminTypes.admin') if using i18n keys
            self::SUPER_ADMIN => __('admin/main.super_admin'), // or __('adminTypes.super_admin')
        };
    }
}
