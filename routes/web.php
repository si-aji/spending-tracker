<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

$register = false;

// Auth
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
    'middleware' => ['web']
], function() use ($register){
    Route::group([
        'middleware' => ['guest']
    ], function() use ($register){
        Route::get('login', \App\Http\Livewire\Auth\Login::class)->name('login');

        if($register){
            Route::get('register', \App\Http\Livewire\Auth\Register::class)->name('register');
        }
    });
    
    Route::group([
        'middleware' => ['auth']
    ], function(){
        Route::get('logout', \App\Http\Livewire\Auth\Logout::class)->name('logout');
    });
});

Route::get('/', \App\Http\Livewire\Public\Homepage\Index::class)->name('index');