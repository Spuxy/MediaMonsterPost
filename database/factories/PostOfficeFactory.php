<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\PostOffice;
use Faker\Generator as Faker;

$factory->define(PostOffice::class, function (Faker $faker) {
    return [
        'PSC' => $faker->numberBetween(4,6),
	    'Name' => $faker->domainName,
	    'Address' => $faker->streetAddress,
	    'X' => $faker->numberBetween(5,8),
	    'Y' => $faker->numberBetween(5,8),
	    'City' => $faker->city,
	    'C_City' => $faker->city.$faker->word,
    ];
});
