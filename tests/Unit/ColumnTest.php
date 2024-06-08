<?php

namespace Pojow\LaravelCollectionTable\Tests\Unit;

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Test;
use Pojow\LaravelCollectionTable\Column;
use Pojow\LaravelCollectionTable\Tests\TestCase;

class ColumnTest extends TestCase
{
    #[Test]
    public function it_can_make_column(): void
    {
        $column = Column::make('test');

        $defaultColumn = [
            'attribute' => 'test',
            'title' => 'validation.attributes.test',
            'searchable' => false,
            'sortable' => false,
            'sortClosure' => null,
        ];

        $this->assertEquals($defaultColumn, [
            'attribute' => $column->getAttribute(),
            'title' => $column->getTitle(),
            'searchable' => $column->getSearchable(),
            'sortable' => $column->getSortable(),
            'sortClosure' => $column->getSortClosure(),
        ]);
    }

    #[Test]
    public function it_can_set_column_title(): void
    {
        $column = Column::make('test')->title('Test title');

        $this->assertEquals('Test title', $column->getTitle());
    }

    #[Test]
    public function it_can_set_column_format(): void
    {
        $model = new class extends Model
        {
            protected $fillable = ['test', 'other'];
        };

        $testElementModel = new $model([
            'test' => 'testModel',
            'other' => 'otherModel',
        ]);

        $testElement = [
            'test' => 'test',
            'other' => 'other',
        ];

        $column = Column::make('test');

        // No formatter
        $this->assertEquals('test', $column->getValue($testElement));
        $this->assertEquals('testModel', $column->getValue($testElementModel));

        // Closure formatter
        $column->format(fn (array $element) => $element['test'] . ' ' . $element['other']);
        $this->assertEquals('test other', $column->getValue($testElement));

        $column->format(fn (array $element) => $element['test'] . '<br/>' . $element['other'], false);
        $this->assertEquals('test<br/>other', $column->getValue($testElement));

        /** @phpstan-ignore-next-line */
        $column->format(fn (Model $model) => $model->test . ' ' . $model->other);
        $this->assertEquals('testModel otherModel', $column->getValue($testElementModel));

        /** @phpstan-ignore-next-line */
        $column->format(fn (Model $model) => $model->test . '<br/>' . $model->other, false);
        $this->assertEquals('testModel<br/>otherModel', $column->getValue($testElementModel));
    }

    #[Test]
    public function it_can_set_column_searchable(): void
    {
        $column = Column::make('test')->searchable();

        $this->assertTrue($column->getSearchable());
    }
}
