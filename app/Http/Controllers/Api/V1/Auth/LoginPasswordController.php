<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordLoginRequest;
use App\Contracts\AuthServiceInterface;
use App\Http\Resources\Auth\UserResource;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;

class LoginPasswordController extends Controller
{
    use ResponseTrait;

    public function login(PasswordLoginRequest $request): JsonResponse
    {
        dd(1);
        $result = $this->authService->loginWithPassword($request->validated());
        
        return $this->respondWithSuccess(__('responses.logged_in_successfully'), [
            'token' => $result['token'],
            'user' => new UserResource($result['user'])
        ]);
    }
}
