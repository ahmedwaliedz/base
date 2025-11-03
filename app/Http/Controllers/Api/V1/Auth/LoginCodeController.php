<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\CodeLoginRequest;
use App\Contracts\AuthServiceInterface;
use App\Http\Resources\Auth\UserResource;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;

class LoginCodeController extends Controller
{
    use ResponseTrait;

    public function __construct(  private readonly AuthServiceInterface $authService)
    {
 
    }

    public function loginCode(CodeLoginRequest $request): JsonResponse
    {
        $result = $this->authService->loginWithCode($request->validated());
        
        return $this->respondWithSuccess(__('responses.logged_in_successfully'), [
            'token' => $result['token'],
            'user' => new UserResource($result['user'])
        ]);
    }
}
