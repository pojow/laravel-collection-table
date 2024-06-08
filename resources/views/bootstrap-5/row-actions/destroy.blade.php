<a class="link-danger px-1"
        type="button"
        data-bs-toggle="modal" data-bs-target="#destroyConfirmation{{ $id }}"
        title="{{ __('Destroy') }}">
    {!! view(config('laravel-collection-table.icon.destroy'))->render() !!}
</a>

<div class="modal modal-md fade" id="destroyConfirmation{{ $id }}" tabindex="-1" aria-labelledby="destroyConfirmationModal" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-body text-danger text-center fw-bolder">
                {{ __('Are you sure you want to delete the selected item?') }}
            </div>
            <div class="modal-footer d-flex justify-content-center">
                <form action="{{ $url }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                    <button type="submit" class="btn btn-primary">{{ __('Confirm') }}</button>
                </form>
            </div>
        </div>
    </div>
</div>
