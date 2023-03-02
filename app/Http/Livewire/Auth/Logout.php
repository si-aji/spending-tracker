<?php

namespace App\Http\Livewire\Auth;

use Livewire\Component;

class Logout extends Component
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
        return view('livewire.auth.logout');
    }

    /**
     * Custom Function
     */
    public function actionLogout()
    {
        \Auth::logout();

        return redirect()->route('auth.login');
    }
}
