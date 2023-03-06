<?php

namespace App\Http\Livewire\Sys\Category;

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
    public $categoryList_data;

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
        $this->menuState = 'category';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->fetchMainCategory();
        $this->dispatchBrowserEvent('categoryListInit-re_order');

        return view('livewire.sys.category.re-order')
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
            ]);
    }

    /**
     * Custom Function
     */
    public function fetchMainCategory()
    {
        // Category
        $this->categoryList_data = \App\Models\Category::with('child', 'parent')
            ->where('user_id', \Auth::user()->id)
            ->whereNull('parent_id')
            ->orderBy('order_main', 'asc')
            ->get();
    }
    public function reOrder($order = null)
    {
        \Log::debug("Debug on Re Order function ~ \App\Http\Livewire\Sys\Category\ReOrder", [
            'order' => $order
        ]);

        if($order === null){
            // Create Category Re-Order Request
            $allParentCategory = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->whereNull('parent_id')
                // ->orderBy('order_main', 'asc')
                ->orderByRaw('ISNULL(order_main), order_main ASC')
                ->get();
            $formatedRequest = [];
            if (count($allParentCategory) > 0) {
                foreach ($allParentCategory as $category) {
                    $arr = [
                        'id' => $category->uuid,
                    ];

                    if ($category->child()->exists()) {
                        $childArr = [];
                        foreach ($category->child()->orderBy('order', 'asc')->get() as $child) {
                            $childArr[] = [
                                'id' => $child->uuid,
                            ];
                        }

                        $arr = [
                            'id' => $category->uuid,
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
            $category = \App\Models\Category::where('user_id', \Auth::user()->id)
                ->where(\DB::raw('BINARY `uuid`'), $hierarchy['id'])
                ->firstOrFail();

            // $category->order = $numorder;
            $category->order_main = $numorderMain;
            if (!empty($category->parent_id)) {
                $category->parent_id = null;
            }
            $category->save();

            // Request has Child Category
            if (isset($hierarchy['child']) && is_array($hierarchy['child']) && count($hierarchy['child']) > 0) {
                $childOrder = 1;
                foreach ($hierarchy['child'] as $child) {
                    $numorderMain++;

                    // Update Child Order
                    $subcategory = \App\Models\Category::where('user_id', \Auth::user()->id)
                        ->where(\DB::raw('BINARY `uuid`'), $child['id'])
                        ->firstOrFail();
                    $subcategory->order = $childOrder;
                    $subcategory->order_main = $numorderMain;
                    $subcategory->parent_id = $category->id;
                    $subcategory->save();

                    $childOrder++;
                }
            }

            $numorderMain++;
            $numorder++;
        }
    }
}
