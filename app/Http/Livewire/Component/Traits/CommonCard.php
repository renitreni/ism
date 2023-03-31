<?php 
namespace App\Http\Livewire\Component\Traits;

trait CommonCard {

    public $dateRange;

    public $filterBy;

    public $yearList;

    public $total;

    public $year;

    public function mount()
    {
        $prevYear = 2020;
        $this->year = now()->format('Y');
        $this->filterBy = 'yearly';
        $this->total = $this->builder();

        do {
            $this->yearList[] = $prevYear;
            ++$prevYear;
        } while (now()->format('Y') >= $prevYear);
    }

    public function updatedFilterBy()
    {
        $this->dispatchBrowserEvent('fetch');
        $this->total = $this->builder();
    }

    public function updatedYear()
    {
        $this->dispatchBrowserEvent('fetch');
        $this->total = $this->builder();
    }

    public function updatedDateRange()
    {
        $this->total = $this->builder();
    }
}