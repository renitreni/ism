<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Expenses;
use Faker\Generator as Faker;

$factory->define(Expenses::class, function (Faker $faker) {
    return [
        'expenses_no' => $faker->numberBetween(1,10000),
        'cost_center' => $faker->word(),
        'description' => $faker->paragraph(),
        'person_assigned' => $faker->name(),
        'total_amount' => $faker->numberBetween(1,10000),
        'expense_date' => $faker->date(),
        'si_no' => $faker->numberBetween(1,10000),
        'dr_no' => $faker->numberBetween(1,10000),
        'remarks' => $faker->paragraph(),
        'created_by' => null
    ];
});
