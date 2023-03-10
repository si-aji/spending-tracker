<?php

namespace App\Http\Livewire\Public\Homepage;

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
        // 
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.public.homepage.index')
            ->extends('layouts.app');
    }

    /**
     * Custom Function
     */
}
