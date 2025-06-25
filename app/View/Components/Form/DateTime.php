<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class DateTime extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';
    private const DEFAULT_CLASS = 'form-control';

    public string $name;
    public ?string $label;
    public ?string $placeholder;
    public ?string $class;
    public bool $isRequired;
    public ?string $requiredMessage;
    public ?string $value;
    public bool $disabled;
    public ?string $min;
    public ?string $max;

    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $this->value = $options['value'] ?? null;
        $this->label = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : __(self::ADMIN_INPUTS_PREFIX . $this->name);
        $this->placeholder = isset($options['placeholder']) ? __(self::ADMIN_INPUTS_PREFIX . $options['placeholder']) : $this->label;
        $this->class = $options['class'] ?? self::DEFAULT_CLASS;
        $this->isRequired = $options['isRequired'] ?? false;
        $this->requiredMessage = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->disabled = $options['disabled'] ?? false;
        $this->min = $options['min'] ?? null;
        $this->max = $options['max'] ?? null;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.datetime');
    }
}
