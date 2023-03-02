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
                    <div class="form-group" x-data="{
                        alertState: @entangle('wallet_uuid')
                    }">
                        <label>Parent</label>
                        <div wire:ignore>
                            <select class="form-control" name="parent_id" id="input_wallet-parent_id" placeholder="Search Wallet Parent Data" wire:model.lazy="wallet_parent">
                                <option value="">Select Wallet Parent Data</option>
                            </select>
                        </div>
                        <small class=" tw__text-xs tw__italic text-muted" x-show="alertState">*You can change Wallet Parent on Re-order feature</small>
                    </div>
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control @error('wallet_name') is-invalid @enderror" name="name" id="input_wallet-name" placeholder="Wallet Name" wire:model.lazy="wallet_name">
                        @error('wallet_name')
                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2" x-data="{
                        state: @entangle('wallet_parent')
                    }">
                        <input class="form-check-input" type="checkbox" id="input_wallet-keep_parent" wire:model.lazy="wallet_keepparent" x-bind:disabled="!(state) || !(state.value != '')">
                        <label class="form-check-label tw__m-0" for="input_wallet-keep_parent">Keep selected parent</label>
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
        // Modal Show
        window.addEventListener('walletModal-modalShow', (e) => {
            console.log('Event by Livewire');

            parentChoice.enable();
            if(@this.wallet_uuid){
                parentChoice.disable();
            }

            let el = document.getElementById('wallet-modal');
            var myModal = new bootstrap.Modal(el);
            myModal.show();

            el.addEventListener('hidden.bs.modal', (e) => {
                @this.closeModal()
            });
            el.addEventListener('shown.bs.modal', (e) => {
                document.getElementById('input_wallet-name').focus();
            });
        });
        // Modal Hide
        window.addEventListener('walletModal-modalHide', (e) => {
            console.log('Event by Livewire / Modal Hide');

            let el = document.getElementById('wallet-modal');
            if(el.classList.contains('show')){
                console.log('Please hide this modal');

                var myModal = bootstrap.Modal.getInstance(el);
                myModal.hide();
            }
        });
        // Clear Field
        window.addEventListener('walletModal-clearField', (e) => {
            parentChoice.setChoiceByValue('');
        });

        let parentChoice = null;
        document.addEventListener('DOMContentLoaded', (e) => {
            var element = document.getElementById('input_wallet-parent_id');
            parentChoice = new Choices(element, {
                allowHTML: true,
                searchPlaceholderValue: 'Search Wallet Parent Data',
                shouldSort: false
            });
            parentChoice.passedElement.element.addEventListener('showDropdown', (e) => {
                console.log(e);
                parentChoice.clearChoices();

                @this.fetchMainWallet();
                let data = @this.walletList_data;
                let selectedOption = @this.wallet_parent;
                let selectOption = [];

                // Placeholder
                if (parentChoice.passedElement.element.length <= 1) {
                    parentChoice.setChoices([{value: '', label: 'Select Wallet Parent Data', placeholder: true}], 'value', 'label', true);
                }
                // Push data to selection
                data.forEach((val, index) => {
                    selectOption.push({
                        value: val.uuid,
                        label: val.name
                    });
                });

                parentChoice.setValue(selectOption);
                console.log(selectedOption);
                if(selectedOption){
                    parentChoice.setChoiceByValue(selectedOption.value);
                } else {
                    parentChoice.setChoiceByValue('');
                }
            });
            parentChoice.passedElement.element.addEventListener('change', (e) => {
                if(parentChoice.getValue().value === ''){
                    @this.set('wallet_keepparent', false);
                }
            });
        });
    </script>
@endpush