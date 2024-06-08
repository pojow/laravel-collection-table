<?php

namespace Pojow\LaravelCollectionTable\Tests\Unit;

use Illuminate\Support\Facades\View as ViewFacade;
use Illuminate\Testing\Constraints\SeeInOrder;
use PHPUnit\Framework\Attributes\Test;
use Pojow\LaravelCollectionTable\Filters\SelectFilter;
use Pojow\LaravelCollectionTable\Tests\TestCase;

class SelectFilterTest extends TestCase
{
    protected function renderSelectSanitized(SelectFilter $selectFilter): string
    {
        $sanitized = preg_replace('/\n/', '', $selectFilter->render());
        $sanitized = preg_replace('/\s{2,}/', ' ', $sanitized);
        $sanitized = preg_replace('/\s?(<)\s?/', '$1', $sanitized);

        return preg_replace('/\s?(>)\s?/', '$1', $sanitized);
    }

    protected function seeInSelect(SelectFilter $selectFilter, array $expected): void
    {
        $matches = (new SeeInOrder($this->renderSelectSanitized($selectFilter)))->matches($expected);

        $this->assertTrue($matches);
    }

    protected function notSeeInSelect(SelectFilter $selectFilter, array $expected): void
    {
        $matches = (new SeeInOrder($this->renderSelectSanitized($selectFilter)))->matches($expected);

        $this->assertFalse($matches);
    }

    protected function createFakeView(string $content): string
    {
        $tempDirectory = sys_get_temp_dir();
        ViewFacade::addLocation($tempDirectory);
        $tempFileInfo = pathinfo(tempnam($tempDirectory, 'test-collection-table-'));
        $tempFile = $tempFileInfo['dirname'] . '/' . $tempFileInfo['filename'] . '.blade.php';
        file_put_contents($tempFile, $content);

        return $tempFileInfo['filename'];
    }

    #[Test]
    public function it_can_create_select_filter(): void
    {
        $selectFilter = new SelectFilter('test', 'test');

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_create_select_filter_with_query_value(): void
    {
        request()->query->add(['test' => 1]);
        $this->assertArrayHasKey('test', request()->query());
        $this->assertEquals(1, request()->query('test'));

        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ]);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '<option value="1" selected>test1</option>',
            '<option value="2">test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_create_select_filter_with_empty_query_value(): void
    {
        request()->query->add(['test' => null]);
        $this->assertArrayHasKey('test', request()->query());
        $this->assertEquals(null, request()->query('test'));

        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ]);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '<option value="1">test1</option>',
            '<option value="2">test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_select_options(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ]);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '<option value="1">test1</option>',
            '<option value="2">test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_select_label(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))->label('Test Label');

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="Test Label">',
            '<option value="">Test Label</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_selected_value(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ])
            ->selected(1);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '<option value="1" selected>test1</option>',
            '<option value="2">test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_select_as_multiple(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ])
            ->multiple();

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select multiple name="test[]" class="form-select" aria-label="test">',
            '<option value="1">test1</option>',
            '<option value="2">test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_multiple_select_value(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ])
            ->multiple()
            ->selected(1);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select multiple name="test[]" class="form-select" aria-label="test">',
            '<option value="1" selected>test1</option>',
            '<option value="2">test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_multiple_select_multiple_values(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->options([
                1 => 'test1',
                2 => 'test2',
            ])
            ->multiple()
            ->selected([1, 2]);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select multiple name="test[]" class="form-select" aria-label="test">',
            '<option value="1" selected>test1</option>',
            '<option value="2" selected>test2</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_replace_select_icon(): void
    {
        $fakeIcon = $this->createFakeView('<div>Test icon</div>');

        $selectFilter = (new SelectFilter('test', 'test'))->icon($fakeIcon);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<div>Test icon</div>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $notExpected = [
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
        ];

        $this->seeInSelect($selectFilter, $expected);
        $this->notSeeInSelect($selectFilter, $notExpected);
    }

    #[Test]
    public function it_can_remove_select_icon(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))->icon(null);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $notExpected = [
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
        ];

        $this->seeInSelect($selectFilter, $expected);
        $this->notSeeInSelect($selectFilter, $notExpected);
    }

    #[Test]
    public function it_can_add_select_classes(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->selectClasses(['test-class', 'test-class-2']);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select test-class test-class-2" aria-label="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_replace_select_classes(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->selectClasses(['test-class', 'test-class-2'], true);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="test-class test-class-2" aria-label="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_add_select_attributes(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->selectAttributes([
                'data-test',
                'data-test-2' => false,
                'data-test-3' => 'test',
            ]);

        $expected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test" data-test data-test-2 data-test-3="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, $expected);
    }

    #[Test]
    public function it_can_set_select_custom_view(): void
    {
        $customView = $this->createFakeView('<div>Test view</div>');

        $selectFilter = (new SelectFilter('test', 'test'))
            ->customView($customView);

        $notExpected = [
            '<div class="py-1">',
            '<div class="input-group">',
            '<span class="input-group-text text-secondary">',
            '<svg',
            '</svg>',
            '</span>',
            '<select name="test" class="form-select" aria-label="test">',
            '<option value="">test</option>',
            '</select>',
            '</div>',
            '</div>',
        ];

        $this->seeInSelect($selectFilter, ['<div>Test view</div>']);
        $this->notSeeInSelect($selectFilter, $notExpected);
    }

    #[Test]
    public function it_can_validate_when_nothing_selected(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'));

        $elements = [
            ['test' => 'test1'],
            ['test' => 'test2'],
        ];
        $filteredElements = array_filter($elements, fn (array $element) => $selectFilter->validate($element));

        $this->assertEquals($elements, $filteredElements);
    }

    #[Test]
    public function it_can_validate_when_selected(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->selected('test1');

        $elements = [
            ['test' => 'test1'],
            ['test' => 'test2'],
        ];
        $filteredElements = array_filter($elements, fn (array $element) => $selectFilter->validate($element));
        $expected = [['test' => 'test1']];

        $this->assertEquals($expected, $filteredElements);
    }

    #[Test]
    public function it_can_validate_multiple_selected(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->multiple()
            ->selected('test1');

        $elements = [
            ['test' => 'test1'],
            ['test' => 'test2'],
        ];
        $filteredElements = array_filter($elements, fn (array $element) => $selectFilter->validate($element));
        $expected = [['test' => 'test1']];

        $this->assertEquals($expected, $filteredElements);
    }

    #[Test]
    public function it_can_validate_multiple_when_multiple_selected(): void
    {
        $selectFilter = (new SelectFilter('test', 'test'))
            ->multiple()
            ->selected(['test1', 'test2']);

        $elements = [
            ['test' => 'test1'],
            ['test' => 'test2'],
            ['test' => 'test3'],
        ];
        $filteredElements = array_filter($elements, fn (array $element) => $selectFilter->validate($element));
        $expected = [
            ['test' => 'test1'],
            ['test' => 'test2'],
        ];

        $this->assertEquals($expected, $filteredElements);
    }
}
