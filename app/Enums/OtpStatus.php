<?php

namespace App\Enums;

use App\Traits\Enums\GeneralEnumTrait;

enum OtpStatus: string
{
    use GeneralEnumTrait;

    case ACTIVE     = 'active';
    case FINISHED   = 'finished';
    case FAILED     = 'failed';
}
