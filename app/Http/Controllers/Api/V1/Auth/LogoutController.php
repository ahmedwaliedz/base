<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    use ResponseTrait;

    public function logout(Request $request): JsonResponse
    {
        $user = $request->user();
        $tokenId = $request->user()->currentAccessToken()->id ?? null;
        
        // Revoke the current token
        $user->currentAccessToken()->delete();
        
        return $this->respondWithSuccess(__('responses.logged_out_successfully'));
    }
}
