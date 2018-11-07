<?php

use Wink\WinkPost;

$factory->define(WinkPost::class, function (Faker\Generator $faker) {
    return [
        'id'                     => $faker->uuid,
        'title'                  => $faker->sentence,
        'slug'                   => $faker->unique()->slug(),
        'body'                   => $faker->text,
        'excerpt'                => $faker->text,
        'featured_image_caption' => $faker->text,
        'author_id'              => 1,
    ];
});