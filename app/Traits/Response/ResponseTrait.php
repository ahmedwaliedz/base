<?php

namespace App\Traits\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ResponseTrait
{
    use AuthResponseTrait;
    use SystemErrorResponseTrait;
    use ValidationResponseTrait;
    use UserStateResponseTrait;
    use SuccessResponseTrait;
    use FailResponseTrait;
}
