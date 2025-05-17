<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Traits\Response\ResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    use ResponseTrait ;
    public function index(){
        $settings = cache()->get('settings');
        return view('admin.settings.index' , compact('settings'));
    }
    public function update(Request $request)
    {
        dd($request->except(['_token' , '_method']));
        foreach ($request->except(['_token' , '_method']) as $key => $value) {
            Setting::where('key' , $key)->update([
                'value' => $value
            ]
            );
//            Setting::updateOrCreate([
//                    'key' => $key
//                ],[
//                    'value' => $value
//                ]
//            );
        }
        cache()->forget('settings');
        Cache::rememberForever('settings', function () {
            return Setting::get()->pluck('value', 'key');
        });
        return $this->respondWithSuccess(__('admin/main.settings_updated') , [
            'route' => route('admin.settings.index')
        ]);
    }
}
