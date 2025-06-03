<?php

namespace App\Console;

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

class ConsoleCommands
{
    public static function load()
    {
        Artisan::command('inspire', function () {
            $this->comment(Inspiring::quote());
        })->purpose('Display an inspiring quote');
    }
}
