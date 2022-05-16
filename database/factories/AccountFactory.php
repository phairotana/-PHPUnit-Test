<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Models\Account;
use Faker\Generator as Faker;

$factory->define(Account::class, function (Faker $faker) {
    return [
        'name'  => $faker->name,
        'phone' => $faker->phoneNumber,
        'email' => \Str::random(5).'@email.com',
        'address' => 'Phnom Penh',
        'number' => rand(100000000, 999999999)
    ];
});
