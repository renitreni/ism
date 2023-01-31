<?php

use Illuminate\Database\Seeder;
use Illuminate\Database\Eloquent\Model;
use App\User;
use Faker\Factory;
use Illuminate\Support\Str;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Model::unguard();
        for ($x = 0; $x <= 500; $x++) {
            $faker = Factory::create();
            $email = $faker->unique()->safeEmail;
            User::query()->insert(
                [
                    'name'              => $faker->name,
                    'email'             => $email,
                    'email_verified_at' => '',
                    'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                    'remember_token'    => Str::random(10),
                ]
            );
            print $email . "\n";
        }
    }
}
