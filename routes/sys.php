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

    // Record
    Route::group([
        'prefix' => 'record',
        'as' => 'record.'
    ], function(){
        // List
        Route::get('/{uuid}', \App\Http\Livewire\Sys\Record\Show::class)->name('show');
        Route::get('/', \App\Http\Livewire\Sys\Record\Index::class)->name('index');
    });

    // Wallet
    Route::group([
        'prefix' => 'wallet',
        'as' => 'wallet.'
    ], function(){
        // Re Order
        Route::get('re-order', \App\Http\Livewire\Sys\Wallet\ReOrder::class)->name('re-order.index');
        // Group
        Route::get('group/{uuid}', \App\Http\Livewire\Sys\WalletGroup\Show::class)->name('group.show');
        Route::get('group', \App\Http\Livewire\Sys\WalletGroup\Index::class)->name('group.index');
        
        // List
        Route::get('{uuid}', \App\Http\Livewire\Sys\Wallet\Show::class)->name('show');
        Route::get('/', \App\Http\Livewire\Sys\Wallet\Index::class)->name('index');
    });

    // Category
    Route::group([
        'prefix' => 'category',
        'as' => 'category.'
    ], function(){
        // Re Order
        Route::get('re-order', \App\Http\Livewire\Sys\Category\ReOrder::class)->name('re-order.index');
        // List
        Route::get('/', \App\Http\Livewire\Sys\Category\Index::class)->name('index');
    });
});