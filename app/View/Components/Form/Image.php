<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const DEFAULT_CLASS = 'form-control';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';

    public string $name;
    public ?string $label;
    public ?string $class;
    public bool $isRequired;
    public ?string $requiredMessage;
    public ?string $value;
    public ?string $accept;

    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $this->value = $options['value'] ?? null;
        $this->label = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : __(self::ADMIN_INPUTS_PREFIX . $options['name']);
        $this->class = $options['class'] ?? self::DEFAULT_CLASS;
        $this->isRequired = $options['isRequired'] ?? false;
        $this->requiredMessage = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->accept = $options['accept'] ?? 'image/*';
    }

    public function render(): View|Closure|string
    {
        return view('components.form.image');
    }
}
