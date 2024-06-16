<?php

return [
    /** Set all the displayed action icons (components). */
    'icon' => [
        'filter' => 'laravel-collection-table::svg.filter',
        'destroy' => 'laravel-collection-table::svg.delete',
        'query' => 'laravel-collection-table::svg.type',
        'rows_number' => 'laravel-collection-table::svg.list-ordered',
        'reset' => 'laravel-collection-table::svg.reset',
        'edit' => 'laravel-collection-table::svg.edit',
    ],

    /** The default number-of-rows-per-page-select options. */
    'rows_per_page' => [10, 25, 50, 75, 100],

    /** Can be bootstrap-5 or tailwind-3 */
    'css_framework' => 'bootstrap-5',

    /** Override default package views. */
    'views' => [
        'bootstrap-5' => [
            'table' => 'laravel-collection-table::bootstrap-5.table',
            'pagination' => 'pagination::bootstrap-5',
            'filters' => [
                'select' => 'laravel-collection-table::bootstrap-5.filters.select',
            ],
            'row_actions' => [
                'edit' => 'laravel-collection-table::bootstrap-5.row-actions.edit',
                'destroy' => 'laravel-collection-table::bootstrap-5.row-actions.destroy',
            ],
        ],
        'tailwind-3' => [
            'table' => 'laravel-collection-table::tailwind-3.table',
            'pagination' => 'pagination::tailwind-3',
            'filters' => [
                'select' => 'laravel-collection-table::tailwind-3.filters.select',
            ],
            'row_actions' => [
                'edit' => 'laravel-collection-table::tailwind-3.row-actions.edit',
                'destroy' => 'laravel-collection-table::tailwind-3.row-actions.destroy',
            ],
        ],
    ],
];
