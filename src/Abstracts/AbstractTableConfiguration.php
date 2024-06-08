<?php

namespace Pojow\LaravelCollectionTable\Abstracts;

use Pojow\LaravelCollectionTable\Table;

abstract class AbstractTableConfiguration
{
    public function setup(): Table
    {
        $table = $this->table();
        $table->columns($this->columns());

        return $table;
    }

    abstract protected function table(): Table;

    abstract protected function columns(): array;
}
