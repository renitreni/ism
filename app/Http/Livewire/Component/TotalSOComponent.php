<?php

namespace App\Http\Livewire\Component;

use App\SalesOrder;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Component\Traits\CommonCard;

class TotalSOComponent extends Component
{
    use CommonCard;

    public function builder()
    {
        $result = SalesOrder::query()->join('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
            ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
            ->where('delivery_status', 'Shipped')
            ->whereNull('purchase_order_id')
            ->when($this->filterBy == 'yearly', function ($q) {
                $q->where(DB::raw('YEAR(sales_orders.created_at)'), $this->year);
            })
            ->when($this->filterBy == 'date_ranged' && $this->dateRange, function ($q) {
                $ranged = explode(' - ', $this->dateRange);
                $q->whereBetween('sales_orders.created_at', [$ranged[0], $ranged[1]]);
            })
            ->whereNotNull('sales_orders.created_at')
            ->sum('grand_total');

        return number_format($result, 2, '.', ',');
    }

    public function render()
    {
        return view('livewire.component.total-s-o-component');
    }
}
