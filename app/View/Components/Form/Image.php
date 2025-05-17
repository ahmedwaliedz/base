<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Image extends Component
{
    public string $name;
    public ?string $label;
    public ?string $class;
    public bool $isRequired;
    public ?string $requiredMessage;
    public ?string $value;
    public ?string $accept;

    public function __construct(
        string $name,
        ?string $value = null,
        ?string $accept = null,
        ?string $label = null,
        ?string $class = 'form-control',
        bool $isRequired = false,
        ?string $requiredMessage = null,
    ) {
        $this->accept = $accept  ?? 'image/*';
        $this->value = $value;
        $this->name = $name;
        $this->label = $label ?  __('admin/inputs.' . $label) : __('admin/inputs.' . $name);
        $this->class = $class;
        $this->isRequired = $isRequired;
        $this->requiredMessage = $requiredMessage ?? __('admin/validation.required_input', ['attribute' => $this->label]);
    }

    public function render(): View|Closure|string
    {
        return view('components.form.image');
    }
}
