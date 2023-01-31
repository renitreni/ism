<?php

use App\Product;
use App\ProductDetail;
use App\PurchaseInfo;
use App\Supply;
use Illuminate\Database\Seeder;
use Faker\Factory;

class ProductDetailTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProductDetail::truncate();
        for ($x = 0; $x < 1000; $x++) {
            $faker                             = Factory::create();
            $product                           = Product::all()->random(5)[1];
            $supply                            = Supply::query()->where('product_id', $product->id)->get()[0];
            $product_detail                    = new ProductDetail();
            $product_detail->purchase_order_id = PurchaseInfo::all()->random(10)[3]->id;
            $product_detail->product_id        = $product->id;
            $product_detail->product_name      = $product->name;
            $product_detail->product_code      = $product->code;
            $product_detail->notes             = $faker->paragraph;
            $product_detail->qty               = 2;
            $product_detail->selling_price     = $supply->unit_cost;
            $product_detail->vendor_price      = $faker->randomDigit;
            $product_detail->discount_item     = $faker->randomDigit;
            $product_detail->save();
        }

        for ($x = 0; $x < 1000; $x++) {
            $faker                          = Factory::create();
            $product                        = Product::all()->random(5)[1];
            $product_detail                 = new ProductDetail();
            $product_detail->sales_order_id = PurchaseInfo::all()->random(10)[3]->id;
            $product_detail->product_id     = $product->id;
            $product_detail->product_name   = $product->name;
            $product_detail->product_code   = $product->code;
            $product_detail->notes          = $faker->paragraph;
            $product_detail->qty            = 2;
            $product_detail->labor_cost     = $faker->randomDigit;
            $product_detail->discount_item  = $faker->randomDigit;
            $product_detail->save();
        }
    }
}
