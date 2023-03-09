<div>
    {{-- If your happiness depends on money, you will never be happy with yourself. --}}
    <div class="modal fade" id="wallet_adjustment-modal" tabindex="-1" aria-labelledby="wallet_adjustment-modalTitle" aria-hidden="true" wire:ignore.self>
        <div class="modal-dialog">
            <form class="modal-content tw__rounded-lg" wire:submit.prevent="save">
                <div class="modal-header">
                    <h5 class="modal-title" id="wallet-modalTitle">{{ $walletAdjustment_modalTitle }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" x-data="{
                    type: @entangle('wallet_type')
                }">
                    <div class="form-group">
                        <label>What to adjust?</label>
                        <div class="btn-group tw__mb-4 md:tw__mb-0 tw__flex" role="group" aria-label="Basic radio toggle button group">
                            <input type="radio" class="btn-check" name="wallet_adjustment" id="wallet_adjustment-current" value="balance" autocomplete="off" wire:model.lazy="wallet_type" x-on:click="$wire.call('fetchAmount')">
                            <label class="btn btn-white px-3 mb-0" for="wallet_adjustment-current">Current Balance</label>
                            <input type="radio" class="btn-check" name="wallet_adjustment" id="wallet_adjustment-starting" value="starting_balance" autocomplete="off" wire:model.lazy="wallet_type" x-on:click="$wire.call('fetchAmount')"">
                            <label class="btn btn-white px-3 mb-0" for="wallet_adjustment-starting">Starting Balance</label>
                        </div>
                    </div>

                    <div class="alert tw__mb-2" :class="type === 'balance' ? 'alert-primary' : 'alert-info'" role="alert">
                        <span x-show="type === 'balance'">The application will create a new record (either Income / Expense) to match the current balance</span>
                        <span x-show="type === 'starting_balance'"><strong>Please be careful!</strong>, the application will change starting balance of related wallet, this will affect your balance calculation</span>
                    </div>

                    <div class="form-group @error('wallet_amount') tw__border-red-400 @enderror tw__mb-0" wire:ignore>
                        <label>Amount</label>
                        <input type="text" inputmode="numeric" class="form-control" placeholder="Amount" id="wallet_adjustment-amount" style="border-color: inherit" >
                    
                        @error('wallet_amount')
                            <small class="invalid-feedback tw__block tw__italic tw__text-xs">{{ $message }}</small>
                        @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary tw__my-0" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary tw__my-0" id="btn_wallet_adjustment-submit">Submit</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('javascript')
    <script>
        // IMask
        var walletAdjustmentModalAmountMask = null;
        
        // Modal Show
        window.addEventListener('walletAdjustmentModal-modalShow', (e) => {
            console.log('Event by Livewire');

            window.dispatchEvent(new Event('walletAdjustmentModal-getBalance'));

            let el = document.getElementById('wallet_adjustment-modal');
            var myModal = new bootstrap.Modal(el);
            myModal.show();

            el.addEventListener('hidden.bs.modal', (e) => {
                @this.closeModal()
            });
        });
        // Modal Hide
        window.addEventListener('walletAdjustmentModal-modalHide', (e) => {
            console.log('Event by Livewire / Modal Hide');

            let el = document.getElementById('wallet_adjustment-modal');
            if(el.classList.contains('show')){
                console.log('Please hide this modal');

                var myModal = bootstrap.Modal.getInstance(el);
                myModal.hide();
            }
        });

        window.addEventListener('walletAdjustmentModal-getBalance', (e) => {
            console.log('Get Balance');

            let data = @this.get('wallet_amount');
            console.log(data);
            walletAdjustmentModalAmountMask.value = data.toString();
        });

        document.addEventListener('DOMContentLoaded', () => {
            // iMask
            if(document.getElementById('wallet_adjustment-amount')){
                walletAdjustmentModalAmountMask = IMask(document.getElementById('wallet_adjustment-amount'), {
                    mask: Number,
                    thousandsSeparator: ',',
                    scale: 2,  // digits after point, 0 for integers
                    signed: false,  // disallow negative
                    radix: '.',  // fractional delimiter
                    min: 0,
                });
                walletAdjustmentModalAmountMask.on('complete', (e) => {
                    @this.wallet_amount = walletAdjustmentModalAmountMask.unmaskedValue;
                });
            }
        });
    </script>
@endpush