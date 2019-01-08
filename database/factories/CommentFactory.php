<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Comment::class, function (Faker $faker) {
    return [
        //
        'description' => $faker->paragraph,
        'user_id'     => function () {
            return User::where('email', 'zawlinnnaing0018@gmail.com')->first()->id;
        },
        'post_id'     => rand(1, 20)
    ];
});