<?php

use Illuminate\Database\Seeder;

class OrderFormTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        for ($x = 0; $x < 1000; $x++) {
            $faker                     = \Faker\Factory::create();
            $order_form                = new \App\OrderForm();
            $order_form->customer_id   = \App\Customer::all()->random(2)[0]->id;
            $order_form->acct_exec     = $faker->firstName;
            $order_form->no            = $faker->randomNumber();
            $order_form->so_no         = '';
            $order_form->po_no         ='';
            $order_form->prepared_by   = \App\User::all()->random(2)[0]->id;
            $order_form->stock_card_in = $faker->randomNumber();
            $order_form->plate_no      = $faker->randomNumber();
            $order_form->driver        = $faker->firstName();
            $order_form->save();
        }
    }
}
