<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;

use Livewire\Component;

class Login extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $email = '';
    public $password = '';
    public $remember = false;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'logout'
    ];

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
        return view('livewire.auth.login')
            ->extends('layouts.auth');
    }

    /**
     * Custom Function
     */
    public function authenticate()
    {
        $this->validate([
            'email' => ['required'],
            'password' => ['required'],
        ]);

        $userKey = $this->usernameKeyValidate() ?? 'email';
        if (!Auth::attempt([$userKey => $this->email, 'password' => $this->password], $this->remember)) {
            $this->addError('email', trans('auth.failed'));

            return;
        }

        return redirect()->intended(route('sys.index'));
    }

    public function usernameKeyValidate()
    {
        $key = filter_var($this->email, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        return $key;
    }
}
