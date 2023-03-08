<?php

namespace App\Http\Livewire\Component\WalletGroup;

use Livewire\Component;

class WalletGroupModal extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $walletGroup_modalTitle = 'Wallet Group: Create new';
    public $walletGroup_keepopen = false;
    public $walletGroupReset_list = [];
    // List
    public $walletList_data = [];
    public $walletGroupItem_data = [];
    // Data
    public $walletGroup_uuid = null;
    public $walletGroup_name = null;
    public $walletGroup_item = [];

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'showModal' => 'showModal',
        'closeModal' => 'closeModal',
        'showWalletOption' => 'showWalletOption',

        'save' => 'save',
        'edit' => 'edit',
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->walletGroupReset_list = [
            'walletGroup_modalTitle',
            'walletGroupItem_data',

            'walletGroup_name',
            'walletGroup_item'
        ];
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        return view('livewire.component.wallet-group.wallet-group-modal');
    }

    /**
     * Custom Function
     */
    public function fetchMainWallet()
    {
        $data = \App\Models\Wallet::with('parent', 'child')
            ->where('user_id', \Auth::user()->id)
            ->whereNull('parent_id')
            ->orderBy('order_main', 'asc')
            ->get();
        $this->walletList_data = collect($data);
    }

    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function save()
    {
        $this->validate([
            'walletGroup_name' => ['required', 'string', 'max:191'],
            'walletGroup_item.*' => ['required', 'exists:'.(new \App\Models\Wallet())->getTable().',uuid']
        ]);

        \DB::transaction(function () {
            $data = new \App\Models\WalletGroup();
            if(!empty($this->walletGroup_uuid)){
                $data = \App\Models\WalletGroup::where('user_id', \Auth::user()->id)
                    ->where(\DB::raw("BINARY `uuid`"), $this->walletGroup_uuid)
                    ->firstOrFail();
            }

            $data->user_id = \Auth::user()->id;
            $data->name = $this->walletGroup_name;
            $data->save();

            $selectedWallet = [];
            if(!empty($this->walletGroup_item)){
                $selectedWallet = \App\Models\Wallet::whereIn(\DB::raw('BINARY `uuid`'), $this->walletGroup_item)
                    ->pluck('id')
                    ->toArray();
            }
            // Sync between Wallet Group and Wallet Group Item
            $data->walletGroupItem()->syncWithPivotValues($selectedWallet, ['created_at' => \Carbon\Carbon::now(), 'updated_at' => \Carbon\Carbon::now()]);
        });

        if(!$this->walletGroup_keepopen){
            $this->closeModal();
        }
        $this->reset($this->walletGroupReset_list);
        $this->dispatchBrowserEvent('walletGroupModal-clearField');
    }

    public function edit($uuid)
    {
        $data = \App\Models\WalletGroup::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->firstOrFail();

        $this->walletGroup_modalTitle = 'Wallet Group: Edit Data';

        $this->walletGroup_uuid = $data->uuid;
        $this->walletGroup_name = $data->name;
        $this->walletGroup_item = $data->walletGroupItem()->pluck((new \App\Models\Wallet())->getTable().'.uuid');
        $this->walletGroupItem_data = collect($data->walletGroupItem->load('parent'));

        $this->showModal();
    }

    /**
     * Browser Function
     */
    public function showModal()
    {
        $this->dispatchBrowserEvent('walletGroupModal-modalShow');
    }
    public function closeModal()
    {
        $this->hydrate();
        $this->dispatchBrowserEvent('walletGroupModal-modalHide');
        $this->dispatchBrowserEvent('walletGroupModal-clearField');
        $this->reset($this->walletGroupReset_list);
        $this->reset(['walletGroup_keepopen']);
    }
    public function showWalletOption()
    {
        $this->fetchMainWallet();
        $this->dispatchBrowserEvent('walletChoice-showOption');
    }
}
