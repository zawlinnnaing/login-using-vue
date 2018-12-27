<?php

use App\User;
use Faker\Generator as Faker;

$factory->define(App\Post::class, function (Faker $faker) {
    return [
        //
        'title'   => $faker->sentence,
        'body'    => $faker->paragraph,
        'user_id' => function () {
            return User::where('email', 'zawlinnnaing0018@gmail.com')->first()->id;
        }
    ];
});
