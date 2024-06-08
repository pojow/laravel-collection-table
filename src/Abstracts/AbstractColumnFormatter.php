<?php

namespace Pojow\LaravelCollectionTable\Abstracts;

abstract class AbstractColumnFormatter
{
    abstract public function render(mixed $element): string;
}
