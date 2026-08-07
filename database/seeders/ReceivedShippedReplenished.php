<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\PurchaseInfo;
use App\SalesOrder;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ReceivedShippedReplenished extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class=ReceivedShippedReplenished
     *
     * @return void
     */
    public function run()
    {
        PurchaseInfo::where('status', 'Received')->each(function ($item) {
            DB::table('purchase_infos')
                ->where('id', $item['id'])
                ->update(['received_date' => Carbon::parse($item['updated_at'])->format('Y-m-d')]);
        });


        SalesOrder::where('delivery_status', 'Shipped')->each(function ($item) {
            DB::table('sales_orders')
                ->where('id', $item['id'])
                ->update(['shipped_date' => Carbon::parse($item['shipped_date'])->format('Y-m-d')]);
        });
    }
}
