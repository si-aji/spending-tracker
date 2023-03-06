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
    public $wallet_keepparent = false;
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
        $this->fetchMainWallet();
        return view('livewire.component.wallet.wallet-modal');
    }

    /**
     * Custom Function
     */
    public function fetchMainWallet()
    {
        $data = \App\Models\Wallet::where('user_id', \Auth::user()->id)
            ->whereNull('parent_id')
            ->orderBy('order_main', 'asc')
            ->get();
        $this->walletList_data = collect($data);
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
            'modal_state' => $this->wallet_keepopen,
            'parent' => $this->wallet_parent
        ]);

        $this->validate([
            'wallet_name' => ['required', 'string', 'max:191']
        ]);

        $parent = null;
        // Save to database
        $data = new \App\Models\Wallet();
        if(!empty($this->wallet_uuid)){
            $data = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->wallet_uuid)
                ->first();
        } else {
            if(!empty($this->wallet_parent)){
                $parent = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                    ->where(\DB::raw('BINARY `uuid`'), $this->wallet_parent)
                    ->first();
            }
            $data->parent_id = !empty($parent) ? $parent->id : null;
        }
        

        $data->user_id = \Auth::user()->id;
        $data->name = $this->wallet_name;
        $data->save();

        if(!$this->wallet_keepopen){
            $this->closeModal();
        }

        $this->reset($this->walletReset_list);
        if(!($this->wallet_keepparent)){
            $this->dispatchBrowserEvent('walletModal-clearField');
        } else {
            $this->wallet_parent = $data->parent->uuid;
        }

        // Re-order Main Order
        if(empty($data->parent_id)){
            $lastOrder = -1;
            $checkLastOrder = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->orderBy('order_main', 'desc')
                ->first();
            if(!empty($checkLastOrder)){
                $lastOrder = $checkLastOrder->order_main;
            }
            $data->order_main = $lastOrder + 1;
            $data->save();
        } else {
            $lastOrder = -1;
            $lastOrderMain = -1;

            $checkLastOrder = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where('parent_id', $data->parent_id)
                ->where('id', '!=', $data->id)
                ->orderBy('order_main', 'desc')
                ->first();
            if(!empty($checkLastOrder)){
                $lastOrder = $checkLastOrder->order;
                $lastOrderMain = $checkLastOrder->order_main;
            } else {
                $lastOrder = $data->parent->order;
                $lastOrderMain = $data->parent->order_main;
            }

            // Modify Order Main
            $others = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where('order_main', '>', $lastOrderMain)
                ->orderBy('order_main', 'desc')
                ->get();
            foreach($others as $other){
                $other->order_main += 1;
                $other->save();
            }

            $data->order = $lastOrder + 1;
            $data->order_main = $lastOrderMain + 1;
            $data->save();
        }
        
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
        $this->dispatchBrowserEvent('walletModal-clearField');
        $this->reset($this->walletReset_list);
        $this->reset(['wallet_keepopen', 'wallet_keepparent']);
    }
}
