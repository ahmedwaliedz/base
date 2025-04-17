<?php

namespace  App\Traits\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait FailResponseTrait
{
    use BaseResponseTrait;
    /**
     * @param string|null $message
     * @param array $data
     * @param int $statusCode
     * @return JsonResponse
     */
    protected function respondWithFail(string $message = null , array  $data = [] , int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR ): JsonResponse
    {
        // This method is used to respond with a JSON response for failure.
        return $this->mainRespond([
            'message' => $message,
            'data' => $data
        ], $statusCode);
    }
}
