<?php

namespace App\Http\Livewire\Component;

use App\Expenses;
use App\Http\Livewire\Component\Traits\CommonCard;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class TotalExpensesComponent extends Component
{
    use CommonCard;
    
    public function builder()
    {
        $result = Expenses::query()
            ->when($this->filterBy == 'yearly', function ($q) {
                $q->where(DB::raw('YEAR(expense_date)'), $this->year);
            })
            ->when($this->filterBy == 'date_ranged' && $this->dateRange, function ($q) {
                $ranged = explode(' - ', $this->dateRange);
                $q->whereBetween('expense_date', [$ranged[0], $ranged[1]]);
            })
            ->whereNotNull('expense_date')
            ->sum('total_amount');

        return number_format($result, 2, '.', ',');
    }

    public function render()
    {
        return view('livewire.component.total-expenses-component');
    }
}
