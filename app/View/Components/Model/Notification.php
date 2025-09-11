<?php

namespace App\View\Components\Model;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Notification extends Component
{
    public function __construct(public $route = null, public $class = 'App\Models\User')
    {

    }

    public function render(): View|Closure|string
    {
        $availableNotificationTypes = [];

        if (class_exists($this->class) && method_exists($this->class, 'getAvailableNotificationTypes')) {
            $availableNotificationTypes = $this->class::getAvailableNotificationTypes();
        }

        return view('components.model.notification', [
            'availableNotificationTypes' => $availableNotificationTypes
        ]);
    }
}
