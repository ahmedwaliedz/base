<?php

namespace App\Traits\Response;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
trait AuthResponseTrait
{
    use FailResponseTrait;
    /* @param string|null $message
         * @return JsonResponse
    */
    public function respondUnAuthorized(string $message = null): JsonResponse
    {
        //  This method is used to respond with an unauthorized error message.
        return $this->respondWithFail($message ?? __('response.unauthorized'), [] ,  Response::HTTP_BAD_REQUEST);
    }
    /* @param string|null $message
         * @return JsonResponse
    */
    public function respondUnauthenticated(string $message = null): JsonResponse
    {
        //  This method is used to respond with an unauthenticated error message.
        return $this->respondWithFail($message ?? __('response.unauthenticated'), [] ,  Response::HTTP_UNAUTHORIZED);
    }
    /* @param string|null $message
         * @return JsonResponse
    */
    public function respondForbidden(string $message = null): JsonResponse
    {
        //  This method is used to respond with a forbidden error message.
        return $this->respondWithFail($message ?? __('response.forbidden'), [] ,  Response::HTTP_FORBIDDEN);
    }
}
