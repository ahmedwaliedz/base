<?php

namespace App\Traits\Response;

use Illuminate\Http\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

trait SystemErrorResponseTrait
{
    use FailResponseTrait;

    /**
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    public function respondInternalError($message = null): JsonResponse
    {
        // This method is used to respond with a JSON response for internal server error.
        return $this->respondWithFail($message ?? __('response.internal_error'), [] ,  Response::HTTP_INTERNAL_SERVER_ERROR);
    }
    /**
     * @param string|null $message
     * @param array $data
     * @return JsonResponse
     */
    public function respondUnknown($message = null): JsonResponse
    {
        // This method is used to respond with a JSON response for unknown error.
        return $this->respondWithFail($message ?? __('response.unknown_error'), [] ,  Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    public function respondNotFound(string $message = null): JsonResponse
    {
        // This method is used to respond with a JSON response for not found error.
        return $this->respondWithFail($message ?? __('response.not_found'), [] ,  Response::HTTP_NOT_FOUND);
    }

}
