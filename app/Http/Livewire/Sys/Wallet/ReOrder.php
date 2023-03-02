<?php

namespace App\Http\Livewire\Sys\Wallet;

use Livewire\Component;

class ReOrder extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $walletList_data;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'refreshComponent' => '$refresh',
        'reOrder' => 'reOrder'
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->menuState = 'wallet';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->fetchMainWallet();
        $this->dispatchBrowserEvent('walletListInit-re_order');

        return view('livewire.sys.wallet.re-order')
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
            ]);
    }

    /**
     * Custom Function
     */
    public function fetchMainWallet()
    {
        // Wallet
        $this->walletList_data = \App\Models\Wallet::with('child', 'parent')
            ->where('user_id', \Auth::user()->id)
            ->whereNull('parent_id')
            ->orderBy('order_main', 'asc')
            ->get();
    }
    public function reOrder($order = null)
    {
        \Log::debug("Debug on Re Order function ~ \App\Http\Livewire\Sys\Wallet\ReOrder", [
            'order' => $order
        ]);

        if($order === null){
            // Create Wallet Re-Order Request
            $allParentWallet = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->whereNull('parent_id')
                // ->orderBy('order_main', 'asc')
                ->orderByRaw('ISNULL(order_main), order_main ASC')
                ->get();
            $formatedRequest = [];
            if (count($allParentWallet) > 0) {
                foreach ($allParentWallet as $wallet) {
                    $arr = [
                        'id' => $wallet->uuid,
                    ];

                    if ($wallet->child()->exists()) {
                        $childArr = [];
                        foreach ($wallet->child()->orderBy('order', 'asc')->get() as $child) {
                            $childArr[] = [
                                'id' => $child->uuid,
                            ];
                        }

                        $arr = [
                            'id' => $wallet->uuid,
                            'child' => $childArr,
                        ];
                    }

                    $formatedRequest[] = $arr;
                }
            }

            $order = $formatedRequest;
        }

        $numorder = 0;
        $numorderMain = 0;
        foreach ($order as $hierarchy) {
            // Update Main Order
            $wallet = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $hierarchy['id'])
                ->firstOrFail();

            // $wallet->order = $numorder;
            $wallet->order_main = $numorderMain;
            if (!empty($wallet->parent_id)) {
                $wallet->parent_id = null;
            }
            $wallet->save();

            // Request has Child Wallet
            if (isset($hierarchy['child']) && is_array($hierarchy['child']) && count($hierarchy['child']) > 0) {
                $childOrder = 1;
                foreach ($hierarchy['child'] as $child) {
                    $numorderMain++;

                    // Update Child Order
                    $subwallet = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                        ->where(\DB::raw('BINARY `uuid`'), $child['id'])
                        ->firstOrFail();
                    $subwallet->order = $childOrder;
                    $subwallet->order_main = $numorderMain;
                    $subwallet->parent_id = $wallet->id;
                    $subwallet->save();

                    $childOrder++;
                }
            }

            $numorderMain++;
            $numorder++;
        }
    }
}
