<?php

namespace App\Traits\Response;

use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait BaseResponseTrait
{
    /**
     * @param string|null $message
     * @param array $data
     * @param int $statusCode
     * @param array $headers
     * @return JsonResponse
     */
    protected function mainRespond($data, int $statusCode = Response::HTTP_OK, array $headers = []): JsonResponse
    {
        // This method is used to respond with a JSON response.
        return response()->json($data, $statusCode, $headers);
    }
}
