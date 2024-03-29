<?php

namespace App;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Supply extends Model
{
    protected $fillable = [];

    public static function decreCount($product_id, $quantity)
    {
        self::query()->where('product_id', $product_id)->decrement('quantity', $quantity);
    }


    public static function increCount($product_id, $quantity)
    {
        self::query()->where('product_id', $product_id)->increment('quantity', $quantity);
    }

    public static function recalibrate()
    {

        $data_po = DB::table('purchase_infos')
            ->select(['product_details.product_id'])
            ->join('product_details', 'product_details.purchase_order_id', '=', 'purchase_infos.id')
            ->whereBetween('purchase_infos.updated_at', [Carbon::now()->subMonth(), Carbon::now()])
            ->get()
            ->pluck('product_id')
            ->toArray();

        $data_so = DB::table('sales_orders')
            ->select(['product_details.product_id'])
            ->join('product_details', 'product_details.sales_order_id', '=', 'sales_orders.id')
            ->whereBetween('sales_orders.updated_at', [Carbon::now()->subMonth(), Carbon::now()])
            ->get()
            ->pluck('product_id')
            ->toArray();

        $merged    = array_merge($data_po, $data_so);
        $data      = Product::query()->where('type', 'limited')->whereIn('id', $merged)->get()->pluck('id');
        $unlimited = Product::query()->where('type', 'unlimited')->get()->pluck('id');
        foreach ($data as $value) {
            $so = DB::table('sales_orders')
                ->join('product_details', 'product_details.sales_order_id', '=', 'sales_orders.id')
                ->where('sales_orders.delivery_status', 'Shipped')
                ->where('product_id', $value)
                ->sum('product_details.qty');

            $po = DB::table('purchase_infos')
                ->join('product_details', 'product_details.purchase_order_id', '=', 'purchase_infos.id')
                ->where('purchase_infos.status', 'Received')
                ->where('product_id', $value)
                ->sum('product_details.qty');

            $final_qty = $po - $so;
            self::query()->where('product_id', $value)->update(['quantity' => $final_qty]);
        }
        self::query()->whereIn('product_id', $unlimited)->update(['quantity' => 0]);
    }

    public function results()
    : Builder
    {
        return $this->query()
            ->selectRaw('supplies.*, products.name as product_name, products.manual_id, products.code')
            ->join('products', 'products.id', '=', 'supplies.product_id');
    }
}
