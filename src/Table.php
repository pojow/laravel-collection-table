<?php

namespace Pojow\LaravelCollectionTable;

use Illuminate\Support\Collection;

class Table
{
    protected Collection $collection;

    protected Collection $columns;

    protected Collection $rowActions;

    protected Collection $filters;

    protected ?array $rowsPerPage;

    public function __construct()
    {
        $this->columns = collect();
        $this->rowActions = collect();
        $this->filters = collect();
    }

    public static function make(): self
    {
        return new self();
    }

    public function collection(Collection $collection): self
    {
        $this->collection = $collection;

        return $this;
    }

    public function getCollection(): Collection
    {
        return $this->collection;
    }

    public function rowsPerPage(array $rowsPerPage): self
    {
        $this->rowsPerPage = $rowsPerPage;

        return $this;
    }

    public function getRowsPerPage(): array
    {
        return $this->rowsPerPage ?? config('laravel-collection-table.rows_per_page');
    }

    public function columns(array $columns): self
    {
        $this->columns = collect($columns);

        return $this;
    }

    public function getColumns(): Collection
    {
        return $this->columns;
    }

    public function rowActions(array $rowActions): self
    {
        $this->rowActions = collect($rowActions);

        return $this;
    }

    public function getRowActions(): Collection
    {
        return $this->rowActions;
    }

    public function filters(array $filters): self
    {
        $this->filters = collect($filters);

        return $this;
    }

    public function getFilters(): Collection
    {
        return $this->filters;
    }
}
