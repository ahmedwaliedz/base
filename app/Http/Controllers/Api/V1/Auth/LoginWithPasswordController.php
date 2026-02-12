<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\PasswordLoginRequest;
use App\Http\Resources\Auth\UserResource;
use App\Services\Auth\AuthService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;

class LoginWithPasswordController extends Controller
{
    use ResponseTrait;
    public function __construct(private readonly AuthService $authService) {}

    public function login(PasswordLoginRequest $request): JsonResponse
    {
        $result = $this->authService->loginWithPassword($request->validated());
        return match ($result['status']) {
            'validationError'   => $this->respondValidationErrors([ 'login_value' => [$result['message']],'password'=> [$result['message']],]),
            'notActive'         => $this->respondNotActive($result['message'], $result['user']),
            'blocked'           => $this->respondBlocked($result['message']),
            'success'           => $this->respondWithSuccess(__('api/auth.logged_in_successfully'), new UserResource($result['user'], $result['token'])),
        };
    }
}
