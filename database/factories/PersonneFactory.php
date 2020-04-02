<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Model;
use App\Model\Personne;
use Faker\Generator as Faker;

$factory->define(Personne::class, function (Faker $faker) {
    $createAt = $faker->dateTimeInInterval(
        $startDate = '-6 months',
        $interval = '+ 180 days',
        $timezone = date_default_timezone_get()
    );
    return [
        'nom' => $faker->lastName(),
        'prenom' => $faker->firstName(),
        'pseudo' => $faker->firstName(),
        'avatar' => $faker->imageUrl(150,150),
        'actif' => $faker->randomElement([0,1]),
        /*'score' => $faker->numberBetween($min = 0, $max = 1000000000),
        'high_score' => $faker->numberBetween($min = 0, $max = 5000),
        'tirs' => $faker->numberBetween($min = 0, $max = 10000),
        'ennemis_tues' => $faker->numberBetween($min = 0, $max = 1000),
        'morts' => $faker->numberBetween($min = 0, $max = 500),
        'bonus' => $faker->numberBetween($min = 0, $max = 100),
        'malus' => $faker->numberBetween($min = 0, $max = 100),*/
        'user_id' => $faker->randomDigit,
        'statistique_id' => $faker->randomDigit,
        'created_at' => $createAt,
        'updated_at' => $faker->dateTimeInInterval(
            $startDate = $createAt,
            $interval = $createAt->diff(new DateTime('now'))->format("%R%a days"),
            $timezone = date_default_timezone_get()
        ),
    ];
});
