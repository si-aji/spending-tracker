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
    public $categoryData = null;
    public $fromWalletData = null;
    public $toWalletData = null;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'showModal' => 'showModal',
        'hideModal' => 'hideModal',
        'edit' => 'edit',
        'switchTransferWallet' => 'switchTransferWallet'
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
            'record_note',

            'categoryData',
            'fromWalletData',
            'toWalletData',
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
    public function getCategoryData()
    {
        if(!empty($this->record_category) && is_array($this->record_category) && isset($this->record_category['value'])){
            $this->record_category = $this->record_category['value'];
        }

        $data = \App\Models\Category::with('parent')
            ->where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $this->record_category)
            ->first();

        $this->categoryData = collect($data);
    }
    public function getFromWalletData()
    {
        if(!empty($this->record_from_wallet) && is_array($this->record_from_wallet) && isset($this->record_from_wallet['value'])){
            $this->record_from_wallet = $this->record_from_wallet['value'];
        }

        if($this->record_from_wallet){
            $data = \App\Models\Wallet::with('parent')
                ->where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->record_from_wallet)
                ->first();

            $this->fromWalletData = collect($data);
        }
    }
    public function getToWalletData()
    {
        if(!empty($this->record_to_wallet) && is_array($this->record_to_wallet) && isset($this->record_to_wallet['value'])){
            $this->record_to_wallet = $this->record_to_wallet['value'];
        }

        if($this->record_to_wallet){
            $data = \App\Models\Wallet::with('parent')
                ->where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->record_to_wallet)
                ->first();

            $this->toWalletData = collect($data);
        }
    }

    /**
     * 
     */
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

        // Run action based on Action
        if(!empty($this->record_uuid)){
            $data = \App\Models\Record::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->record_uuid)
                ->firstOrFail();
            $this->update($data);
        } else {
            $this->store($data);
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
    public function store($data)
    {
        // Convert datetime
        $datetime = date("Y-m-d H:i:00", strtotime($this->record_timestamp));
        if(!empty($this->timezone)){
            $timezone = $this->timezone;
            $formated = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime, $timezone);
            $datetime = $formated->setTimezone('UTC')->format('Y-m-d H:i:s');
        }

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
    }
    public function update($data)
    {
        // Validate if Record Type is changed
        $type = $data->type;
        if(!empty($data->to_wallet_id)){
            $type = 'transfer';
        }

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

        // if($type !== $this->record_type){
        //     if($type === 'transfer'){
        //         $related = null;
        //         // Previous type is transfer, so remove related data
        //         if($data->type === 'income'){
        //             $related = $data;
        //             // Change to expense
        //             $data = \App\Models\Record::where('user_id', \Auth::user()->id)
        //                 ->where('type', 'expense')
        //                 ->where('from_wallet_id', $data->to_wallet_id)
        //                 ->where('to_wallet_id', $data->from_wallet_id)
        //                 ->where('amount', $data->amount)
        //                 ->where('extra_type', $data->extra_type)
        //                 ->where('extra_percentage', $data->extra_percentage)
        //                 ->where('extra_amount', $data->extra_amount)
        //                 ->where('note', $data->note)
        //                 ->where('datetime', $data->datetime)
        //                 ->where('created_at', $data->created_at)
        //                 ->firstOrFail();
        //         } else {
        //             $related = \App\Models\Record::where('user_id', \Auth::user()->id)
        //                 ->where('type', 'income')
        //                 ->where('from_wallet_id', $data->to_wallet_id)
        //                 ->where('to_wallet_id', $data->from_wallet_id)
        //                 ->where('amount', $data->amount)
        //                 ->where('extra_type', $data->extra_type)
        //                 ->where('extra_percentage', $data->extra_percentage)
        //                 ->where('extra_amount', $data->extra_amount)
        //                 ->where('note', $data->note)
        //                 ->where('datetime', $data->datetime)
        //                 ->where('created_at', $data->created_at)
        //                 ->firstOrFail();
        //         }

        //         // Remove related
        //         if(!empty($related)){
        //             $data->to_wallet_id = null;
        //             $related->delete();
        //         }
        //     }

        //     if($this->record_type === 'transfer'){
        //         // New record is transfer, and previous record is not transfer
        //     } else {
        //         // Update record from transfer to either expense/income
        //         $data->type = $this->record_type;
        //     }
        // }

        if($type !== $this->record_type){
            // Check previous type
            if($type === 'transfer'){
                // Previous record yype is transfer, new record is either expense / income
                $related = null;
                if($data->type === 'income'){
                    $related = $data;
                    // Change to expense
                    $data = \App\Models\Record::where('user_id', \Auth::user()->id)
                        ->where('type', 'expense')
                        ->where('from_wallet_id', $data->to_wallet_id)
                        ->where('to_wallet_id', $data->from_wallet_id)
                        ->where('amount', $data->amount)
                        ->where('extra_type', $data->extra_type)
                        ->where('extra_percentage', $data->extra_percentage)
                        ->where('extra_amount', $data->extra_amount)
                        ->where('note', $data->note)
                        ->where('datetime', $data->datetime)
                        ->where('created_at', $data->created_at)
                        ->firstOrFail();
                } else {
                    $related = \App\Models\Record::where('user_id', \Auth::user()->id)
                        ->where('type', 'income')
                        ->where('from_wallet_id', $data->to_wallet_id)
                        ->where('to_wallet_id', $data->from_wallet_id)
                        ->where('amount', $data->amount)
                        ->where('extra_type', $data->extra_type)
                        ->where('extra_percentage', $data->extra_percentage)
                        ->where('extra_amount', $data->extra_amount)
                        ->where('note', $data->note)
                        ->where('datetime', $data->datetime)
                        ->where('created_at', $data->created_at)
                        ->firstOrFail();
                }

                // Remove related
                if(!empty($related)){
                    $data->to_wallet_id = null;
                    $related->delete();
                }

                // Update data
                $data->category_id = $category->id;
                $data->from_wallet_id = !empty($fromWallet) ? $fromWallet->id : null;
                $data->to_wallet_id = !empty($toWallet) ? $toWallet->id : null;
                $data->amount = $this->record_amount;
                $data->extra_type = $this->record_extra_type;
                $data->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
                $data->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount * $this->record_amount) / 100 : $this->record_extra_amount;
                $data->note = $this->record_note;
                $data->date = date('Y-m-d', strtotime($datetime));
                $data->time = date('H:i:00', strtotime($datetime));
                $data->datetime = date('Y-m-d H:i:00', strtotime($datetime));
                $data->timezone = $this->timezone;
                $data->save();
            } else {
                // Update Data, previous record type is either expense / income
                $data->category_id = $category->id;
                $data->from_wallet_id = $data->type === 'expense' ? $fromWallet->id : $toWallet->id;
                $data->to_wallet_id = $data->type === 'expense' ? $toWallet->id : $fromWallet->id;
                $data->amount = $this->record_amount;
                $data->extra_type = $this->record_extra_type;
                $data->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
                $data->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount * $this->record_amount) / 100 : $this->record_extra_amount;
                $data->note = $this->record_note;
                $data->date = date('Y-m-d', strtotime($datetime));
                $data->time = date('H:i:00', strtotime($datetime));
                $data->datetime = date('Y-m-d H:i:00', strtotime($datetime));
                $data->timezone = $this->timezone;
                $data->save();

                // Create related
                $related = new \App\Models\Record();
                $related->user_id = \Auth::user()->id;
                $related->type = $data->type === 'expense' ? 'income' : 'expense';
                $related->category_id = $data->category->id;
                $related->from_wallet_id = $data->type === 'expense' ? $toWallet->id : $fromWallet->id;
                $related->to_wallet_id = $data->type === 'expense' ? $fromWallet->id : $toWallet->id;
                $related->amount = $data->amount;
                $related->extra_type = $data->extra_type;
                $related->extra_percentage = $data->extra_percentage;
                $related->extra_amount = $data->extra_amount;
                $related->note = $data->note;
                $related->date = date('Y-m-d', strtotime($data->date));
                $related->time = date('H:i:00', strtotime($data->time));
                $related->datetime = date('Y-m-d H:i:00', strtotime($data->datetime));
                $related->timezone = $data->timezone;
                $related->save();
            }
        } else {
            // Record type is not changed
            if(!empty($data->to_wallet_id)){
                // Transfer
                if($data->type === 'income'){
                    $related = $data;
                    // Change to expense
                    $data = \App\Models\Record::where('user_id', \Auth::user()->id)
                        ->where('type', 'expense')
                        ->where('from_wallet_id', $data->to_wallet_id)
                        ->where('to_wallet_id', $data->from_wallet_id)
                        ->where('amount', $data->amount)
                        ->where('extra_type', $data->extra_type)
                        ->where('extra_percentage', $data->extra_percentage)
                        ->where('extra_amount', $data->extra_amount)
                        ->where('note', $data->note)
                        ->where('datetime', $data->datetime)
                        ->where('updated_at', $data->updated_at)
                        ->firstOrFail();
                } else {
                    $related = \App\Models\Record::where('user_id', \Auth::user()->id)
                        ->where('type', 'income')
                        ->where('from_wallet_id', $data->to_wallet_id)
                        ->where('to_wallet_id', $data->from_wallet_id)
                        ->where('amount', $data->amount)
                        ->where('extra_type', $data->extra_type)
                        ->where('extra_percentage', $data->extra_percentage)
                        ->where('extra_amount', $data->extra_amount)
                        ->where('note', $data->note)
                        ->where('datetime', $data->datetime)
                        ->where('updated_at', $data->updated_at)
                        ->firstOrFail();
                }

                // Update Data
                $data->category_id = $category->id;
                $data->from_wallet_id = !empty($fromWallet) ? $fromWallet->id : null;
                $data->to_wallet_id = !empty($toWallet) ? $toWallet->id : null;
                $data->amount = $this->record_amount;
                $data->extra_type = $this->record_extra_type;
                $data->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
                $data->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount * $this->record_amount) / 100 : $this->record_extra_amount;
                $data->note = $this->record_note;
                $data->date = date('Y-m-d', strtotime($datetime));
                $data->time = date('H:i:00', strtotime($datetime));
                $data->datetime = date('Y-m-d H:i:00', strtotime($datetime));
                $data->timezone = $this->timezone;
                $data->save();

                // Update Related Data
                $related->category_id = $category->id;
                $related->from_wallet_id = !empty($toWallet) ? $toWallet->id : null;
                $related->to_wallet_id = !empty($fromWallet) ? $fromWallet->id : null;
                $related->amount = $this->record_amount;
                $related->extra_type = $this->record_extra_type;
                $related->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
                $related->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount * $this->record_amount) / 100 : $this->record_extra_amount;
                $related->note = $this->record_note;
                $related->date = date('Y-m-d', strtotime($datetime));
                $related->time = date('H:i:00', strtotime($datetime));
                $related->datetime = date('Y-m-d H:i:00', strtotime($datetime));
                $related->timezone = $this->timezone;
                $related->save();
            } else {
                // Expense / Income
                $data->type = $this->record_type;
                $data->category_id = $category->id;
                $data->from_wallet_id = !empty($fromWallet) ? $fromWallet->id : null;
                $data->to_wallet_id = !empty($toWallet) ? $toWallet->id : null;
                $data->amount = $this->record_amount;
                $data->extra_type = $this->record_extra_type;
                $data->extra_percentage = $this->record_extra_type === 'percentage' ? $this->record_extra_amount : 0;
                $data->extra_amount = $this->record_extra_type === 'percentage' ? ($this->record_extra_amount * $this->record_amount) / 100 : $this->record_extra_amount;
                $data->note = $this->record_note;
                $data->date = date('Y-m-d', strtotime($datetime));
                $data->time = date('H:i:00', strtotime($datetime));
                $data->datetime = date('Y-m-d H:i:00', strtotime($datetime));
                $data->timezone = $this->timezone;
                $data->save();
            }
        }
    }

    public function edit($uuid)
    {
        $data = \App\Models\Record::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->firstOrFail();

        $this->categoryData = !empty($data->category_id) ? collect($data->category->load('parent')) : null;

        $this->record_modalTitle = 'Record: Edit Data';
        $this->record_uuid = $data->uuid;
        $this->record_type = !empty($data->to_wallet_id) ? 'transfer' : $data->type;
        $this->record_category = !empty($data->category_id) ? $data->category->uuid : null;
        $this->record_amount = $data->amount;
        $this->record_extra_type = $data->extra_type;
        $this->record_extra_amount = $data->extra_type === 'percentage' ? $data->extra_percentage : $data->extra_amount;
        $this->record_note = $data->note;
        $this->record_timestamp = $data->datetime;
        $this->timezone = $data->timezone;

        if(!empty($data->to_wallet_id)){
            // Transfer
            if($data->type === 'expense'){
                $this->fromWalletData = !empty($data->from_wallet_id) ? collect($data->fromWallet->load('parent')) : null;
                $this->toWalletData = !empty($data->to_wallet_id) ? collect($data->toWallet->load('parent')) : null;

                $this->record_from_wallet = !empty($data->from_wallet_id) ? $data->fromWallet->uuid : null;
                $this->record_to_wallet = !empty($data->to_wallet_id) ? $data->toWallet->uuid : null;
            } else {
                $this->fromWalletData = !empty($data->to_wallet_id) ? collect($data->toWallet->load('parent')) : null;
                $this->toWalletData = !empty($data->from_wallet_id) ? collect($data->fromWallet->load('parent')) : null;

                $this->record_from_wallet = !empty($data->to_wallet_id) ? $data->toWallet->uuid : null;
                $this->record_to_wallet = !empty($data->from_wallet_id) ? $data->fromWallet->uuid : null;
            }
        } else {
            $this->fromWalletData = !empty($data->from_wallet_id) ? collect($data->fromWallet->load('parent')) : null;
            $this->record_from_wallet = !empty($data->from_wallet_id) ? $data->fromWallet->uuid : null;
        }

        $this->showModal();
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
        $this->hydrate();
        $this->dispatchBrowserEvent('recordModal-modalHide');
        $this->reset($this->recordReset_list);
        $this->reset(['record_keepopen', 'record_keepselectedcategory']);
    }
    public function switchTransferWallet()
    {
        $this->getFromWalletData();
        $this->getToWalletData();
        $this->dispatchBrowserEvent('recordModal-switchWallet');
    }
}
