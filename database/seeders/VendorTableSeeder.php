<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\User;
use App\Vendor;

class VendorTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Vendor::truncate();
        for($x=0; $x<1000; $x++) {
            $faker = Factory::create();
            $vendor = new Vendor();
            $vendor->name = $faker->firstName;
            $vendor->contact_person = $faker->firstName();
            $vendor->landline = $faker->phoneNumber;
            $vendor->mobile_phone = $faker->phoneNumber;
            $vendor->email = $faker->safeEmail;
            $vendor->payment_method = $faker->randomElement(['Credit','Cash','Check','COD']);
            $vendor->shipping_method = $faker->word;
            $vendor->address = $faker->address;
            $vendor->assigned_to  = User::all()->random(2)[0]->id;
            $vendor->save();
        }
    }
}
