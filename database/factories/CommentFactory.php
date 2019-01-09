<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        //
        'description' => $faker->paragraph,
        'user_id'     => rand(1, 2),
        'post_id'     => rand(43, 82)
    ];
});