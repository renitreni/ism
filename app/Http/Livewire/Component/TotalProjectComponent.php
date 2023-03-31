<?php

namespace App\Http\Livewire\Component;

use App\ProductDetail;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Component\Traits\CommonCard;

class TotalProjectComponent extends Component
{
    use CommonCard;

    public function builder()
    {
        $result = ProductDetail::query()
            ->selectRaw('so.due_date,
        so.agent,
        so.status,
        so.so_no,
        c.name,
        product_name,
        qty,
        vendor_price,
        selling_price,
        (qty * selling_price) as subtotal,
        so.payment_status,
        so.payment_method')
            ->join('sales_orders as so', 'so.id', '=', 'product_details.sales_order_id')
            ->join('customers as c', 'c.id', '=', 'so.customer_id')
            ->where('so.status', 'Project')
            ->when($this->filterBy == 'yearly', function ($q) {
                $q->where(DB::raw('YEAR(due_date)'), $this->year);
            })
            ->when($this->filterBy == 'date_ranged' && $this->dateRange, function ($q) {
                $ranged = explode(' - ', $this->dateRange);
                $q->whereBetween('due_date', [$ranged[0], $ranged[1]]);
            })
            ->whereNull('purchase_order_id')
            ->orderBy('so.so_no', 'desc');

        return number_format($this->computeStock($result, 'subtotal'), 2, '.', ',');
    }

    public function computeStock($result, $key = 'total')
    {
        $hold = 0;
        foreach ($result->get()->toArray() as $value) {
            $hold += $value[$key];
        }

        return $hold;
    }

    public function render()
    {
        return view('livewire.component.total-project-component');
    }
}
