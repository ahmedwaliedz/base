<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Email extends Component
{
    public string $name;
    public ?string $label;
    public ?string $placeholder;
    public ?string $class;
    public ?int $maxLength;
    public ?int $minLength;
    public bool $isRequired;
    public ?string $requiredMessage;
    public ?string $value;
    public bool $disabled;
    public bool $autofocus;

    public function __construct(
        string $name,
        ?string $value = null,
        ?string $label = null,
        ?string $placeholder = null,
        ?string $class = 'form-control',
        ?int $maxLength = null,
        ?int $minLength = null,
        bool $isRequired = false,
        ?string $requiredMessage = null,
        bool $disabled = false,
        bool $autofocus = false,
    ) {
        $this->value = $value;
        $this->name = $name;
        $this->label = $label ?  __('admin/inputs.' . $label) : __('admin/inputs.' . $name);
        $this->placeholder = $placeholder ? __('admin/inputs.' . $placeholder) : __('admin/inputs.' . $name);
        $this->class = $class;
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
        $this->isRequired = $isRequired;
        $this->requiredMessage = $requiredMessage ?? __('admin/validation.required_input', ['attribute' => $this->label]);
        $this->disabled = $disabled;
        $this->autofocus = $autofocus;
    }

    public function render(): View|Closure|string
    {
        return view('components.form.email');
    }
}
