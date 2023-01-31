<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\PreferenceTableSeeder;
use Database\Seeders\SuperAdminTableSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            SuperAdminTableSeeder::class,
            PreferenceTableSeeder::class
        ]);
    }
}
