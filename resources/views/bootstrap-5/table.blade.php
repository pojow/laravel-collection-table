<div class="table-responsive">
    <table class="table table-borderless">
        <thead>
        <tr>
            <td class="px-0" {!! $columnsCount > 1 ? 'colspan="' . $columnsCount . '"' : null !!}>
                <form action="" method="GET">
                    <div class="d-flex flex-column flex-xl-row">
                        <div class="flex-fill">
                            @if($searchableLabels)
                                <div class="flex-fill pe-xl-3 py-1">
                                    <div class="input-group">
                                            <span id="search-for-rows" class="input-group-text">
                                                {!! view(config('laravel-collection-table.icon.query'))->render() !!}
                                            </span>
                                        <input
                                            class="form-control"
                                            name="query"
                                            placeholder="{{ __('Search by:') }} {{ $searchableLabels }}"
                                            aria-label="{{ __('Search by:') }} {{ $searchableLabels }}"
                                            value="{{ request()->get('query') }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                        @foreach($table->getFilters() as $filter)
                            <div class="me-xl-3">
                                {!! $filter->render() !!}
                            </div>
                        @endforeach
                        @if(count($table->getRowsPerPage()) > 1)
                            <div class="me-xl-3">
                                <div class="d-flex justify-content-between py-1">
                                    <div class="input-group">
                                            <span id="rows-number-per-page-icon" class="input-group-text text-secondary">
                                                {!! view(config('laravel-collection-table.icon.rows_number'))->render() !!}
                                            </span>
                                        <select name="per_page" class="form-select"
                                                aria-label="{{ __('Number of rows per page') }}">
                                            <option value="" disabled>{{ __('Number of rows per page') }}</option>
                                            @foreach($table->getRowsPerPage() as $rowsPerPageOption)
                                                <option
                                                    value="{{ $rowsPerPageOption }}"{{ (int) request()->get('per_page') === $rowsPerPageOption ? ' selected' : null}}>
                                                    {{ $rowsPerPageOption }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        @endif
                        <div class="d-flex align-items-start pt-1">
                            <button class="btn btn-md btn-primary"
                                    type="submit"
                                    title="{{ __('Search') }}">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <tr class="table-light border-top border-bottom">
            @foreach($table->getColumns() as $column)
                @if($column->getSortable())
                    @php($orderQuery = $column->getOrderQuery())
                    <th class="align-middle" scope="col">
                        <a href="{{ $orderQuery }}"
                           class="d-flex align-items-center flex-nowrap">
                            <span>{{ $column->getTitle() }}</span>
                            @if(request()->query('order_by') === $column->getAttribute())
                                <span style="{{ request()->query('order_dir') === 'ASC' ? '' : 'transform: rotate(180deg)' }}">
                                    {!! view('laravel-collection-table::svg.chevron-down')->render() !!}
                                </span>
                            @else
                                <span>
                                    {!! view('laravel-collection-table::svg.double-chevron')->render() !!}
                                </span>
                            @endif
                        </a>
                    </th>
                @else
                    <th class="align-middle" scope="col">
                        {{ $column->getTitle() }}
                    </th>
                @endif
            @endforeach
            @if($table->getRowActions()->isNotEmpty())
                <th class="align-middle text-end" scope="col">
                    {{ __('Actions') }}
                </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr class="border-bottom">
                @foreach($table->getColumns() as $column)
                    <td class="align-middle">
                        {!! $column->getValue($row) !!}
                    </td>
                @endforeach
                @if($table->getRowActions()->isNotEmpty())
                    <td class="align-middle text-end">
                        <div class="d-flex align-items-center justify-content-end">
                            @foreach($table->getRowActions() as $rowAction)
                                {!! $rowAction->render($row) !!}
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <tr class="border-bottom">
                <th class="fw-normal text-center align-middle p-3"
                    scope="row"{!! $columnsCount > 1 ? ' colspan="' .  $columnsCount . '"' : null !!}>
                    {{ __('No results were found.') }}
                </th>
            </tr>
        @endforelse
        </tbody>
        <tfoot class="table-light">
        <tr>
            <td class="align-middle"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                <div class="d-flex flex-wrap justify-content-end mt-3">
                    {!! $rows->appends(request()->query())->links(config('laravel-collection-table.views.pagination')) !!}
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
