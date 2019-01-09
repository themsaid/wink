<?php

use Faker\Generator;
use Wink\WinkAuthor;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(WinkAuthor::class, function (Generator $faker) {
    return [
        'id' => $faker->uuid,
        'slug' => $faker->unique()->slug(),
        'name' => $faker->title,
        'email' => $faker->unique()->safeEmail(),
        'password' => $faker->password(),
        'bio' => $faker->sentence,
        'avatar' => null,
    ];
});
