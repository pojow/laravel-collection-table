<?php

namespace Pojow\LaravelCollectionTable;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Pojow\LaravelCollectionTable\Abstracts\AbstractColumnFormatter;

class Column
{
    protected AbstractColumnFormatter|Closure|null $formatter = null;

    protected ?string $title = null;

    protected bool $escapeHtml = true;

    protected bool $searchable = false;

    protected bool $sortable = false;

    protected Closure|array|null $sortClosure = null;

    public function __construct(protected string $attribute)
    {
        //
    }

    public static function make(string $attribute): self
    {
        return new self($attribute);
    }

    public function title(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function format(Closure|AbstractColumnFormatter $format, bool $escapeHtml = true): self
    {
        $this->formatter = $format;
        $this->escapeHtml = $escapeHtml;

        return $this;
    }

    public function searchable(): self
    {
        $this->searchable = true;

        return $this;
    }

    public function getSearchable(): bool
    {
        return $this->searchable;
    }

    public function sortable(Closure|array|null $sortClosure = null): self
    {
        $this->sortable = true;
        $this->sortClosure = $sortClosure;

        return $this;
    }

    public function getSortable(): bool
    {
        return $this->sortable;
    }

    public function getSortClosure(): Closure|array|null
    {
        return $this->sortClosure;
    }

    public function getAttribute(): string
    {
        return $this->attribute;
    }

    public function getOrderQuery(): string
    {
        $currentQuery = request()->query();
        $filteredQuery = array_filter(
            $currentQuery,
            static fn (string $key) => ! in_array($key, ['order_by', 'order_dir'], true),
            ARRAY_FILTER_USE_KEY
        );
        $columnOrderQuery = match (true) {
            data_get($currentQuery, 'order_by') !== $this->attribute => [
                'order_by' => $this->attribute, 'order_dir' => 'ASC',
            ],
            data_get($currentQuery, 'order_dir') === 'ASC' => ['order_by' => $this->attribute, 'order_dir' => 'DESC'],
            default => []
        };
        $orderQuery = [
            ...$filteredQuery,
            ...$columnOrderQuery,
        ];
        $orderQueryString = http_build_query($orderQuery, '', '&');

        return $orderQueryString ? "?$orderQueryString" : request()->url();
    }

    public function getTitle(): string
    {
        return $this->title ?? __('validation.attributes.' . $this->attribute);
    }

    public function getValue(Model|array $element): string
    {
        if (! $this->formatter) {
            return ($element instanceof Model)
                ? $element->{$this->attribute}
                : data_get($element, $this->attribute);
        }
        if ($this->formatter instanceof Closure) {
            return $this->escapeHtml
                ? htmlspecialchars(($this->formatter)($element))
                : ($this->formatter)($element);
        }

        return $this->formatter->render($element[$this->attribute]);
    }
}
