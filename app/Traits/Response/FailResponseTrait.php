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
    protected function respondWithFail(string|null $message = null , array  $data = [] , int $statusCode = Response::HTTP_INTERNAL_SERVER_ERROR ): JsonResponse
    {
        $response = ['message' => $message];

        if (!empty($data)) {
            $response['data'] = $data;
        }

        return $this->mainRespond($response, $statusCode);
    }
}
