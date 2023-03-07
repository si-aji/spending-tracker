<?php

namespace App\Http\Livewire\Sys\Record;

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
    public $recordData = null;

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
    public function mount($uuid)
    {
        $this->menuState = 'record';
        $this->submenuState = null;

        $this->recordData = \App\Models\Record::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->firstOrFail();
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.sys.record.show')
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
            ]);
    }

    /**
     * Custom Function
     */
}
