<?php

namespace App\Traits\Response;

use Illuminate\Http\JsonResponse;

use Symfony\Component\HttpFoundation\Response;

trait UserStateResponseTrait
{
    use FailResponseTrait;

    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondBlocked(string $message = null): JsonResponse
    {
        // This method is used to respond with a blocked error message.
        return $this->respondWithFail($message ?? __('response.blocked_by_admin'), [] ,  Response::HTTP_LOCKED);
    }
    /**
     * @param string|null $message
     * @return JsonResponse
     */
    public function respondNotActive(string $message = null): JsonResponse
    {
        // This method is used to respond with a not active error message.
        return $this->respondWithFail($message ?? __('response.need_activation'), [] ,  Response::HTTP_NON_AUTHORITATIVE_INFORMATION);
    }
}
