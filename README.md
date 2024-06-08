![Laravel Collection Table illustration](/docs/laravel-collection-table.png)
<p style="text-align: center;">
    <a href="https://github.com/pojow/laravel-collection-table/releases" title="Latest Stable Version">
        <img src="https://img.shields.io/github/release/pojow/laravel-collection-table.svg?style=flat-square" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/pojow/laravel-collection-table" title="Total Downloads">
        <img src="https://img.shields.io/packagist/dt/pojow/laravel-collection-table.svg?style=flat-square" alt="Total Downloads">
    </a>
    <a href="https://github.com/pojow/laravel-collection-table/actions" title="Build Status">
        <img src="https://github.com/pojow/laravel-collection-table/actions/workflows/ci.yml/badge.svg" alt="Build Status">
    </a>
    <a href="https://coveralls.io/github/pojow/laravel-collection-table?branch=main" title="Coverage Status">
        <img src="https://coveralls.io/repos/github/pojow/laravel-collection-table/badge.svg?branch=main" alt="Coverage Status">
    </a>
    <a href="/LICENSE.md" title="License: MIT">
        <img src="https://img.shields.io/badge/License-MIT-blue.svg" alt="License: MIT">
    </a>
</p>


Our package allows you to generate functional HTML tables from a Laravel collection. The generated tables support a variety of features, including filtering, sorting, searching, and actions.

This lightweight package is designed for developers, providing easy customization of tables. You can effortlessly create new filters, column formats, and more to suit your specific needs.

The package can be used with the following UI frameworks:
* Bootstrap 5
* TailwindCSS 3 (beta)
* Custom : All views in the package can be overridden, allowing you to manage the display of your tables exactly as you wish.


## Compatibility

| Laravel                        | PHP                             | Package |
|--------------------------------|---------------------------------|---------|
| ^11.0 | 8.2.* &#124; 8.3.* | ^0.1    |

## Usage example
Create your table with the following command:

```bash
php artisan make:table UsersTable 
```


Configure your table in the `UsersTable` generated class, which can be found in the `app\Tables` directory:

```php
namespace App\Tables;

use App\Models\User;
use Pojow\LaravelCollectionTable\Abstracts\AbstractTableConfiguration;
use Pojow\LaravelCollectionTable\Column;
use Pojow\LaravelCollectionTable\Filters\SelectFilter;
use Pojow\LaravelCollectionTable\RowActions\DestroyRowAction;
use Pojow\LaravelCollectionTable\RowActions\EditRowAction;
use Pojow\LaravelCollectionTable\Table;

class UsersTable extends AbstractTableConfiguration
{
    protected function table(): Table
    {
        return Table::make()
            ->collection(User::all())
            ->filters([
                (new SelectFilter(__('Role'), 'role'))->options(['user', 'administrator']),
            ])
            ->rowActions([
                new EditRowAction('user.edit', 'id'),
                new DestroyRowAction('user.destroy', 'id'),
            ]);
    }

    protected function columns(): array
    {
        return [
            Column::make('username')
                ->searchable()
                ->sortable(),
            Column::make('first_name')
                ->searchable()
                ->sortable(),
            Column::make('last_name'),
            Column::make('email')
                ->searchable()
                ->sortable()
                ->format(fn (User $user) => "<a href='mailto:{$user->email}'>{$user->email}</a>", false),
        ];
    }
}
```
And display it in a view:

```blade
<x:laravel-collection-table :config="App\Tables\UsersTable::class"/>
```

See the result
![Table usage example](/docs/table-usage-example.png)

## Table of Contents

* [Installation](#installation)
* [Configuration](#configuration)
* [Views](#views)
* [Features](#features)
    * [Filters](#todo) : Documentation not available
    * [Column formatters](#todo) : Documentation not available
    * [Row actions](#todo) : Documentation not available
* [Testing](#testing)
* [Changelog](#changelog)
* [Contributing](#contributing)
* [Credits](#credits)
* [Licence](#license)

## Installation

You can install the package via composer:

```bash
composer require pojow/laravel-collection-table
```

## Configuration

You can publish the config file with:

```bash
php artisan vendor:publish --tag=laravel-collection-table:config
```

Among its configurations, this package allows you to choose which UI framework will be use.

Please note that you'll have to install and configure the UI framework you want to use before using this package, or you must override all the views and create your own.

## Views

You can publish the package views to customize them if necessary:

```bash
php artisan vendor:publish --tag=laravel-collection-table:views
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.


## Credits

- [Pojow](https://github.com/pojow)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
