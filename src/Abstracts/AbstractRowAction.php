<?php

namespace Pojow\LaravelCollectionTable\Abstracts;

abstract class AbstractRowAction
{
    abstract public function render(mixed $element): string;
}
