<?php

namespace App\Enums;

enum FaqType: string
{
    case USER = 'user';
    case PROVIDER = 'provider';
    case PUBLIC = 'public';
}
