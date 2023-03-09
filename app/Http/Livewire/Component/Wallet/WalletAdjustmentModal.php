<?php

namespace App\Http\Livewire\Component\Wallet;

use Livewire\Component;

class WalletAdjustmentModal extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $walletAdjustment_modalTitle = 'Wallet: Adjustment';
    // Data
    public $walletReset_list = [];
    public $wallet_data = null;
    public $wallet_type = 'balance';
    public $wallet_amount = 0;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'showModal' => 'showModal',
        'hideModal' => 'hideModal',
        'fetchAmount' => 'fetchAmount',

        'edit' => 'edit',
        'save' => 'save'
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->walletReset_list = [
            'walletAdjustment_modalTitle',
            'wallet_data',
            'wallet_amount',
            'wallet_type'
        ];
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.component.wallet.wallet-adjustment-modal');
    }

    /**
     * Custom Function
     */
    public function fetchAmount()
    {
        if($this->wallet_type === 'balance'){
            // Current Balance
            $this->wallet_amount = $this->wallet_data->getBalance();
        } else {
            // Starting Balance
            $this->wallet_amount = $this->wallet_data->starting_balance;
        }

        $this->dispatchBrowserEvent('walletAdjustmentModal-getBalance');
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function edit($uuid)
    {
        $this->wallet_data = \App\Models\Wallet::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->firstOrFail();
        
        $this->fetchAmount();
        $this->showModal();
    }
    public function save()
    {
        \Log::debug("Debug on Save Wallet Adjustment", [
            'data' => $this->wallet_data,
            'type' => $this->wallet_type,
            'amount' => $this->wallet_amount
        ]);

        if($this->wallet_type === 'starting_balance'){

        } else {
            $balance = $this->wallet_data->getBalance();
            $type = 'expense';

            $calc = $balance - $this->wallet_amount;
            if($this->wallet_amount > $balance){
                $type = 'income';
                $calc = $this->wallet_amount - $balance;
            }

            $datetime = date('Y-m-d H:i:s');

            $record = new \App\Models\Record([
                'user_id' => \Auth::user()->id,
                'category_id' => null,
                'type' => $type,
                'from_wallet_id' => $this->wallet_data->id,
                'to_wallet_id' => null,
                'amount' => $calc,
                'extra_type' => 'amount',
                'extra_percentage' => 0,
                'extra_amount' => 0,
                'date' => date('Y-m-d', strtotime($datetime)),
                'time' => date('H:i:s', strtotime($datetime)),
                'datetime' => $datetime,
                'note' => 'Wallet balance adjustment',
                'timezone' => null
            ]);
            $record->save();
        }

        $this->closeModal();
    }

    /**
     * Browser Function
     */
    public function showModal()
    {
        $this->dispatchBrowserEvent('walletAdjustmentModal-modalShow');
    }
    public function closeModal()
    {
        $this->hydrate();
        $this->dispatchBrowserEvent('walletAdjustmentModal-modalHide');
        $this->reset($this->walletReset_list);
    }
}
