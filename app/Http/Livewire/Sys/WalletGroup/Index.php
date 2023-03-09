<?php

namespace App\Http\Livewire\Sys\WalletGroup;

use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    // Data
    public $dataWalletGroup = [];
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
        $this->menuState = 'wallet-group';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->dataWalletGroup = \App\Models\WalletGroup::with('walletGroupItem.parent')->where('user_id', \Auth::user()->id)
            ->orderBy('name', 'asc');

        $this->dataWalletGroup = $this->dataWalletGroup->paginate($this->loadPerPage);
        $paginate = $this->dataWalletGroup;
        $this->dataWalletGroup = collect($this->dataWalletGroup->items())->values()->map(function($data){
            $data->balance = $data->getBalance();
            
            return $data;
        });

        $this->dispatchBrowserEvent('walletGroupLoadData');
        return view('livewire.sys.wallet-group.index', [
                'paginate' => $paginate
            ])
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
                'submenuState' => $this->submenuState
            ]);
    }

    /**
     * Custom Function
     */
}
