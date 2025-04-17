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
        // Check if the language is supported
        if (in_array($lang, languages())) {
            // Set the session variable for the selected language
            Session::put('admin-lang', $lang);
        }
        // Set the application locale and Carbon locale
        App::setLocale($lang);
        // Set the Carbon locale
        Carbon::setLocale($lang);
        return redirect()->back()->with('success', __('admin.language_changed_successfully'));
    }
}
