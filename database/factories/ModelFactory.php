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

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(App\User::class, function (Faker\Generator $faker) {
    static $password;

    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => $password ?: $password = bcrypt('secret'),
        'remember_token' => str_random(10),
        'verified' => $verificado = $faker->randomElement([\App\User::USUARIO_NO_VERIFICADO, \App\User::USUARIO_VERIFICADO]),
        'verification_token' => $verificado == \App\User::USUARIO_VERIFICADO ? null : \App\User::generarVerificationToken() ,
        'admin' => $faker->randomElement([\App\User::USUARIO_REGULAR, \App\User::USUARIO_ADMINISTRADOR]),
    ];
});

$factory->define(App\Category::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {

    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10) ,
        'status' => $faker->randomElement([\App\Product::PRODUCTO_NO_DISPONIBLE, \App\Product::PRODUCTO_DISPONIBLE]) ,
        'image' => $faker->randomElement(['1.jpg', '2.jpg', '3.jpg']) ,
        // 'seller_id' => User::inRandomOrder()->first()->id ,
        'seller_id' => \App\User::all()->random()->id ,
    ];
});

$factory->define(App\Transaction::class, function (Faker\Generator $faker) {

    $vendedor = \App\Seller::has('products')->get()->random();
    $comprador = \App\User::all()->except($vendedor->id)->random();
    return [
        'quantity' => $faker->numberBetween(1, 3) ,
        'buyer_id' => $comprador->id ,
        'product_id' => $vendedor->products->random()->id ,
        
    ];
});
