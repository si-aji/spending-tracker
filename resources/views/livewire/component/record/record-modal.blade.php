@push('css')
    @include('layouts.plugins.choices-js.css')
    @include('layouts.plugins.flatpickr.css')
@endpush

<div x-data="{
    recordType: @entangle('record_type'),
    extraType: @entangle('record_extra_type')
}">
    {{-- Nothing in the world is as soft and yielding as water. --}}
    <div class="modal fade" id="record-modal" tabindex="-1" aria-labelledby="record-modalTitle" aria-hidden="true" data-bs-focus="false" wire:ignore.self>
        <div class="modal-dialog modal-lg">
            <form class="modal-content tw__rounded-lg" id="record-modal_form">
                <div class="modal-header tw__border-slate-100">
                    <h5 class="modal-title" id="record-modalTitle">{{ $record_modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body tw__p-0">
                    <div class=" tw__grid tw__grid-flow-row lg:tw__grid-flow-col tw__grid-cols-2 lg:tw__grid-cols-4">
                        {{-- Left Side --}}
                        <div class=" tw__p-6 tw__col-span-2 tw__self-center">
                            {{-- Record Type --}}
                            <div class=" tw__text-center">
                                <div class="btn-group tw__mb-4 md:tw__mb-0" role="group" aria-label="Basic radio toggle button group">
                                    <input type="radio" class="btn-check" name="record_type" id="record_type-income" value="income" autocomplete="off" wire:model.lazy="record_type">
                                    <label class="btn btn-white px-3 mb-0" for="record_type-income">Income</label>
                                    <input type="radio" class="btn-check" name="record_type" id="record_type-transfer" value="transfer" autocomplete="off" wire:model.lazy="record_type">
                                    <label class="btn btn-white px-3 mb-0" for="record_type-transfer">Transfer</label>
                                    <input type="radio" class="btn-check" name="record_type" id="record_type-expense" value="expense" autocomplete="off"" wire:model.lazy="record_type">
                                    <label class="btn btn-white px-3 mb-0" for="record_type-expense">Expense</label>
                                </div>
                            </div>

                            {{-- Record Category --}}
                            <div class="form-group @error('record_category') tw__border-red-400 @enderror">
                                <label>Category</label>
                                <div style="border-color: inherit" wire:ignore>
                                    <select class="form-control" name="category" id="input_record-category" placeholder="Search Category Data" wire:model.lazy="record_category">
                                        <option value="">Select Category Data</option>
                                    </select>
                                </div>
                                @error('record_category')
                                    <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Record Wallet --}}
                            <div class="">
                                <div class="form-group @error('record_from_wallet') tw__border-red-400 @enderror">
                                    <label x-text="recordType === 'transfer' ? 'From' : 'Wallet'"></label>
                                    <div style="border-color: inherit" wire:ignore>
                                        <select class="form-control" name="from_wallet" id="input_record-from_wallet" placeholder="Search Wallet Data" wire:model.lazy="record_from_wallet">
                                            <option value="">Select Wallet Data</option>
                                        </select>
                                    </div>
                                    @error('record_from_wallet')
                                        <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="button" class="btn btn-sm btn-secondary tw__mb-2" x-show="recordType === 'transfer'" x-on:click="$wire.emit('switchTransferWallet')">
                                    <span class=" tw__flex tw__items-center tw__gap-2">
                                        <span class=" tw__rotate-90"><i class="fa-solid fa-right-left"></i></span>
                                        <span>Switch</span>
                                    </span>
                                </button>
                                <div x-show="recordType === 'transfer'">
                                    <div class="form-group @error('record_from_wallet') tw__border-red-400 @enderror">
                                        <label x-text="recordType === 'transfer' ? 'To' : 'Wallet'"></label>
                                        <div style="border-color: inherit" wire:ignore>
                                            <select class="form-control" name="to_wallet" id="input_record-to_wallet" placeholder="Search Wallet Data" wire:model.lazy="record_to_wallet">
                                                <option value="">Select Wallet Data</option>
                                            </select>
                                        </div>
                                        @error('record_to_wallet')
                                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            {{-- Record Amount --}}
                            <div class="form-group @error('record_amount') tw__border-red-400 @enderror">
                                <label>Amount</label>
                                <div class="input-group mb-3" style="border-color: inherit" wire:ignore.self>
                                    <span class="input-group-text"style="border-color: inherit" >
                                        <i class="fa-solid" x-bind:class="recordType === 'expense' ? 'fa-minus' : (recordType === 'income' ? 'fa-plus' : 'fa-plus-minus')"></i>
                                    </span>
                                    <input type="text" inputmode="numeric" class="form-control" placeholder="Record Amount" id="input_record-amount" style="border-color: inherit" >
                                </div>
                                @error('record_amount')
                                    <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                @enderror
                            </div>

                            {{-- Extra & Final Amount --}}
                            <div class=" tw__flex tw__w-full tw__flex-col lg:tw__flex-row tw__gap-4">
                                <div class="form-group tw__mb-0 tw__w-full">
                                    <label>Extra</label>
                                    <input type="text" inputmode="numeric" class="form-control @error('record_extra_amount') is-invalid @enderror" placeholder="Extra Amount" id="input_record-extra">
                                    
                                    @error('record_extra_amount')
                                        <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                                    @enderror
                                    <span class=" tw__text-xs" wire:ignore.self>
                                        <span>(</span>
                                        <span>
                                            <a href="javascript:void(0)" wire:click="$set('record_extra_type', 'amount')" x-on:click="calculateFinalAmount()" x-bind:class="extraType === 'amount' ? 'text-primary' : ''">Amount</a>
                                            <span>/</span>
                                            <a href="javascript:void(0)" wire:click="$set('record_extra_type', 'percentage')" x-on:click="calculateFinalAmount()" x-bind:class="extraType === 'percentage' ? 'text-primary' : ''">Percentage</a>
                                        </span>
                                        <span>)</span>
                                    </span>
                                </div>
                                <div class="form-group tw__mb-0 tw__w-full">
                                    <label>Final</label>
                                    <div wire:ignore.self>
                                        <input type="text" inputmode="numeric" class="form-control" placeholder="Final Amount" id="input_record-final" readonly>
                                    </div>
                                </div>
                            </div>
                            <small class=" tw__text-xs tw__italic text-muted" x-show="recordType === 'transfer'">**Extra amount will only applied to Expense Data (From wallet)</small>
                        </div>

                        {{-- Right Side --}}
                        <div class=" tw__p-6 tw__col-span-2 tw__bg-slate-100">
                            <div class="">
                                {{-- Record Timestamp --}}
                                <div class="form-group">
                                    <label>Date Time</label>
                                    <div wire:ignore>
                                        <input type="text" class="form-control flatpickr" name="period" id="input_record-timestamp" placeholder="Record Date Time">
                                    </div>
                                </div>

                                {{-- Record Notes --}}
                                <div class="form-group">
                                    <label>Notes</label>
                                    <textarea class="form-control tw__w-full" placeholder="Record Notess" rows="6" wire:model.lazy="record_note"></textarea>
                                </div>

                                <div x-data="{
                                    keepOpen: @entangle('record_keepopen'),
                                    keepCategory: @entangle('record_keepselectedcategory')
                                }">
                                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2">
                                        <input class="form-check-input" type="checkbox" id="input_wallet-keep_open" wire:model.lazy="record_keepopen" x-model="keepOpen" x-on:click="(keepOpen) && keepCategory ? keepCategory = false : ''">
                                        <label class="form-check-label tw__m-0" for="input_wallet-keep_open">Keep the modal open</label>
                                    </div>
                                    <div class="form-check form-switch tw__flex tw__items-center tw__gap-2" x-data="{
                                        state: @entangle('record_keepopen')
                                    }">
                                        <input class="form-check-input" type="checkbox" id="input_wallet-keep_category" wire:model.lazy="record_keepselectedcategory" x-model="keepCategory" x-bind:disabled="!(state) || !(state.value != '')">
                                        <label class="form-check-label tw__m-0" for="input_wallet-keep_category">Keep selected category</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer tw__border-slate-100">
                    <button type="button" class="btn btn-secondary tw__my-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary tw__my-0" id="btn_record-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    @include('layouts.plugins.choices-js.js')
    @include('layouts.plugins.flatpickr.js')

    <script>
        let categoryChoice = null;
        let fromWalletChoice = null;
        let toWalletChoice = null;
        // IMask
        var recordModalAmountMask = null;
        var recordModalExtraAmountMask = null;
        var recordModalFinalAmountMask = null;
        // Flatpickr
        var recordModalDateTime = null;
        const generateChoice = () => {
            // Category
            var categoryElement = document.getElementById('input_record-category');
            categoryChoice = new Choices(categoryElement, {
                allowHTML: true,
                searchPlaceholderValue: 'Search Category Data',
                shouldSort: false
            });
            categoryChoice.passedElement.element.addEventListener('showDropdown', (e) => {
                if(!(@this.get('record_uuid'))){
                    categoryChoice.clearChoices();
                }
                // Placeholder
                if (categoryChoice.passedElement.element.length <= 1) {
                    categoryChoice.setChoices([{value: '', label: 'Select Category Data', placeholder: true}], 'value', 'label', true);
                }

                @this.fetchMainCategory();
                let data = @this.categoryList_data;
                let selectedOption = @this.record_category;
                let selectOption = [];

                data.forEach((val, index) => {
                    let option = [];
                    option.push({
                        value: val.uuid,
                        label: val.name
                    });
                    if(val.child){
                        (val.child).forEach((child, childIndex) => {
                            option.push({
                                value: child.uuid,
                                label: `${val.name} - ${child.name}`
                            });
                        });
                    }

                    selectOption.push({
                        label: val.name,
                        id: index,
                        disabled: false,
                        choices: option
                    });
                });
                // console.log(selectOption);
                categoryChoice.setChoices(selectOption);
                if(selectedOption){
                    categoryChoice.setChoiceByValue(selectedOption.value);
                } else {
                    if(!(@this.get('record_uuid'))){
                        categoryChoice.setChoiceByValue('');
                    }
                }
            });

            // From Wallet
            var fromElement = document.getElementById('input_record-from_wallet');
            fromWalletChoice = new Choices(fromElement, {
                allowHTML: true,
                searchPlaceholderValue: 'Search Wallet Data',
                shouldSort: false
            });
            fromWalletChoice.passedElement.element.addEventListener('showDropdown', (e) => {
                fromWalletChoice.clearChoices();
                // Placeholder
                if (fromWalletChoice.passedElement.element.length <= 1) {
                    fromWalletChoice.setChoices([{value: '', label: 'Select Wallet Data', placeholder: true}], 'value', 'label', true);
                }

                @this.fetchMainWallet();
                let data = @this.walletList_data;
                let selectedOption = @this.record_from_wallet;
                let selectOption = [];
                console.log(data);

                data.forEach((val, index) => {
                    let option = [];
                    option.push({
                        value: val.uuid,
                        label: val.name
                    });
                    if(val.child){
                        (val.child).forEach((child, childIndex) => {
                            option.push({
                                value: child.uuid,
                                label: `${val.name} - ${child.name}`
                            });
                        });
                    }

                    selectOption.push({
                        label: val.name,
                        id: index,
                        disabled: false,
                        choices: option
                    });
                });
                // console.log(selectOption);
                fromWalletChoice.setChoices(selectOption);
                if(selectedOption){
                    fromWalletChoice.setChoiceByValue(selectedOption.value);
                } else {
                    if(!(@this.get('record_uuid'))){
                        fromWalletChoice.setChoiceByValue('');
                    }
                }
            });

            // To Wallet
            var toElement = document.getElementById('input_record-to_wallet');
            toWalletChoice = new Choices(toElement, {
                allowHTML: true,
                searchPlaceholderValue: 'Search Wallet Data',
                shouldSort: false
            });
            toWalletChoice.passedElement.element.addEventListener('showDropdown', (e) => {
                toWalletChoice.clearChoices();
                // Placeholder
                if (toWalletChoice.passedElement.element.length <= 1) {
                    toWalletChoice.setChoices([{value: '', label: 'Select Wallet Data', placeholder: true}], 'value', 'label', true);
                }

                @this.fetchMainWallet();
                let data = @this.walletList_data;
                let selectedOption = @this.record_to_wallet;
                let selectOption = [];
                console.log(data);

                data.forEach((val, index) => {
                    let option = [];
                    option.push({
                        value: val.uuid,
                        label: val.name
                    });
                    if(val.child){
                        (val.child).forEach((child, childIndex) => {
                            option.push({
                                value: child.uuid,
                                label: `${val.name} - ${child.name}`
                            });
                        });
                    }

                    selectOption.push({
                        label: val.name,
                        id: index,
                        disabled: false,
                        choices: option
                    });
                });
                // console.log(selectOption);
                toWalletChoice.setChoices(selectOption);
                if(selectedOption){
                    toWalletChoice.setChoiceByValue(selectedOption.value);
                } else {
                    if(!(@this.get('record_uuid'))){
                        toWalletChoice.setChoiceByValue('');
                    }
                }
            });
        }
        const generateImask = () => {
            // iMask
            if(document.getElementById('input_record-amount')){
                recordModalAmountMask = IMask(document.getElementById('input_record-amount'), {
                    mask: Number,
                    thousandsSeparator: ',',
                    scale: 2,  // digits after point, 0 for integers
                    signed: false,  // disallow negative
                    radix: '.',  // fractional delimiter
                    min: 0,
                });
                recordModalAmountMask.on('complete', (e) => {
                    @this.record_amount = recordModalAmountMask.unmaskedValue;
                    calculateFinalAmount();
                });
            }
            if(document.getElementById('input_record-extra')){
                recordModalExtraAmountMask = IMask(document.getElementById('input_record-extra'), {
                    mask: Number,
                    thousandsSeparator: ',',
                    scale: 2,  // digits after point, 0 for integers
                    signed: false,  // disallow negative
                    radix: '.',  // fractional delimiter
                    min: 0,
                });
                recordModalExtraAmountMask.on('complete', (e) => {
                    @this.record_extra_amount = recordModalExtraAmountMask.unmaskedValue;
                    calculateFinalAmount();
                });
            }
            if(document.getElementById('input_record-final')){
                recordModalFinalAmountMask = IMask(document.getElementById('input_record-final'), {
                    mask: Number,
                    thousandsSeparator: ',',
                    scale: 2,  // digits after point, 0 for integers
                    signed: false,  // disallow negative
                    radix: '.',  // fractional delimiter
                    min: 0,
                });
            }
        }
        const calculateFinalAmount = () => {
            setTimeout(() => {
                let amount = recordModalAmountMask.unmaskedValue;
                let extraAmount = recordModalExtraAmountMask.unmaskedValue;
                let extraType = @this.record_extra_type;

                if(isNaN(amount) || amount == ''){
                    amount = 0;
                }
                if(isNaN(extraAmount) || extraAmount == ''){
                    extraAmount = 0;
                }

                let calc = parseInt(amount) + parseInt(extraAmount);
                if(extraType === 'percentage'){
                    calc = parseInt(amount) + (parseInt(amount) * parseInt(extraAmount) / 100)
                }

                console.log(`Change final amount, current value: ${calc}`);
                recordModalFinalAmountMask.value = calc.toString();
            }, 100);
        }

        // Modal Show
        window.addEventListener('recordModal-modalShow', (e) => {
            console.log('Event by Livewire');

            // Set default date
            if(@this.get('record_uuid')) {
                console.log(@this.get('fromWalletData'));

                recordModalDateTime.setDate(moment(momentFormated('YYYY-MM-DD HH:mm:ss', @this.get('timezone'), @this.get('record_timestamp'))).format('YYYY-MM-DD HH:mm'));
                
                if(@this.get('categoryData')){
                    categoryChoice.setValue([
                        { value: @this.get('categoryData').uuid, label: `${@this.get('categoryData').parent ? `${@this.get('categoryData').parent.name} - ` : ''}${@this.get('categoryData').name}` },
                    ]);
                }

                if(@this.get('fromWalletData')){
                    fromWalletChoice.setValue([
                        { value: @this.get('fromWalletData').uuid, label: `${@this.get('fromWalletData').parent ? `${@this.get('fromWalletData').parent.name} - ` : ''}${@this.get('fromWalletData').name}` },
                    ]);
                }

                if(@this.get('toWalletData')){
                    toWalletChoice.setValue([
                        { value: @this.get('toWalletData').uuid, label: `${@this.get('toWalletData').parent ? `${@this.get('toWalletData').parent.name} - ` : ''}${@this.get('toWalletData').name}` },
                    ]);
                }

                recordModalAmountMask.value = (@this.get('record_amount')).toString()
                recordModalExtraAmountMask.value = (@this.get('record_extra_amount')).toString()
            } else {
                recordModalDateTime.setDate(moment().format('YYYY-MM-DD HH:mm'));
            }

            let el = document.getElementById('record-modal');
            var myModal = new bootstrap.Modal(el);
            myModal.show();

            el.addEventListener('hidden.bs.modal', (e) => {
                @this.closeModal();

                categoryChoice.setChoiceByValue('');
                fromWalletChoice.setChoiceByValue('');
                toWalletChoice.setChoiceByValue('');
                recordModalAmountMask.value = '';
                recordModalExtraAmountMask.value = '';
            });
            el.addEventListener('shown.bs.modal', (e) => {
            });
        });
        // Modal Hide
        window.addEventListener('recordModal-modalHide', (e) => {
            console.log('Event by Livewire / Modal Hide');

            let el = document.getElementById('record-modal');
            if(el.classList.contains('show')){
                console.log('Please hide this modal');

                var myModal = bootstrap.Modal.getInstance(el);
                myModal.hide();
            }
        });
        // Clear Field
        window.addEventListener('recordModal-clearField', (e) => {
            categoryChoice.setChoiceByValue(@this.record_category !== null ? @this.record_category : '');
            fromWalletChoice.setChoiceByValue('');
            toWalletChoice.setChoiceByValue('');
            recordModalDateTime.setDate(moment().format('YYYY-MM-DD HH:mm'));

            recordModalAmountMask.value = '';
            recordModalExtraAmountMask.value = '';
            recordModalFinalAmountMask.value = '';

        });
        // Switch selected Wallet
        window.addEventListener('recordModal-switchWallet', (e) => {
            let fromWallet = @this.get('fromWalletData');
            let toWallet = @this.get('toWalletData');

            if(fromWallet){
                toWalletChoice.setValue([
                    { value: fromWallet.uuid, label: `${fromWallet.parent ? `${fromWallet.parent.name} - ` : ''}${fromWallet.name}` },
                ]);
                @this.set('record_to_wallet', fromWallet.uuid);
            } else {
                toWalletChoice.setChoiceByValue('');
                @this.set('record_to_wallet', null);
            }

            if(toWallet){
                fromWalletChoice.setValue([
                    { value: toWallet.uuid, label: `${toWallet.parent ? `${toWallet.parent.name} - ` : ''}${toWallet.name}` },
                ]);
                @this.set('record_from_wallet', toWallet.uuid);
            } else {
                fromWalletChoice.setChoiceByValue('');
                @this.set('record_from_wallet', null);
            }

            @this.set('fromWalletData', null);
            @this.set('toWalletData', null);
        });

        document.addEventListener('DOMContentLoaded', () => {
            generateChoice();
            generateImask();

            // Flatpickr
            recordModalDateTime = flatpickr(document.getElementById('input_record-timestamp'), {
                enableTime: true,
                altInput: true,
                altFormat: "F j, Y / H:i",
                dateFormat: "Y-m-d H:i",
                time_24hr: true,
                minuteIncrement: 1,
                allowInput: true,
            });

            // Form
            document.getElementById('record-modal_form').addEventListener('submit', (e) => {
                e.preventDefault();
                console.log('Form record modal is being submited');

                if(e.target.querySelector('button[type="submit"]')){
                    e.target.querySelector('button[type="submit"]').innerHTML = `
                        <span class=" tw__flex tw__items-center tw__gap-2">
                            <i class="fa-solid fa-spinner fa-spin"></i>
                            <span>Loading</span>    
                        </span>
                    `;
                    e.target.querySelector('button[type="submit"]').disabled = true;
                }

                @this.record_timestamp = document.getElementById('input_record-timestamp').value;
                if(moment.tz.guess()){
                    @this.timezone = moment.tz.guess();
                }
                @this.save();
            });
        });
    </script>
@endpush