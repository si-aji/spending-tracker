<?php

namespace App\Http\Livewire\Component\Record;

use Livewire\Component;

class RecordModal extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $record_modalTitle = 'Record: Create new';
    public $record_keepopen = false;
    public $record_keepselectedcategory = false;
    public $recordReset_list = [];
    // Data
    public $timezone = null;
    public $record_uuid = null;
    public $record_type = 'expense';
    public $record_extra_type = 'amount';
    public $record_category = null;
    public $record_to_wallet = null;
    public $record_from_wallet = null;
    public $record_amount = null;
    public $record_extra_amount = null;
    public $record_final_amount = null;
    public $record_timestamp = null;
    public $record_note = null;
    // List
    public $walletList_data = [];
    public $categoryList_data = [];

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'showModal' => 'showModal',
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->recordReset_list = [
            'record_modalTitle',
            'timezone',
            'record_uuid',
            'record_type',
            'record_extra_type',
            'record_amount',
            'record_extra_amount',
            'record_category',
            'record_from_wallet',
            'record_to_wallet',
        ];
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->fetchMainWallet();
        $this->fetchMainCategory();
        return view('livewire.component.record.record-modal');
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
    public function fetchMainCategory()
    {
        $data = \App\Models\Category::with('parent', 'child')
            ->where('user_id', \Auth::user()->id)
            ->whereNull('parent_id')
            ->orderBy('order_main', 'asc')
            ->get();
        $this->categoryList_data = collect($data);
    }
    public function hydrate()
    {
        $this->resetErrorBag();
        $this->resetValidation();
    }
    public function save()
    {
        if(!empty($this->record_category) && is_array($this->record_category) && isset($this->record_category['value'])){
            $this->record_category = $this->record_category['value'];
        }
        if(!empty($this->record_from_wallet) && is_array($this->record_from_wallet) && isset($this->record_from_wallet['value'])){
            $this->record_from_wallet = $this->record_from_wallet['value'];
        }
        if(!empty($this->record_to_wallet) && is_array($this->record_to_wallet) && isset($this->record_to_wallet['value'])){
            $this->record_to_wallet = $this->record_to_wallet['value'];
        }

        $this->validate([
            'record_type' => ['required', 'string', 'in:income,expense,transfer'],
            'record_category' => ['required', 'string', 'exists:'.(new \App\Models\Category())->getTable().',uuid'],
            'record_from_wallet' => ['required', 'string', 'exists:'.(new \App\Models\Wallet())->getTable().',uuid'],
            'record_to_wallet' => [($this->record_type === 'transfer' ? 'required' : 'nullable'), 'string', 'exists:'.(new \App\Models\Wallet())->getTable().',uuid'],
            'record_amount' => ['required', 'numeric'],
            'record_extra_amount' => ['nullable', 'numeric'],
            'record_extra_type' => ['required', 'string', 'in:amount,percentage'],
            'record_timestamp' => ['required', 'string'],
            'record_note' => ['nullable', 'string'],
        ]);

        // Initialize record model
        $data = new \App\Models\Record();
        // Initialize category
        $category = null;
        if($this->record_category){
            $category = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->record_category)
                ->firstOrFail();
        }
        // Initialize from wallet
        $fromWallet = null;
        if($this->record_from_wallet){
            $fromWallet = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->record_from_wallet)
                ->firstOrFail();
        }
        // Initialize to wallet
        $toWallet = null;
        if($this->record_type === 'transfer' && $this->record_to_wallet){
            $toWallet = \App\Models\Wallet::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->record_to_wallet)
                ->firstOrFail();
        }
        
        // Convert datetime
        $datetime = date("Y-m-d H:i:00", strtotime($this->record_timestamp));
        if(!empty($this->timezone)){
            $timezone = $this->timezone;
            $formated = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $timezone);
            $datetime = $formated->setTimezone('UTC')->format('Y-m-d H:i:s');
        }

        // Check Record Type
        if(in_array($this->record_type, ['income', 'expense'])){
            $data->user_id = \Auth::user()->id;
            $data->category_id = !empty($category) ? $category->id : null;
            $data->type = $this->record_type;
            $data->from_wallet_id = !empty($fromWallet) ? $fromWallet->id : null;
            $data->to_wallet_id = !empty($toWallet) ? $toWallet->id : null;
            $data->amount = $this->record_amount;
            $data->extra_type = $this->record_extra_type;
            $data->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
            $data->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount ? (($this->record_extra_amount * $this->record_amount) / 100) : 0) : ($this->record_extra_amount ?? 0);
            $data->date = date('Y-m-d', strtotime($datetime));
            $data->time = date('H:i:00', strtotime($datetime));
            $data->datetime = date('Y-m-d H:i:00', strtotime($datetime));
            $data->note = $this->record_note;
            $data->timezone = $this->timezone;
            $data->save();
        } else if($this->record_type === 'transfer'){
            foreach(['expense', 'income'] as $type){
                $data = new \App\Models\Record();
                $data->user_id = \Auth::user()->id;
                $data->category_id = !empty($category) ? $category->id : null;
                $data->type = $type;
                $data->from_wallet_id = !empty($fromWallet) && !empty($toWallet) ? ($type === 'expense' ? $fromWallet->id : $toWallet->id) : null;
                $data->to_wallet_id = !empty($fromWallet) && !empty($toWallet) ? ($type === 'expense' ? $toWallet->id : $fromWallet->id) : null;
                $data->amount = $this->record_amount;
                $data->extra_type = $this->record_extra_type;
                $data->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
                $data->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount ? (($this->record_extra_amount * $this->record_amount) / 100) : 0) : ($this->record_extra_amount ?? 0);
                $data->date = date('Y-m-d', strtotime($datetime));
                $data->time = date('H:i:00', strtotime($datetime));
                $data->datetime = date('Y-m-d H:i:00', strtotime($datetime));
                $data->note = $this->record_note;
                $data->timezone = $this->timezone;
                $data->save();
            }
        }

        if(!$this->record_keepopen){
            $this->closeModal();
        }

        $this->reset($this->recordReset_list);
        if(($this->record_keepselectedcategory)){
            $this->record_category = $category->uuid;
        } 
        $this->dispatchBrowserEvent('recordModal-clearField');
    }

    /**
     * Browser Function
     */
    public function showModal()
    {
        $this->dispatchBrowserEvent('recordModal-modalShow');
    }
    public function closeModal()
    {
        $this->dispatchBrowserEvent('recordModal-modalHide');
    }
}
