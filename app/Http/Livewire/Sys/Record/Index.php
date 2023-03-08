<?php

namespace App\Http\Livewire\Sys\Record;

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
    public $user_first_year = null;
    // Load More Conf
    public $loadPerPage = 10,
        $timezone = null;
    // Filter
    public $filter_selected_year = null;
    public $filter_selected_month = null;
    // Data
    public $dataRecord = [];
    protected $recordPaginate;

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
        $this->menuState = 'record';
        $this->submenuState = null;
    }

    /**
     * Livewire Component Render
     */
    public function render()
    {
        $this->user_first_year = \Auth::user()->getFirstYear();
        $this->fetchRecordData();

        return view('livewire.sys.record.index', [
                'paginate' => $this->recordPaginate
            ])
            ->extends('layouts.sys', [
                'menuState' => $this->menuState,
                'submenuState' => $this->submenuState
            ]);
    }

    /**
     * Custom Function
     */
    public function fetchRecordData($selectedWallet = null) : void
    {
        $this->dataRecord = \App\Models\Record::with('fromWallet.parent', 'toWallet.parent', 'category.parent')
            ->where('user_id', \Auth::user()->id);

        $this->dataRecord = $this->dataRecord->orderBy('datetime', 'desc')
            // ->orderBy('type', 'asc')
            // ->orderBy('created_at', 'desc')
            ->get();
        $this->dataRecord = collect($this->dataRecord);
        if($this->filter_selected_month){
            $this->dataRecord = $this->dataRecord->filter(function($record){
                // Compromize can't use convert_tz on shared hosting
                $recordDateTime = new \DateTime(date("Y-m-d H:i:s", strtotime($record->datetime)));
                if(!empty($this->timezone)){
                    $recordDateTime = $recordDateTime->setTimezone(new \DateTimeZone(\Session::get('SAUSER_TZ')))->format('Y-m-d H:i:s');
                    // $recordDateTime = (new \DateTime($recordDateTime, new \DateTimeZone(\Session::get('SAUSER_TZ'))))->format('Y-m-d H:i:s');
                } else {
                    $recordDateTime = $recordDateTime->format('Y-m-d H:i:s');
                }
                
                return (date("m", strtotime($recordDateTime)) === date("m", strtotime($this->filter_selected_month))) && date("Y", strtotime($recordDateTime)) === date("Y", strtotime($this->filter_selected_year));
            });
        }
        $this->recordPaginate = $this->dataRecord->paginate($this->loadPerPage);
        $this->dataRecord = $this->dataRecord->values()->take($this->loadPerPage);

        $this->dispatchBrowserEvent('fetchData');
    }
    public function loadMore()
    {
        $this->loadPerPage += $this->loadPerPage;
        $this->dispatchBrowserEvent('recordListLoadData');
    }
    public function getPaginate()
    {
        return $this->recordPaginate;
    }
}
