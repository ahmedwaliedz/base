<?php

namespace App\Http\Middleware\Admin;

use Carbon\Carbon;
use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;
class AdminSetLocale
{
    public function handle($request, Closure $next)
    {
        $lang = defaultLang();

        if (Session::has('admin-lang')) {
            $lang = Session::get('admin-lang');
        }

        App::setLocale($lang);
        Carbon::setLocale($lang);
        return $next($request);
    }
}
