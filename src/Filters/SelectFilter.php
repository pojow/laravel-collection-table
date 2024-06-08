<?php

namespace Pojow\LaravelCollectionTable\Filters;

use Illuminate\Support\Facades\View;
use Pojow\LaravelCollectionTable\Abstracts\AbstractFilter;

class SelectFilter extends AbstractFilter
{
    private const BOOTSTRAP_CLASSES = ['form-select'];

    private const TAILWIND_CLASSES = [
        'appearance-none',
        'text-sm',
        'py-2.5',
        'px-3',
        'bg-transparent',
        'border',
        'rounded-r',
        'focus:border-primary',
        'transition-colors',
        'cursor-pointer',
    ];

    protected array $options = [];

    protected bool $multiple = false;

    protected string $label;

    protected string|array|null $selected;

    protected array $selectClasses;

    protected array $selectAttributes = [];

    protected string $view;

    protected ?string $icon;

    public function __construct(protected string $attribute, protected string $parameterName)
    {
        $this->label = $this->attribute;
        $this->selected = request()->query($this->parameterName);
        $cssFramework = config('laravel-collection-table.css_framework');
        $this->selectClasses = $cssFramework === 'bootstrap-5'
            ? self::BOOTSTRAP_CLASSES
            : self::TAILWIND_CLASSES;
        $this->view = config("laravel-collection-table.views.$cssFramework.filters.select");
        $this->icon = config('laravel-collection-table.icon.filter');
    }

    public function options(array $options): self
    {
        $this->options = $options;

        return $this;
    }

    public function label(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    public function selected(string|int|array $selected): self
    {
        $this->selected = $selected;

        return $this;
    }

    public function multiple(): self
    {
        $this->multiple = true;

        return $this;
    }

    public function icon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    public function selectClasses(array $classes, bool $replace = false): self
    {
        $this->selectClasses = $replace
            ? $classes
            : [...$this->selectClasses, ...$classes];

        return $this;
    }

    public function selectAttributes(array $attributes): self
    {
        foreach ($attributes as $attributeName => $attributeValue) {
            switch (true) {
                case ! is_string($attributeName) && is_string($attributeValue):
                    $this->selectAttributes[] = $attributeValue;
                    break;
                case is_string($attributeName) && ! $attributeValue:
                    $this->selectAttributes[] = $attributeName;
                    break;
                case is_string($attributeName):
                    $this->selectAttributes[] = "$attributeName=\"$attributeValue\"";
                    break;
                default:
                    break;
            }
        }

        return $this;
    }

    public function customView(string $customView): self
    {
        $this->view = $customView;

        return $this;
    }

    public function validate(mixed $element): bool
    {
        if (! $this->selected) {
            return true;
        }
        $selected = $this->multiple && ! is_array($this->selected) ? [$this->selected] : $this->selected;

        return $this->multiple
            ? in_array((string) data_get($element, $this->attribute), $selected)
            : (string) data_get($element, $this->attribute) == $selected;
    }

    public function render(): string
    {
        $selected = $this->selected;
        if ($selected && $this->multiple && ! is_array($selected)) {
            $selected = [$selected];
        }

        return View::make($this->view, [
            'label' => $this->label,
            'options' => $this->options,
            'selected' => $selected,
            'parameterName' => $this->parameterName,
            'multiple' => $this->multiple,
            'selectClasses' => implode(' ', $this->selectClasses),
            'selectAttributes' => implode(' ', $this->selectAttributes),
            'icon' => $this->icon,
        ])->render();
    }
}
