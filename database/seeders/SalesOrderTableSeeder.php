<?php

use App\Customer;
use App\SalesOrder;
use App\User;
use Faker\Factory;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;

class SalesOrderTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        SalesOrder::truncate();
        for($x=0; $x < 1000; $x++) {
            $faker = Factory::create();
            $purchase_info = new SalesOrder();
            $purchase_info->customer_id  = Customer::all()->random(2)[0]->id;
            $purchase_info->subject = $faker->company;
            $purchase_info->owner = $faker->firstName();
            $purchase_info->agent = $faker->firstName();
            $purchase_info->assigned_to = User::all()->random(2)[0]->id;
            $purchase_info->status = 'Quote';
            $purchase_info->address = $faker->address;
            $purchase_info->due_date = $faker->dateTime();
            $purchase_info->payment_method = $faker->randomElement(['Cash','Credit','Check']);
            $purchase_info->account_name = $faker->firstName();
            $purchase_info->phone = $faker->firstName();
            $purchase_info->fax = $faker->firstName();
            $purchase_info->email = $faker->firstName();
            $purchase_info->so_no = $faker->randomNumber();
            $purchase_info->account_no = $faker->bankAccountNumber;
            $purchase_info->tac = $faker->paragraph();
            $purchase_info->save();
        }
    }
}
