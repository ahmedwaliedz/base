<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Setting\StoreUpdateSettingRequest;
use App\Services\Admin\Settings\SettingService;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    use ResponseTrait;

   public function __construct(protected SettingService $settingService)
   {
   }

    public function index()
    {
        $settings = $this->settingService->getSettings();
        return view('admin.settings.index', compact('settings'));
    }

    public function update(StoreUpdateSettingRequest $request)
    {
        $this->settingService->updateSettings($request->validated());
        return $this->respondWithSuccess(__('admin/main.settings_updated'), [
            'route' => route('admin.settings.index')
        ]);
    }
}
