<?php

use Illuminate\Database\Seeder;

class UpgradeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\SalesOrder::query()->where('status', 'Shipped')
                       ->update([
                           'delivery_status' => 'Shipped',
                       ]);

        \App\SalesOrder::query()->where('status', '<>', 'Shipped')
                       ->update([
                           'delivery_status' => 'Not Shipped',
                       ]);
    }
}
