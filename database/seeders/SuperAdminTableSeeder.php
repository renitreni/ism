<?php
namespace Database\Seeders;

use Illuminate\Support\Str;
use Silber\Bouncer\Bouncer;
use Illuminate\Database\Seeder;

class SuperAdminTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\User::query()->where('email', 'admin@management.com')->delete();
        $id = \App\User::query()->insertGetId(
            [
                'name'              => 'Super Admin',
                'email'             => 'admin@management.com',
                'password'          => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
                'remember_token'    => Str::random(10),
            ]
        );
        app(Bouncer::class)->allow(\App\User::find($id))->everything();
    }
}
