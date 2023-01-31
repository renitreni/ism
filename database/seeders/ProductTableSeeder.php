<?php

use App\Product;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x = 0; $x < 1000; $x++) {
            $faker                  = Factory::create();
            $product                = new Product();
            $product->manual_id     = $faker->randomNumber();
            $product->name          = $faker->word();
            $product->code          = $faker->creditCardNumber();
            $product->category      = \App\Category::all()->random(2)[0]->name;
            $product->manufacturer  = $faker->company;
            $product->unit          = $faker->word;
            $product->description   = $faker->paragraph();
            $product->batch         = $faker->randomNumber();
            $product->color         = $faker->colorName;
            $product->size          = $faker->randomFloat();
            $product->selling_price = 0;
            $product->vendor_price  = 0;
            $product->weight        = $faker->randomFloat();
            $product->assigned_to   = User::all()->random(2)[0]->id;
            $product->save();
        }
    }
}
