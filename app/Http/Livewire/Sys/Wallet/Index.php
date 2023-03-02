<?php

namespace App\Http\Livewire\Sys\Wallet;

use Livewire\Component;

class Index extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $wallet_filter_state = 'active';
    public $wallet_filter_search = '';
    // Data
    public $dataWallet = [];
    // Paginate Conf
    public $loadPerPage = 10;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->menuState = 'wallet';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->dataWallet = \App\Models\Wallet::with('parent')
            ->where('user_id', \Auth::user()->id)
            ->orderBy('order_main', 'asc');

        if($this->wallet_filter_search != ''){
            $this->dataWallet->where('name', 'like', '%'.$this->wallet_filter_search.'%');
        }

        $this->dataWallet = $this->dataWallet->paginate($this->loadPerPage);
        $paginate = $this->dataWallet;
        $this->dataWallet = collect($this->dataWallet->items())->values()->map(function($data){
            // $data->balance = $data->getBalance();
            // $data->last_transaction = $data->getLastTransaction();
            return $data;
        });

        $this->dispatchBrowserEvent('walletListLoadData');
        return view('livewire.sys.wallet.index', [
                'paginate' => $paginate
            ])
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
            ]);
    }

    /**
     * Custom Function
     */
}
