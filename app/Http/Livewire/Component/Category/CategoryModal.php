<?php

namespace App\Http\Livewire\Component\Category;

use Livewire\Component;

class CategoryModal extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $category_modalTitle = 'Category: Create new';
    public $category_keepopen = false;
    public $category_keepparent = false;
    public $categoryReset_list = [];
    // List
    public $categoryList_data = [];
    // Data
    public $category_uuid = null;
    public $category_parent = null;
    public $category_name = null;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'showModal' => 'showModal',
        'edit' => 'edit',
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->categoryReset_list = [
            'category_modalTitle',

            'category_uuid',
            'category_parent',
            'category_name'
        ];
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->fetchMainCategory();
        return view('livewire.component.category.category-modal');
    }

    /**
     * Custom Function
     */
    public function fetchMainCategory()
    {
        $data = \App\Models\Category::where('user_id', \Auth::user()->id)
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
        \Log::debug("Debug on Category Modal Save function", [
            'name' => $this->category_name,
            'modal_state' => $this->category_keepopen
        ]);

        $this->validate([
            'category_name' => ['required', 'string', 'max:191']
        ]);

        $parent = null;
        // Save to database
        $data = new \App\Models\Category();
        if(!empty($this->category_uuid)){
            $data = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $this->category_uuid)
                ->first();
        } else {
            if(!empty($this->category_parent)){
                $parent = \App\Models\Category::where('user_id', \Auth::user()->id)
                    ->where(\DB::raw('BINARY `uuid`'), $this->category_parent)
                    ->first();
            }
            $data->parent_id = !empty($parent) ? $parent->id : null;
        }
        

        $data->user_id = \Auth::user()->id;
        $data->name = $this->category_name;
        $data->save();

        if(!$this->category_keepopen){
            $this->closeModal();
        }

        $this->reset($this->categoryReset_list);
        if(!($this->category_keepparent)){
            $this->dispatchBrowserEvent('categoryModal-clearField');
        } else {
            $this->category_parent = $data->parent->uuid;
        }

        // Re-order Main Order
        if(empty($data->parent_id)){
            $lastOrder = -1;
            $checkLastOrder = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->orderBy('order_main', 'desc')
                ->first();
            if(!empty($checkLastOrder)){
                $lastOrder = $checkLastOrder->order_main;
            }
            $data->order_main = $lastOrder + 1;
            $data->save();
        } else {
            $lastOrder = -1;
            $lastOrderMain = -1;

            $checkLastOrder = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->where('parent_id', $data->parent_id)
                ->where('id', '!=', $data->id)
                ->orderBy('order_main', 'desc')
                ->first();
            if(!empty($checkLastOrder)){
                $lastOrder = $checkLastOrder->order;
                $lastOrderMain = $checkLastOrder->order_main;
            } else {
                $lastOrder = $data->parent->order;
                $lastOrderMain = $data->parent->order_main;
            }

            // Modify Order Main
            $others = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->where('order_main', '>', $lastOrderMain)
                ->orderBy('order_main', 'desc')
                ->get();
            foreach($others as $other){
                $other->order_main += 1;
                $other->save();
            }

            $data->order = $lastOrder + 1;
            $data->order_main = $lastOrderMain + 1;
            $data->save();
        }
        
    }
    public function edit($uuid)
    {
        $data = \App\Models\Category::where('user_id', \Auth::user()->id)
            ->where(\DB::raw('BINARY `uuid`'), $uuid)
            ->first();

        if(!empty($data)){
            $this->category_modalTitle = 'Category: Edit Data';

            $this->category_uuid = $data->uuid;
            $this->category_name = $data->name;

            $this->showModal();
        }
    }

    /**
     * Browser Function
     */
    public function showModal()
    {
        $this->dispatchBrowserEvent('categoryModal-modalShow');
    }
    public function closeModal()
    {
        $this->hydrate();
        $this->dispatchBrowserEvent('categoryModal-modalHide');
        $this->dispatchBrowserEvent('categoryModal-clearField');
        $this->reset($this->categoryReset_list);
        $this->reset(['category_keepopen', 'category_keepparent']);
    }
}