<?php

namespace App\Services\Admin\Auth;
use App\Services\Admin\Auth\LoginRateLimiter;
use App\Traits\Response\ValidationResponseTrait;
use App\Traits\Role\AuthGuardFirstRouteTrait;
use Illuminate\Support\Facades\Auth;
class LoginService
{
    use ValidationResponseTrait , AuthGuardFirstRouteTrait;
    protected LoginRateLimiter $limiterService;
    protected CheckStatus $checkStatusService;

    public function __construct(LoginRateLimiter $limiterService , CheckStatus $checkStatusService)
    {
        $this->limiterService = $limiterService;
        $this->checkStatusService = $checkStatusService;
    }

    /**
     * @var LoginRateLimiter
     * @var CheckStatus
     * @var ValidationResponseTrait
     */

    public function login($request) : mixed
    {
        // create throttle key with unique identifiers email & ip
        $key = $this->limiterService->throttleKey($request->email, $request->ip());
        // check if the user has too many failed login attempts
        $this->limiterService->checkTooManyFailedAttempts($key);
        // check that login credentials are valid
        $attempt = $this->attempt($request->email, $request->password, $request->boolean('remember'));
        // when login attempt is failed
        if (!$attempt) {
            // increment rate limiter on failed login
            $this->limiterService->increment($key); // Increment rate limiter on failed login
            // Respond with validation errors if login fails
            return ['status' => 'validationError'];
        }
        // check if the user is blocked
        $checkBlockStatus =  $this->checkStatusService->checkBlockStatus(auth('admin'));
        if ($checkBlockStatus) {
            $this->limiterService->increment($key);
           return [
                'status' => 'blocked',
            ];
        }
        // Clear rate limiter on successful login
        $this->limiterService->clear($key);
        // return success response
        return [
            'status' => 'success',
            'message' => __('admin/auth.login_success'),
            'route' => $this->firstAdminRoute(),
        ];
    }

    public function attempt($email , $password , $remember = false) : bool
    {
        // Attempt to authenticate the user with the provided credentials
        return Auth::guard('admin')->attempt([
            'email' => $email,
            'password' => $password
        ], $remember);
    }
}
