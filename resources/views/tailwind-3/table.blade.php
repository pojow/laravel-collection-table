<div class="overflow-x-scroll overflow-y-hidden">
    <table class="table-auto border-collapse mb-0 w-full">
        <thead>
        <tr>
            <td class="py-3" {!! $columnsCount > 1 ? 'colspan="' . $columnsCount . '"' : null !!}>
                <form action="" method="GET">
                    <div class="flex flex-col xl:flex-row">
                        <div class="flex grow shrink">
                            @if($searchableLabels)
                                <div class="flex grow shrink xl:pr-3 py-1">
                                    <div class="flex items-stretch w-full rounded text-sm shadow-sm">
                                            <span class="bg-gray-100 text-gray-600 px-2.5 grid place-content-center border border-r-0 rounded-l">
                                                {!! view(config('laravel-collection-table.icon.query'))->render() !!}
                                            </span>
                                        <input
                                            class="py-2.5 px-3 w-full border rounded-r outline-none focus:border-primary transition-colors"
                                            name="query"
                                            placeholder="{{ __('Search by:') }} {{ $searchableLabels }}"
                                            aria-label="{{ __('Search by:') }} {{ $searchableLabels }}"
                                            value="{{ request()->get('query') }}">
                                    </div>
                                </div>
                            @endif
                        </div>
                        @foreach($table->getFilters() as $filter)
                            <div class="xl:mr-3">
                                {!! $filter->render() !!}
                            </div>
                        @endforeach
                        @if(count($table->getRowsPerPage()) > 1)
                            <div class="xl:mr-3">
                                <div class="flex justify-between py-1">
                                    <div class="flex items-stretch w-full relative text-sm rounded shadow-sm">
                                            <span class="bg-gray-100 text-gray-600 px-2.5 grid place-content-center border border-r-0 rounded-l">
                                                {!! view(config('laravel-collection-table.icon.rows_number'))->render() !!}
                                            </span>
                                        <select name="per_page"
                                                class="appearance-none py-2.5 pr-9 pl-3 bg-transparent border rounded-r focus:border-primary transition-colors cursor-pointer"
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
                        <div class="flex items-start pt-1">
                            <button class="bg-primary text-white hover:opacity-80 transition-opacity items-center justify-center whitespace-nowrap py-2.5 px-4 rounded shadow-sm text-sm"
                                    type="submit"
                                    title="{{ __('Search') }}">
                                {{ __('Search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </td>
        </tr>
        <tr class="border-t border-b text-gray-500 text-xs uppercase">
            @foreach($table->getColumns() as $column)
                @if($column->getSortable())
                    @php($orderQuery = $column->getOrderQuery())
                    <th class="text-start p-2" scope="col">
                        <a href="{{ $orderQuery }}"
                           class="flex items-center gap-0.5 flex-nowrap hover:text-primary transition-colors">
                            <span>{{ $column->getTitle() }}</span>
                            <span class="{{ str_contains($orderQuery, 'order_dir') ? '' : 'rotate-180 pt-0.5' }}">
                                {!! view(str_contains($orderQuery, 'order_dir=ASC') ? 'laravel-collection-table::svg.double-chevron' : 'laravel-collection-table::svg.chevron-down')->render() !!}
                            </span>
                        </a>
                    </th>
                @else
                    <th class="text-start p-2" scope="col">
                        {{ $column->getTitle() }}
                    </th>
                @endif
            @endforeach
            @if($table->getRowActions()->isNotEmpty())
                <th class="text-end p-2" scope="col">
                    {{ __('Actions') }}
                </th>
            @endif
        </tr>
        </thead>
        <tbody>
        @forelse($rows as $row)
            <tr class="border-b text-sm">
                @foreach($table->getColumns() as $column)
                    <td class="align-middle p-2">
                        {!! $column->getValue($row) !!}
                    </td>
                @endforeach
                @if($table->getRowActions()->isNotEmpty())
                    <td class="align-middle text-end p-2">
                        <div class="flex items-center justify-end">
                            @foreach($table->getRowActions() as $rowAction)
                                {!! $rowAction->render($row) !!}
                            @endforeach
                        </div>
                    </td>
                @endif
            </tr>
        @empty
            <tr class="border-b">
                <th class="font-normal text-center align-middle p-2"
                    scope="row"{!! $columnsCount > 1 ? ' colspan="' .  $columnsCount . '"' : null !!}>
                    {{ __('No results were found.') }}
                </th>
            </tr>
        @endforelse
        </tbody>
        <tfoot>
        <tr>
            <td class="align-middle"{!! $columnsCount > 1 ? ' colspan="' . $columnsCount . '"' : null !!}>
                <div class="mt-3">
                    {!! $rows->links(config('laravel-collection-table.views.pagination')) !!}
                </div>
            </td>
        </tr>
        </tfoot>
    </table>
</div>
