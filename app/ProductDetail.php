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

    public function getLabor()
    {
        return $this->whereRaw("lower(product_name) LIKE '%labor%'")
            ->join('sales_orders', 'sales_orders.id', '=', 'product_details.sales_order_id');
    }
}
