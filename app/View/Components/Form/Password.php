<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Password extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const DEFAULT_CLASS = 'form-control';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';
    public const VALIDATION_MAX_LENGTH = 'admin/validation.max_length';
    public const VALIDATION_MIN_LENGTH = 'admin/validation.min_length';

    public string $name;
    public ?string $label;
    public ?string $class;
    public ?int $maxLength;
    public ?int $minLength;
    public bool $isRequired;
    public ?string $requiredMessage;
    public ?string $minLengthMessage;
    public bool $autofocus;

    public function __construct(array $options) {
        $this->name                 = $options['name'];
        $this->label                = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : __(self::ADMIN_INPUTS_PREFIX . $this->name);
        $this->class                = $options['class'] ?? self::DEFAULT_CLASS;
        $this->maxLength            = $options['maxLength'] ?? null;
        $this->minLength            = $options['minLength'] ?? null;
        $this->isRequired           = $options['isRequired'] ?? false;
        $this->requiredMessage      = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->minLengthMessage     =  __(self::VALIDATION_MIN_LENGTH, ['attribute' => $this->label, 'min' => $this->minLength]);
        $this->autofocus            = $options['autofocus'] ?? false;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.password');
    }
}
