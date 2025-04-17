<?php

namespace App\Traits\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait SuccessResponseTrait
{
    /**
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    use BaseResponseTrait;
    protected function respondWithSuccess(string $message = null , array $data = [] ): JsonResponse
    {
        // This method is used to respond with a JSON response for success.
        return $this->mainRespond(
            [
                'message' => $message ?? __('Success'),
                'data' => $data
            ],
            Response::HTTP_OK
        );
    }
}
