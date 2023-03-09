<?php

namespace App\Http\Livewire\Sys\Wallet;

use Livewire\Component;

class Show extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $data;
    // Load More Conf
    public $loadPerPage = 10,
        $timezone = null;
    // List Data
    public $dataRecord = null;
    protected $recordPaginate;

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
    public function mount($uuid)
    {
        $this->menuState = 'wallet';
        $this->submenuState = null;

        $this->data = \App\Models\Wallet::with('parent')
            ->where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->firstOrFail();
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->dispatchBrowserEvent('walletShow-load');

        return view('livewire.sys.wallet.show', [
                'paginate' => $this->recordPaginate
            ])
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
                'submenuState' => $this->submenuState
            ]);
    }

    /**
     * Custom Function
     */
    public function fetchRecordData($selectedWallet = null) : void
    {
        $this->dataRecord = \App\Models\Record::with('fromWallet.parent', 'toWallet.parent', 'category.parent')
            ->where('user_id', \Auth::user()->id)
            ->where(function($q){
                return $q->where('from_wallet_id', $this->data->id)
                    ->orWhere('to_wallet_id', $this->data->id);
            });

        $this->dataRecord = $this->dataRecord->orderBy('datetime', 'desc')
            ->get();
        $this->dataRecord = collect($this->dataRecord);
        $this->recordPaginate = $this->dataRecord->paginate($this->loadPerPage);
        $this->dataRecord = $this->dataRecord->values()->take($this->loadPerPage);

        $this->dispatchBrowserEvent('fetchData');
    }
}
