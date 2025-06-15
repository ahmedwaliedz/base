<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Map extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const DEFAULT_CLASS = 'col-md-12';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';

    public ?string $name;
    public ?string $label;
    public ?string $class;
    public bool $isRequired;
    public ?string $requiredMessage;
    public ?string $lat;
    public ?string $lng;
    public string $apiKey;

    public function __construct(array $options)
    {
        $this->name = $options['name'] ? __(self::ADMIN_INPUTS_PREFIX . $options['name'])  : __(self::ADMIN_INPUTS_PREFIX . 'map');
        $this->label = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : $this->name;
        $this->class = $options['class'] ?? self::DEFAULT_CLASS;
        $this->isRequired = $options['isRequired'] ?? false;
        $this->requiredMessage = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->lat = isset($options['lat']) ? $options['lat'] : null;
        $this->lng = isset($options['lng']) ? $options['lng'] : null;
        $this->apiKey = $options['apiKey'] ?? cache()->get('settings')['google_map_api_key'];
    }

    public function render(): View|Closure|string
    {
        return view('components.form.map');
    }
}
