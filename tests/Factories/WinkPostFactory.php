<?php

use Wink\WinkPost;
use Faker\Generator;
use Wink\WinkAuthor;

/* @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(WinkPost::class, function (Generator $faker) {
    return [
        'id' => $faker->uuid,
        'slug' => $faker->unique()->slug(),
        'title' => $faker->sentence,
        'excerpt' => $faker->text,
        'body' => $faker->text,
        'published' => false,
        'featured_image_caption' => $faker->text,
        'author_id' => function () {
            return factory(WinkAuthor::class)->create()->id;
        },
    ];
});
