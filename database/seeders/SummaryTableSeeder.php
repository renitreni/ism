<?php

use App\PurchaseInfo;
use App\SalesOrder;
use App\Summary;
use Illuminate\Database\Seeder;
use Faker\Factory;

class SummaryTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Summary::truncate();
        $ids = PurchaseInfo::query()->select('id')->get()->pluck('id');
        foreach($ids as $id) {
            $faker = Factory::create();
            $summary = new Summary();
            $summary->purchase_order_id = $id;
            $summary->discount = $faker->randomDigit();
            $summary->sub_total = 0;
            $summary->shipping = $faker->randomDigit();
            $summary->sales_tax = $faker->randomDigit();
            $summary->save();
        }

        $ids = SalesOrder::query()->select('id')->get()->pluck('id');
        foreach($ids as $id) {
            $faker = Factory::create();
            $summary = new Summary();
            $summary->sales_order_id = $id;
            $summary->discount = $faker->randomDigit();
            $summary->sub_total = 0;
            $summary->shipping = $faker->randomDigit();
            $summary->sales_tax = $faker->randomDigit();
            $summary->save();
        }
    }
}
