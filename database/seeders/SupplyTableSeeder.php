<?php

use App\Product;
use App\Supply;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use App\User;

class SupplyTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Supply::truncate();
        $products = Product::all();
        foreach($products as $product){
            $faker = Factory::create();
            $supply = new Supply();
            $supply->product_id = $product->id;
            $supply->quantity = $faker->randomDigit;
            $supply->unit_cost = $faker->randomDigit;
            $supply->assigned_to = User::all()->random(2)[0]->id;
            $supply->save();
        }

    }
}
