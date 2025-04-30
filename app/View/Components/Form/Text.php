<?php

namespace App\View\Components\Form;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Text extends Component
{
    public string $name;
    public ?string $label;
    public ?string $placeholder;
    public ?string $class;
    public ?int $maxLength;
    public ?int $minLength;
    public bool $isRequired;
    public ?string $requiredMessage;
    public string|array|null $value;
    public bool $disabled;
    public bool $autofocus;
    public bool $isMultiLanguage;

    public function __construct(
        string $name,
        string|array|null $value = null,
        ?string $label = null,
        ?string $placeholder = null,
        ?string $class = 'form-control',
        ?int $maxLength = null,
        ?int $minLength = null,
        bool $isRequired = false,
        ?string $requiredMessage = null,
        bool $disabled = false,
        bool $autofocus = false,
        bool $isMultiLanguage = false,
    ) {
        $this->value = $value;
        $this->name = $name;
        $this->label = $label ?? $name;
        $this->placeholder = $placeholder ?? $name;
        $this->class = $class;
        $this->maxLength = $maxLength;
        $this->minLength = $minLength;
        $this->isRequired = $isRequired;
        $this->requiredMessage = $requiredMessage ?? null;
        $this->disabled = $disabled;
        $this->autofocus = $autofocus;
        $this->isMultiLanguage = $isMultiLanguage;
    }
    public function render(): View|Closure|string
    {
        return view('components.form.text');
    }
}
