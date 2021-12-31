<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class SalesOrder extends Model
{
    use SoftDeletes;

    protected $guarded = ['id'];

    public static function generate()
    {
        return new static;
    }

    public function newSONo()
    {
        if (Preference::verify('so_auto') == 0) {
            return '';
        }

        $so_no_list = $this->newQuery()
            ->where('so_no', 'like', '%SO%')
            ->orderBy('id', 'desc')
            ->limit(1)
            ->get()
            ->toArray();
        $str_length = 5;
        $year       = Carbon::now()->format('y');

        if (isset($so_no_list[0]["so_no"])) {
            $so_no = $so_no_list[0]["so_no"];
        }

        if (count($so_no_list) == 0 || substr(explode('-', $so_no)[0], -2) != $year) {
            $num = 1;
            $str = substr("0000{$num}", -$str_length);

            return 'SO'.$year.'-'.$str;
        } else {
            $numbering = explode('-', $so_no)[1];
            $year      = Carbon::now()->format('y');
            $final_num = (int) $numbering + 1;
            $str       = substr("0000{$final_num}", -$str_length);

            return 'SO'.$year.'-'.$str;
        }
    }

    public static function updateInfo($overview)
    {
        self::query()->where('id', $overview['id'])->update($overview);
    }

    public function total($start, $end)
    {
        return $this->leftJoin('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
            ->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
            ->where('delivery_status', 'Shipped')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('sales_orders.created_at', [$start, $end]);
            });
    }

    public function totalQtn($start, $end)
    {
        return $this->leftJoin('customers', 'customers.id', '=', 'sales_orders.customer_id')
            ->leftJoin('summaries', 'summaries.sales_order_id', '=', 'sales_orders.id')
            ->orderBy('sales_orders.so_no', 'desc')
            ->when($start && $end, function ($q) use ($start, $end) {
                $q->whereBetween('sales_orders.created_at', [$start, $end]);
            });
    }
}
