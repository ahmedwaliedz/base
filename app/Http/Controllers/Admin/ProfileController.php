<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Profile\UpdatePasswordRequest;
use App\Http\Requests\Admin\Profile\UpdateProfileRequest;
use App\Services\Admin\AdminService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    use ResponseTrait;

    public function __construct(
        private readonly AdminService $adminService,
    ) {}

    public function profile()
    {
        $profile = auth('admin')->user();
        $countries = $this->adminService->getActiveCountryCodeOptions();
        return view('admin.profile.index', compact('profile', 'countries'));
    }
    public function update(UpdateProfileRequest $request)
    {
        auth('admin')->user()->update($request->validated());
        return $this->respondWithSuccess(__('admin/main.updated_successfully'), [
            'route' => route('admin.profile'),
        ]);
    }

    public function updatePassword(UpdatePasswordRequest $request)
    {
        auth('admin')->user()->update(['password' => $request->validated()['password']]);
        return $this->respondWithSuccess(__('admin/main.password_updated_successfully'), [
            'route' => route('admin.profile'),
        ]);
    }
}
