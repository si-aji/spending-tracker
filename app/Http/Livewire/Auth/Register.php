<?php

namespace App\Http\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use Livewire\Component;

class Register extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $toc = false;

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
        return view('livewire.auth.register')
            ->extends('layouts.auth');
    }

    /**
     * Custom Function
     */
    public function register()
    {
        $this->validate([
            'name' => ['required'],
            'email' => ['required', 'email', 'unique:users'],
            'password' => ['required', 'min:5'],
            'toc' => ['required', 'accepted']
        ], [
            'toc.required' => 'You must agree with our Terms and Conditions',
            'toc.accepted' => 'You must agree with our Terms and Conditions',
        ]);

        // Create User
        $user = \App\Models\User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => Hash::make($this->password),
        ]);

        // Generate default category
        event(new \App\Events\UserRegistered($user));
        // Send registration email

        // Login User
        Auth::login($user, true);
        return redirect()->intended(route('sys.index'));
    }
}
