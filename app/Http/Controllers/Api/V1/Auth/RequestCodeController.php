<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RequestCodeRequest;
use App\Contracts\AuthServiceInterface;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;

class RequestCodeController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private readonly AuthServiceInterface $authService
    ) {}

    public function requestCode(RequestCodeRequest $request): JsonResponse
    {
        try {
            $validated = $request->validated();
            $this->authService->requestActivationCode($validated['phone'], $validated['country_code']);

            return response()->json([], 204);
        } catch (\Illuminate\Http\Exceptions\ThrottleRequestsException $e) {
            return $this->respondWithFail($e->getMessage(), [], 429);
        }
    }
}
