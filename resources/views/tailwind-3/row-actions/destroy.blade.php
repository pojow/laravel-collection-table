<button class="text-red-600 mx-1 hover:opacity-80 transition-opacity"
        id="open-destroy-modal-{{ $id }}"
        type="button"
        title="{{ __('Destroy') }}">
    {!! view(config('laravel-collection-table.icon.destroy'))->render() !!}
</button>

<div class="fixed top-0 left-0 h-screen w-screen opacity-0 pointer-events-none transition-opacity" id="destroy-modal-{{ $id }}">
    <div class="w-full h-full bg-black bg-opacity-50 p-6">
        <div class="rounded bg-white max-w-2xl mx-auto"
             id="destroy-modal-content-{{ $id }}">
            <div class="text-red-600 text-center font-bold border-b p-5">
                {{ __('Are you sure you want to delete the selected item?') }}
            </div>
            <div class="grid place-content-center p-5 text-white">
                <form action="{{ $url }}" method="POST">
                    @method('DELETE')
                    @csrf
                    <button id="close-destroy-modal-{{ $id }}"
                            type="button"
                            class="bg-gray-500 py-2.5 px-4 rounded shadow-sm text-sm hover:opacity-80 transition-opacity">
                        {{ __('Cancel') }}
                    </button>
                    <button type="submit"
                            class="bg-primary py-2.5 px-4 rounded shadow-sm text-sm hover:opacity-80 transition-opacity">
                        {{ __('Confirm') }}
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    const modal{{ $id }} = document.getElementById('destroy-modal-{{ $id }}');
    const modalContent{{ $id }} = document.getElementById('destroy-modal-content-{{ $id }}');
    const openModalButton{{ $id }} = document.getElementById('open-destroy-modal-{{ $id }}');
    const closeModalButton{{ $id }} = document.getElementById('close-destroy-modal-{{ $id }}');

    if (modal{{ $id }} && modalContent{{ $id }} && openModalButton{{ $id }} && closeModalButton{{ $id }}) {
        const openModal{{ $id }} = () => {
            if (! modal{{ $id }}.classList.contains('opacity-0') || ! modal{{ $id }}.classList.contains('pointer-events-none')) {
                return;
            }
            modal{{ $id }}.classList.remove('opacity-0');
            modal{{ $id }}.classList.remove('pointer-events-none');
        }

        const closeModal{{ $id }} = () => {
            if (modal{{ $id }}.classList.contains('opacity-0') && modal{{ $id }}.classList.contains('pointer-events-none')) {
                return;
            }

            modal{{ $id }}.classList.add('opacity-0');
            modal{{ $id }}.classList.add('pointer-events-none');
        }

        openModalButton{{ $id }}.addEventListener('click', (event) => {
            event.preventDefault();
            openModal{{ $id }}();
        });

        modal{{ $id }}.addEventListener('click', (event) => {
            event.preventDefault();
            closeModal{{ $id }}();
        });

        modalContent{{ $id }}.addEventListener('click', (event) => {
            event.preventDefault();
            event.stopPropagation();
        });

        closeModalButton{{ $id }}.addEventListener('click', (event) => {
            event.preventDefault();
            closeModal{{ $id }}();
        });

        document.addEventListener('keydown', (event) => {
            const key = event.key;
            if (key === 'Escape' || key === 'Esc') {
                closeModal{{ $id }}();
            }
        })
    }
</script>
