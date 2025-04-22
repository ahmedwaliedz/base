<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function home() : \Illuminate\Contracts\View\View
    {
        return view('admin.home.index');
    }
}
