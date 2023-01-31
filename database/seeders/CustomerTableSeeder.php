<?php

use App\Customer;
use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use Faker\Factory;
use App\User;

class CustomerTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();

        Customer::truncate();
        for ($x = 0; $x < 3000; $x++) {
            $faker                    = Factory::create();
            $customer                 = new Customer();
            $customer->name           = $faker->company;
            $customer->contact_person = $faker->firstName();
            $customer->landline       = $faker->phoneNumber;
            $customer->mobile_phone   = $faker->phoneNumber;
            $customer->email          = $faker->safeEmail;
            $customer->address        = $faker->address;
            $customer->payment_method = $faker->randomElement(['Credit', 'Cash', 'Check', 'COD']);
            $customer->assigned_to    = User::all()->random(2)[0]->id;
            $customer->save();
        }
    }
}
