<?php

namespace App\Http\Livewire\Sys\WalletGroup;

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
        $this->menuState = 'wallet-group';
        $this->submenuState = null;

        $this->data = \App\Models\WalletGroup::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->firstOrFail();
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.sys.wallet-group.show')
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
                'submenuState' => $this->submenuState
            ]);
    }

    /**
     * Custom Function
     */
}
