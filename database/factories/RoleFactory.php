<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model\Role;
use Faker\Generator as Faker;

$factory->define(Role::class, function (Faker $faker) {
    return [
        'role' => $faker->randomElement(['admin', 'joueur', 'auteur'])
    ];
});
