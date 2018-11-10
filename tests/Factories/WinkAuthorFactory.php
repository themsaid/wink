<?php

use Wink\WinkAuthor;

$factory->define(WinkAuthor::class, function (Faker\Generator $faker) {
    return [
        'id'       => $faker->uuid,
        'slug'     => $faker->unique()->slug(),
        'name'     => $faker->title,
        'email'    => $faker->unique()->safeEmail(),
        'password' => $faker->password(),
        'bio'      => $faker->sentence,
        'avatar'   => null,
    ];
});