<?php

namespace App\Services\Admin\Auth;

use App\Traits\Response\ResponseTrait;

class CheckStatus
{
    use ResponseTrait;
    public function checkBlockStatus($user): bool
    {
        if ($user->user()->is_blocked) {
            $user->logout();
            return true;
        }
        return false;
    }
}
