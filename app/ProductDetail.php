<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDetail extends Model
{
    protected $guarded = ['id'];

    public static function fetchDataPO($purchase_order_id)
    {
        return self::query()
            ->where('purchase_order_id', $purchase_order_id)
            ->get()
            ->toArray();
    }

    public static function fetchDataSO($sales_order_id)
    {
        return self::query()
            ->where('sales_order_id', $sales_order_id)
            ->get()
            ->toArray();
    }

    public function getTotalProject($start = null, $end = null)
    {
        return $this->selectRaw('so.due_date,
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
            ->when($start && $end, function ($q) use ($start, $end) {
                return $q->whereBetween('due_date', [$start, $end]);
            })
            ->whereNull('purchase_order_id')
            ->orderBy('so.so_no', 'desc');
    }
}
