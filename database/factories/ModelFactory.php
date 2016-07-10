<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

// define user factory
$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
        'bio' => $faker->sentence($nbWords = 6, $variableNbWords = true),
        'self_intro' => $faker->text(),
        'url_name' => str_random(10)
    ];
});

// define topic factory
$factory->define(App\Topic::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->word(),
        'description' => $faker->text(),
    ];
});


// define question factory
$factory->define(App\Question::class, function (Faker\Generator $faker) {
    return [
        'title' => $faker->sentence($nbWords = 9, $variableNbWords = true),
        'content' => nl2br($faker->text()),
    ];
});

// define answer factory
$factory->define(App\Answer::class, function (Faker\Generator $faker) {
    return [
        'answer' => nl2br($faker->text()),
    ];
});

// define reply factory
$factory->define(App\Reply::class, function (Faker\Generator $faker) {
    return [
        'reply' => nl2br($faker->text()),
    ];
});

// define message factory
$factory->define(App\Message::class, function (Faker\Generator $faker) {
    return [
        'content' => nl2br($faker->text()),
    ];
});
