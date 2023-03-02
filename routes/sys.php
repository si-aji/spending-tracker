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

Route::group([
    'middleware' => ['auth'],
    'as' => 'sys.'
], function(){
    // Dashboard
    Route::get('/', \App\Http\Livewire\Sys\Dashboard\Index::class)->name('index');

    // Wallet
    Route::group([
        'prefix' => 'wallet',
        'as' => 'wallet.'
    ], function(){
        // Re Order
        Route::get('re-order', \App\Http\Livewire\Sys\Wallet\ReOrder::class)->name('re-order.index');
        // List
        Route::get('/', \App\Http\Livewire\Sys\Wallet\Index::class)->name('index');
    });
});