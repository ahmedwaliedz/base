<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;

class LanguageController extends Controller
{
    public function changeLang($lang)
    {
        if (in_array($lang, languages(), true)) {
            Session::put('admin-lang', $lang);
            App::setLocale($lang);
            Carbon::setLocale($lang);
        }
        return redirect()->back()->with('success', __('admin.language_changed_successfully'));
    }
}
