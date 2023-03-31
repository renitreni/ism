<?php

namespace App\Http\Livewire\Component;

use App\PurchaseInfo;
use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Http\Livewire\Component\Traits\CommonCard;

class TotalPOComponent extends Component
{
    use CommonCard;

    public function builder()
    {
        $result = PurchaseInfo::query()->selectRaw('purchase_infos.due_date, purchase_infos.po_no,
        subject,
        vendors.name,
        purchase_infos.payment_status,
        summaries.grand_total')
            ->leftJoin('vendors', 'vendors.id', '=', 'purchase_infos.vendor_id')
            ->join('summaries', 'summaries.purchase_order_id', '=', 'purchase_infos.id')
            ->orderBy('purchase_infos.po_no', 'desc')
            ->where('status', 'Received')
            ->whereNull('sales_order_id')
            ->when($this->filterBy == 'yearly', function ($q) {
                $q->where(DB::raw('YEAR(purchase_infos.created_at)'), $this->year);
            })
            ->when($this->filterBy == 'date_ranged' && $this->dateRange, function ($q) {
                $ranged = explode(' - ', $this->dateRange);
                $q->whereBetween('purchase_infos.created_at', [$ranged[0], $ranged[1]]);
            })
            ->whereNotNull('purchase_infos.created_at')
            ->sum('grand_total');

        return number_format($result, 2, '.', ',');
    }

    public function render()
    {
        return view('livewire.component.total-p-o-component');
    }
}
