@push('css')
    @include('layouts.plugins.choices-js.css')
@endpush

<div>
    {{-- Be like water. --}}
    <div class="modal fade" id="wallet-modal" tabindex="-1" aria-labelledby="wallet-modalTitle" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content tw__rounded-lg" wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="wallet-modalTitle">{{ $wallet_modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Parent</label>
                        <div wire:ignore>
                            <select class="form-control" name="parent_id" id="input_wallet-parent_id" placeholder="Search Wallet Parent Data" wire:model.lazy="wallet_parent">
                                <option value="">Select Wallet Parent Data</option>
                                <option value="sample">Sample Data</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control @error('wallet_name') is-invalid @enderror" name="name" id="input_wallet-name" placeholder="Wallet Name" wire:model.lazy="wallet_name">
                        @error('wallet_name')
                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2">
                        <input class="form-check-input" type="checkbox" id="input_wallet-keep_open" wire:model.lazy="wallet_keepopen">
                        <label class="form-check-label tw__m-0" for="input_wallet-keep_open">Keep the modal open</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tw__my-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary tw__my-0" id="btn_wallet-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    @include('layouts.plugins.choices-js.js')

    <script>
        window.addEventListener('walletModal-modalShow', (e) => {
            console.log('Event by Livewire');
            let el = document.getElementById('wallet-modal');
            var myModal = new bootstrap.Modal(el);
            myModal.show();

            el.addEventListener('hidden.bs.modal', (e) => {
                @this.closeModal()
            });
        });
        window.addEventListener('walletModal-modalHide', (e) => {
            console.log('Event by Livewire / Modal Hide');

            let el = document.getElementById('wallet-modal');
            if(el.classList.contains('show')){
                console.log('Please hide this modal');

                var myModal = bootstrap.Modal.getInstance(el);
                myModal.hide();
            }
        });

        document.addEventListener('DOMContentLoaded', (e) => {
            var element = document.getElementById('input_wallet-parent_id');
            new Choices(element, {
                allowHTML: true,
                placeholderValue: 'This is a placeholder set in the config',
                searchPlaceholderValue: 'Search Wallet Parent Data',
            });
        });
    </script>
@endpush