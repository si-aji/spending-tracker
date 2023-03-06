<?php

namespace App\Http\Livewire\Sys\Category;

use Livewire\Component;

class Index extends Component
{
    /**
     * Sidebar Configuration
     */
    public $menuState = null;
    public $submenuState = null;

    /**
     * Component Variable
     */
    public $category_filter_state = 'active';
    public $category_filter_search = '';
    // Data
    public $dataCategory = [];
    // Paginate Conf
    public $loadPerPage = 10;

    /**
     * Validation
     */
    // 

    /**
     * Livewire Event Listener
     */
    protected $listeners = [
        'refreshComponent' => '$refresh'
    ];

    /**
     * Livewire Mount
     */
    public function mount()
    {
        $this->menuState = 'category';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->dataCategory = \App\Models\Category::with('parent')
            ->where('user_id', \Auth::user()->id)
            ->orderBy('order_main', 'asc');

        if($this->category_filter_search != ''){
            $this->dataCategory->where(function($q){
                return $q->where('name', 'like', '%'.$this->category_filter_search.'%')
                    ->orWhereHas('parent', function($q){
                        return $q->where('name', 'like', '%'.$this->category_filter_search.'%');
                    });
            });
        }

        $this->dataCategory = $this->dataCategory->paginate($this->loadPerPage);
        $paginate = $this->dataCategory;
        $this->dataCategory = collect($this->dataCategory->items())->values()->map(function($data){
            // $data->balance = $data->getBalance();
            // $data->last_transaction = $data->getLastTransaction();
            return $data;
        });

        $this->dispatchBrowserEvent('categoryListLoadData');
        return view('livewire.sys.category.index', [
                'paginate' => $paginate
            ])
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
            ]);
    }

    /**
     * Custom Function
     */
}