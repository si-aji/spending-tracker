<?php

namespace App\Http\Livewire\Component\Wallet;

use Livewire\Component;

class WalletModal extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $wallet_modalTitle = 'Wallet: Create new';
    public $wallet_keepopen = false;
    public $walletReset_list = [];
    // List
    public $walletList_data = [];
    // Data
    public $wallet_uuid = null;
    public $wallet_parent = null;
    public $wallet_name = null;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'showModal' => 'showModal',
        'edit' => 'edit',
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->walletReset_list = [
            'wallet_modalTitle',

            'wallet_uuid',
            'wallet_parent',
            'wallet_name'
        ];
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.component.wallet.wallet-modal');
    }

    /**
     * Custom Function
     */
    public function fetchMailWallet()
    {
        $this->walletList_data = \App\Models\Wallet::where()
            ->get();
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function save()
    {
        \Log::debug("Debug on Wallet Modal Save function", [
            'name' => $this->wallet_name,
            'modal_state' => $this->wallet_keepopen
        ]);

        $this->validate([
            'wallet_name' => ['required', 'string', 'max:191']
        ]);

        // Save to database
        $data = new \App\Models\Wallet();
        if(!empty($this->wallet_uuid)){
            $data = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->wallet_uuid)
                ->first();
        }

        $data->user_id = \Auth::user()->id;
        $data->name = $this->wallet_name;
        $data->save();

        if(!$this->wallet_keepopen){
            $this->closeModal();
        }

        $this->reset($this->walletReset_list);
    }
    public function edit($uuid)
    {
        $data = \App\Models\Wallet::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->first();

        if(!empty($data)){
            $this->wallet_modalTitle = 'Wallet: Edit Data';

            $this->wallet_uuid = $data->uuid;
            $this->wallet_name = $data->name;

            $this->showModal();
        }
    }

    /**
     * Browser Function
     */
    public function showModal()
    {
        $this->dispatchBrowserEvent('walletModal-modalShow');
    }
    public function closeModal()
    {
        $this->hydrate();
        $this->dispatchBrowserEvent('walletModal-modalHide');
        $this->reset($this->walletReset_list);
        $this->reset(['wallet_keepopen']);
    }
}
