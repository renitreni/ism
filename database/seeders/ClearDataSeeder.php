<?php

use Illuminate\Database\Seeder;

class ClearDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Customer::truncate();
        \App\OrderForm::truncate();
        \App\ProductDetail::truncate();
        \App\Product::truncate();
        \App\PurchaseInfo::truncate();
        \App\SalesOrder::truncate();
        \App\Summary::truncate();
        \App\SalesOrder::truncate();
        \App\Supply::truncate();
        \App\Vendor::truncate();
    }
}
