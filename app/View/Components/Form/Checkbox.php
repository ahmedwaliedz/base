<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Checkbox extends Component
{
    private const ADMIN_INPUTS_PREFIX = 'admin/inputs.';
    private const VALIDATION_REQUIRED = 'admin/validation.required_input';
    private const DEFAULT_CLASS = 'form-check';

    public string $name;
    public ?string $label;
    public ?string $class;
    public bool $isRequired;
    public ?string $requiredMessage;
    public bool $checked;
    public bool $disabled;

    public function __construct(array $options)
    {
        $this->name = $options['name'];
        $this->label = isset($options['label']) ? __(self::ADMIN_INPUTS_PREFIX . $options['label']) : __(self::ADMIN_INPUTS_PREFIX . $this->name);
        $this->class = $options['class'] ?? self::DEFAULT_CLASS;
        $this->isRequired = $options['isRequired'] ?? false;
        $this->requiredMessage = $options['requiredMessage'] ?? __(self::VALIDATION_REQUIRED, ['attribute' => $this->label]);
        $this->checked = (bool)($options['checked'] ?? false);
        $this->disabled = $options['disabled'] ?? false;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.form.checkbox');
    }
}
