<?php

namespace App\Traits\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait SuccessResponseTrait
{
    /**
     * @param  string|null  $message
     * @param  array  $data
     * @return JsonResponse
     */
    use BaseResponseTrait;

    protected function respondWithSuccess(?string $message = null, $data = null): JsonResponse
    {
        return $this->mainRespond(
            [
                'status' => 'success',
                'message' => $message ?? __('Success'),
                'data' => $data,
            ],
            Response::HTTP_OK
        );
    }
}
