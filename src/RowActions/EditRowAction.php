<?php

namespace Pojow\LaravelCollectionTable\RowActions;

use Illuminate\Support\Facades\View;
use Pojow\LaravelCollectionTable\Abstracts\AbstractRowAction;

class EditRowAction extends AbstractRowAction
{
    public function __construct(protected string $routeName, protected string $attribute = 'id')
    {
        //
    }

    public function render(mixed $element): string
    {
        $cssFramework = config('laravel-collection-table.css_framework');

        return View::make(
            config("laravel-collection-table.views.$cssFramework.row_actions.edit"), [
                'url' => route($this->routeName, $element[$this->attribute]),
            ]
        )->render();
    }
}
