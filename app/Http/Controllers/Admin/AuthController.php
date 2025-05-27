<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Auth\LoginRequest;
use App\Services\Admin\Auth\LoginService;
use App\Traits\Response\ResponseTrait;
use Illuminate\View\View;
class AuthController extends Controller
{
    use ResponseTrait ;

    protected LoginService $loginService;

    public function __construct(LoginService $loginService)
    {
        $this->loginService = $loginService;
    }

    public function loginPage(): View
    {
        return view('admin.auth.login');
    }

    public function login(LoginRequest $request)
    {
        $loginData = $this->loginService->login($request);
        return match ($loginData['status']) {
            'validationError'   => $this->respondValidationErrors([
                'email' => [__('admin/auth.wrong_credentials')],
                'password' => [__('admin/auth.wrong_credentials')],
            ]),
            'blocked'           => $this->respondBlocked(),
            default             => $this->respondWithSuccess($loginData['message'], $loginData),
        };
    }

    public function logout() : \Illuminate\Http\RedirectResponse
    {
        auth('admin')->logout();
        return redirect()->route('admin.loginPage');
    }
}
