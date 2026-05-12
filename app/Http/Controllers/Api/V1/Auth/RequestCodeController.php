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
        $this->authService->requestActivationCode($request->validated()['phone']);

        return response()->json([], 204);
    }
}
