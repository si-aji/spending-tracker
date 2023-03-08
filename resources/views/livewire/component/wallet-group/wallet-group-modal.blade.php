@push('css')
    @include('layouts.plugins.choices-js.css')
@endpush

<div>
    {{-- Be like water. --}}
    <div class="modal fade" id="walletGroup-modal" tabindex="-1" aria-labelledby="walletGroup-modalTitle" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content tw__rounded-lg" wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="walletGroup-modalTitle">{{ $walletGroup_modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Name</label>
                        <input type="text" class="form-control @error('walletGroup_name') is-invalid @enderror" name="name" id="input_wallet_group-name" placeholder="Group Name" wire:model.lazy="walletGroup_name">
                        @error('walletGroup_name')
                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                        @enderror
                    </div>

                    <div class="form-group" x-data="{
                        alertState: @entangle('walletGroup_uuid')
                    }">
                        <label>Item</label>
                        <div wire:ignore>
                            <select class="form-control" name="wallet_item_id" id="input_wallet_group-wallet_item_id" placeholder="Search Wallet Data" multiple>
                                <option value="">Select Wallet Data</option>
                            </select>
                        </div>
                        <small class=" tw__text-xs tw__italic text-muted" x-show="alertState">*You can change Wallet Item on Group Detail page</small>
                    </div>

                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2">
                        <input class="form-check-input" type="checkbox" id="input_wallet_group-keep_open" wire:model.lazy="walletGroup_keepopen">
                        <label class="form-check-label tw__m-0" for="input_wallet_group-keep_open">Keep the modal open</label>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tw__my-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary tw__my-0" id="btn_wallet_group-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    @include('layouts.plugins.choices-js.js')

    <script>
        // Modal Show
        window.addEventListener('walletGroupModal-modalShow', (e) => {
            console.log('Event by Livewire');

            if((@this.walletGroupItem_data).length > 0){
                let option = [];
                (@this.walletGroupItem_data).forEach((val, index) => {
                    option.push({
                        value: val.uuid,
                        label: `${val.parent ? `${val.parent.name} - ` : ''}${val.name}`,
                    });
                });

                if(option.length > 0){
                    walletItemChoice.setValue(option);
                }
            }

            let el = document.getElementById('walletGroup-modal');
            var myModal = new bootstrap.Modal(el);
            myModal.show();

            el.addEventListener('hidden.bs.modal', (e) => {
                @this.closeModal()
            });
            el.addEventListener('shown.bs.modal', (e) => {
                document.getElementById('input_wallet_group-name').focus();
            });
        });
        // Modal Hide
        window.addEventListener('walletGroupModal-modalHide', (e) => {
            console.log('Event by Livewire / Modal Hide');

            let el = document.getElementById('walletGroup-modal');
            if(el.classList.contains('show')){
                console.log('Please hide this modal');

                var myModal = bootstrap.Modal.getInstance(el);
                myModal.hide();
            }
        });
        // Show Choices List
        window.addEventListener('walletChoice-showOption', (e) => {
            walletItemChoice.clearChoices();
            // Placeholder
            if (walletItemChoice.passedElement.element.length <= 1) {
                walletItemChoice.setChoices([{value: '', label: 'Select Wallet Data', placeholder: true}], 'value', 'label', true);
            }

            let data = @this.walletList_data;
            console.log(data);
            let selectOption = [];
            let selectedOption = [];

            if(walletItemChoice !== null){
                walletItemChoice.getValue().forEach((e, key) => {
                    selectedOption.push(e.value);
                });
            }

            data.forEach((val, index) => {
                let option = [];

                if(selectedOption.length > 0){
                    if(!(selectedOption.includes(val.uuid))){
                        option.push({
                            value: val.uuid,
                            label: val.name
                        });
                    }
                } else {
                    option.push({
                        value: val.uuid,
                        label: val.name
                    });
                }

                if(val.child){
                    (val.child).forEach((child, childIndex) => {
                        if (selectedOption.length > 0) {
                            if(!(selectedOption.includes(child.uuid))){
                                option.push({
                                    value: child.uuid,
                                    label: `${val.name} - ${child.name}`
                                });
                            }
                        } else {
                            option.push({
                                value: child.uuid,
                                label: `${val.name} - ${child.name}`
                            });
                        }
                    });
                }

                selectOption.push({
                    label: val.name,
                    id: index,
                    disabled: false,
                    choices: option
                });
            });
            walletItemChoice.setChoices(selectOption);
            // walletItemChoice.setChoiceByValue('');
        });
        // Clear Field
        window.addEventListener('walletGroupModal-clearField', (e) => {
            walletItemChoice.removeActiveItems();
        });

        const updateSelectedItem = () => {
            let selectedWallet = [];
            if(walletItemChoice !== null){
                walletItemChoice.getValue().forEach((e, key) => {
                    selectedWallet.push(e.value);
                });
            }

            @this.walletGroup_item = selectedWallet;
            console.log(selectedWallet);
        }

        let walletItemChoice = null;
        document.addEventListener('DOMContentLoaded', (e) => {
            var element = document.getElementById('input_wallet_group-wallet_item_id');
            walletItemChoice = new Choices(element, {
                allowHTML: true,
                searchPlaceholderValue: 'Search Wallet Data',
                shouldSort: false,
                removeItemButton: true,
                resetScrollPosition: false,
            });
            walletItemChoice.passedElement.element.addEventListener('showDropdown', (e) => {
                @this.call('showWalletOption');
            });
            walletItemChoice.passedElement.element.addEventListener('hideDropdown', (e) => {
                updateSelectedItem();
            });
            walletItemChoice.passedElement.element.addEventListener('removeItem', (e) => {
                updateSelectedItem();
            });
        });
    </script>
@endpush