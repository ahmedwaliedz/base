<?php

namespace App\Enums;

use App\Traits\Enums\GeneralEnumTrait;

enum OtpType: string
{
    use GeneralEnumTrait;

    case FORGET_PASSWORD    = 'forget_password';
    case CHANGE_PASSWORD    = 'change_password';
    case OLD_PHONE_VERIFY   = 'old_phone_verify';
    case NEW_PHONE_VERIFY   = 'new_phone_verify';
    case OLD_EMAIL_VERIFY   = 'old_email_verify';
    case NEW_EMAIL_VERIFY   = 'new_email_verify';
    case ACTIVATE           = 'activate';
}
