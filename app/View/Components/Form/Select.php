<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Select extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';
    private const DEFAULT_CLASS = 'form-control';

    public string   $name;
    public ?string  $label;
    public ?string  $placeholder;
    public ?string  $class;
    public bool     $isRequired;
    public ?string  $requiredMessage;
    public string|int|null  $value;
    public bool     $disabled;
    public array    $options;
    public ?string  $optionValueKey;
    public ?string  $optionTextKey;

    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $value = $options['value'] ?? null;
        if ($value instanceof \BackedEnum) {
            $value = $value->value;
        }
        if (is_bool($value)) {
            $value = $value ? 1 : 0;
        }
        $this->value = (is_string($value) || is_int($value) || is_null($value)) ? $value : (string) $value;
        $this->label = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : __(self::ADMIN_INPUTS_PREFIX . $this->name);
        $this->placeholder = isset($options['placeholder']) ? __(self::ADMIN_INPUTS_PREFIX . $options['placeholder']) : $this->label;
        $this->class = $options['class'] ?? self::DEFAULT_CLASS;
        $this->isRequired = $options['isRequired'] ?? false;
        $this->requiredMessage = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->disabled = $options['disabled'] ?? false;
        $this->options = collect($options['options'] ?? [])->all();
        $this->optionValueKey = $options['optionValueKey'] ?? 'id';
        $this->optionTextKey = $options['optionTextKey'] ?? 'name';
    }

    public function render(): View|Closure|string
    {
        return view('components.form.select');
    }
}
