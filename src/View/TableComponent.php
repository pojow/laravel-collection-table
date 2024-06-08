<?php

namespace Pojow\LaravelCollectionTable\View;

use Illuminate\Container\Container;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Illuminate\View\Component;
use Pojow\LaravelCollectionTable\Abstracts\AbstractTableConfiguration;
use Pojow\LaravelCollectionTable\Column;
use Pojow\LaravelCollectionTable\Table;

class TableComponent extends Component
{
    public Table $table;

    public Collection $collection;

    public ?string $searchableLabels = null;

    public ?int $columnsCount = null;

    public function __construct(string $config)
    {
        $this->buildComponent($config);
        $this->columnsCount = $this->table->getColumns()->count() + $this->table->getRowActions()->isNotEmpty();
    }

    protected function buildComponent(string $config): void
    {
        /** @var AbstractTableConfiguration $config */
        $config = app($config);
        $this->table = $config->setup();
    }

    protected function applyQueryFilter(): void
    {
        $searchableColumns = $this->table->getColumns()
            ->filter(fn (Column $column) => $column->getSearchable());
        $this->searchableLabels =
            implode(', ', $searchableColumns->map(fn (Column $column) => $column->getTitle())->toArray());
        if (request()->query('query')) {
            $this->collection =
                $this->collection->filter(function (Model|array $element) use ($searchableColumns) {
                    foreach ($searchableColumns as $searchableColumn) {
                        /** @var Column $searchableColumn */
                        if (Str::contains($searchableColumn->getValue($element), request()->query('query'), true)) {
                            return true;
                        }
                    }

                    return false;
                });
        }
    }

    protected function applyFilters(): void
    {
        $this->collection =
            $this->collection->filter(function (Model|array $element) {
                foreach ($this->table->getFilters() as $filter) {
                    if (! $filter->validate($element)) {
                        return false;
                    }
                }

                return true;
            });
    }

    protected function applyOrderBy(): void
    {
        $orderBy = request()->query('order_by');
        $orderDir = request()->query('order_dir');
        if (! $orderBy || ! in_array($orderDir, ['ASC', 'DESC'])) {
            return;
        }

        $orderColumn = $this->table
            ->getColumns()
            ->filter(fn (Column $column) => $column->getSortable() && $column->getAttribute() === $orderBy)
            ->first();
        if (! $orderColumn) {
            return;
        }

        $sortClosure = $orderColumn->getSortClosure() ?? $orderColumn->getAttribute();
        $this->collection = $orderDir === 'ASC'
            ? $this->collection->sortBy($sortClosure)
            : $this->collection->sortByDesc($sortClosure);
    }

    protected function applyPagination(): LengthAwarePaginator
    {
        if (count($this->table->getRowsPerPage()) === 1 || ! request()->query('per_page')
            || ! in_array((int) request()->query('per_page'), $this->table->getRowsPerPage(), true)) {
            $rowsPerPage = $this->table->getRowsPerPage()[0];
        } else {
            $rowsPerPage = request()->query('per_page');
        }
        $currentPage = Paginator::resolveCurrentPage();

        return self::paginator($this->collection->forPage($currentPage, $rowsPerPage), $this->collection->count(),
            $rowsPerPage,
            $currentPage, [
                'path' => Paginator::resolveCurrentPath(),
                'pageName' => 'page',
            ]);
    }

    protected static function paginator($items, $total, $perPage, $currentPage, $options): LengthAwarePaginator
    {
        return Container::getInstance()->makeWith(LengthAwarePaginator::class, compact(
            'items', 'total', 'perPage', 'currentPage', 'options'
        ));
    }

    protected function getRows(): LengthAwarePaginator
    {
        $this->collection = $this->table->getCollection();
        $this->applyQueryFilter();
        $this->applyFilters();
        $this->applyOrderBy();

        return $this->applyPagination();
    }

    public function render(): View
    {
        $rows = $this->getRows();
        $cssFramework = config('laravel-collection-table.css_framework');

        return view(config("laravel-collection-table.views.$cssFramework.table"), compact('rows'));
    }
}
