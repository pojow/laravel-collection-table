<?php

namespace Pojow\LaravelCollectionTable\Abstracts;

abstract class AbstractFilter
{
    abstract public function validate(mixed $element): bool;

    abstract public function render(): string;
}
