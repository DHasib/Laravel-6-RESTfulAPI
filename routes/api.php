<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Buyer ....
 */
 Route::resource('buyer','Buyer\BuyerController', ['only' => ['index', 'show']]);

 /**
 * Category ....
 */
Route::resource('category','Category\CategoryController', ['except' => ['create', 'edit']]);

/**
 * Products ....
 */
Route::resource('product','Product\ProductController', ['only' => ['index', 'show']]);
/**
 * Sellers ....
 */
Route::resource('seller','Seller\SellerController', ['only' => ['index', 'show']]);

/**
 * Transactions ....
 */
Route::resource('transaction','Transaction\TransactionController', ['only' => ['index', 'show']]);

/**
 * Users ....
 */
Route::resource('users','User\UserController', ['except' => ['create', 'edit']]);