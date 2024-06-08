<?php

namespace Pojow\LaravelCollectionTable\Tests\Unit;

use Illuminate\Support\Facades\Route;
use Illuminate\Testing\Constraints\SeeInOrder;
use PHPUnit\Framework\Attributes\Test;
use Pojow\LaravelCollectionTable\Abstracts\AbstractRowAction;
use Pojow\LaravelCollectionTable\RowActions\DestroyRowAction;
use Pojow\LaravelCollectionTable\RowActions\EditRowAction;
use Pojow\LaravelCollectionTable\Tests\TestCase;

class RowActionTest extends TestCase
{
    protected function renderRowActionSanitized(AbstractRowAction $rowAction, array $element): string
    {
        $sanitized = preg_replace('/\n/', '', $rowAction->render($element));
        $sanitized = preg_replace('/\s{2,}/', ' ', $sanitized);
        $sanitized = preg_replace('/\s?(<)\s?/', '$1', $sanitized);

        return preg_replace('/\s?(>)\s?/', '$1', $sanitized);
    }

    protected function seeInRowAction(AbstractRowAction $rowAction, array $element, array $expected): void
    {
        $matches = (new SeeInOrder($this->renderRowActionSanitized($rowAction, $element)))->matches($expected);

        $this->assertTrue($matches);
    }

    protected function setUp(): void
    {
        parent::setUp();
        Route::get('/test', fn () => abort(404))->name('test.route');
    }

    #[Test]
    public function it_can_render_edit_action(): void
    {
        $editAction = new EditRowAction('test.route');
        $editRoute = route('test.route', 1);

        $expected = [
            "<a class=\"link-primary px-1\" href=\"$editRoute\" title=\"Edit\">",
            '<svg',
            '</svg>',
            '</a>',
        ];

        $this->seeInRowAction($editAction, ['id' => 1], $expected);
    }

    #[Test]
    public function it_can_render_destroy_action(): void
    {
        $destroyAction = new DestroyRowAction('test.route');
        $destroyRoute = route('test.route', 1);

        $expected = [
            '<a class="link-danger px-1" type="button" data-bs-toggle="modal" data-bs-target="#destroyConfirmation1" title="Destroy">',
            '<svg',
            '</svg>',
            '</a>',
            '<div class="modal modal-md fade" id="destroyConfirmation1" tabindex="-1" aria-labelledby="destroyConfirmationModal" aria-hidden="true">',
            "<form action=\"$destroyRoute\" method=\"POST\">",
        ];

        $this->seeInRowAction($destroyAction, ['id' => 1], $expected);
    }
}
