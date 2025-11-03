<?php

declare(strict_types=1);

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Resources\Auth\UserResource;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MeController extends Controller
{
    use ResponseTrait;

    public function me(Request $request): JsonResponse
    {
        $user = $request->user();
        
        return $this->respondWithSuccess(__('responses.user_profile_retrieved'), [
            'user' => new UserResource($user)
        ]);
    }
}
