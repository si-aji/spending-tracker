<?php

namespace App\Http\Livewire\Sys\Dashboard;

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
    // 

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    // protected $listeners = [];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->menuState = 'dashboard';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.sys.dashboard.index')
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
                'submenuState' => $this->submenuState
            ]);
    }

    /**
     * Custom Function
     */
}
