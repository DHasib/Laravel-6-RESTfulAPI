<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */
use App\User;
use App\Buyer;
use App\Seller;
use App\Product;
use App\Category;
use App\Tramsaction;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/


//User factory..........................................................
$factory->define(User::class, function (Faker $faker) {
     static $password;
     $str_random = Str::random(10);
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'email_verified_at' => now(),
        'password' => $password ?: $password = bcrypt('123'), // password
        'remember_token' =>  $str_random ,
        'verified' => $verified = $faker->randomElement([User::VERIFIED_USER, USER::UNVERIFIED_USER]),
        'verification_token' => $verified == User::VERIFIED_USER ? null : USER::generateVerificationCode(),
        'admin' => $verified = $faker->randomElement([User::ADMIN_USER, USER::REGULAR_USER]),
    ];
});

//Category factory..........................................................
$factory->define(Category::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
    ];
});

//Product  factory..........................................................
$factory->define(Product::class, function (Faker $faker) {
    return [
        'name' => $faker->word,
        'description' => $faker->paragraph(1),
        'quantity' => $faker->numberBetween(1, 10),
        'status' => $faker->randomElement([Product::AVAILABLE_PRODUCT, Product::UNAVAILABLE_PRODUCT]),
        'image' => $faker->randomElement(['test-1.PNG','test-2.PNG','test-3.PNG']),
        'seller_id' => User::all()->random()->id, 
    ];
});

//Transaction  factory..........................................................
$factory->define(Tramsaction::class, function (Faker $faker) {

    $seller = Seller::has('products')->get()->random();
    $buyer = User::all()->except($seller->id)->random();

    return [
        'quantity' => $faker->numberBetween(1, 3),
        'buyer_id' => $buyer->id, 
        'product_id' => $seller->products->random()->id, 
    ];
});