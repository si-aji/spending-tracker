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

// Auth
Route::group([
    'prefix' => 'auth',
    'as' => 'auth.',
    'middleware' => ['web']
], function(){
    Route::group([
        'middleware' => ['guest']
    ], function(){
        Route::get('login', \App\Http\Livewire\Auth\Login::class)->name('login');
        Route::get('register', \App\Http\Livewire\Auth\Register::class)->name('register');
    });
    
    Route::group([
        'middleware' => ['auth']
    ], function(){
        Route::get('logout', \App\Http\Livewire\Auth\Logout::class)->name('logout');
    });
});

Route::get('/', function(){
    return response()->json('ok');
});