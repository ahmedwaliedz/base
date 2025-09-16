<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const DEFAULT_CLASS = 'form-control';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';
    public const VALIDATION_MIN_LENGTH = 'admin/validation.min_length';
    public const VALIDATION_MAX_LENGTH = 'admin/validation.max_length';

    public string $name;
    public ?string $label;
    public ?string $placeholder;
    public ?string $class;
    public ?int $minLength;
    public bool $isRequired;
    public ?string $requiredMessage;
    public string|array|null $value;
    public bool $disabled;
    public bool $autofocus;
    public ?string $minLengthMessage;
    public bool $isMultiLanguage;
    public function __construct(array $options) {
        $this->value = $options['value'] ?? null;
        $this->name = $options['name'];
        $this->label = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : __(self::ADMIN_INPUTS_PREFIX . $this->name);
        $this->placeholder = isset($options['placeholder']) ? __(self::ADMIN_INPUTS_PREFIX . $options['placeholder']) : $this->label;
        $this->class = $options['class'] ?? self::DEFAULT_CLASS;
        $this->minLength = $options['minLength'] ?? null;
        $this->isRequired = $options['isRequired'] ?? false;
        $this->requiredMessage = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->disabled = $options['disabled'] ?? false;
        $this->autofocus = $options['autofocus'] ?? false;
        $this->minLengthMessage =  __(self::VALIDATION_MIN_LENGTH, ['attribute' => $this->label, 'min' => $this->minLength]);
        $this->isMultiLanguage = $options['isMultiLanguage'] ?? false;
    }
    public function render(): View|Closure|string
    {
        return view('components.form.text');
    }
}
