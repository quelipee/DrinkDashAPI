<?php

use App\ProductDomain\ProductController\ProductController;
use App\UserDomain\UserController\UserController;
use App\UserDomain\UserController\UserGetController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('insert',[ProductController::class,'insert_bd_products'])->name('insert_products');//insert produtos

Route::get('get_all_products',[ProductController::class,'get_all_products'])->name('get_all_products');//listando todos os produtos

Route::get('product/{id}',[ProductController::class,'getProduct'])->name('get_product');

Route::middleware(['guest:sanctum'])->group(function ()
{
    Route::post('register',[UserController::class,'store'])->name('register');
    Route::post('login',[UserController::class,'login'])->name('login');
});

Route::middleware(['auth:sanctum'])->group(function ()
{
    Route::post('order_product/{id}',[UserController::class,'order_product'])->name('order_product');
    Route::post('order_product_cancel/{id}',[UserController::class,'order_product_cancel'])->name('order_product_cancel');
    Route::post('editProfile',[UserController::class,'editProfile'])->name('editProfile');

    Route::post('deposit',[UserController::class,'deposit'])->name('deposit');
    Route::post('buy_product/{id}',[UserController::class,'buy_product'])->name('buy_product');

    Route::get('logout', [UserController::class,'logout'])->name('logout');
});

Route::middleware(['auth:sanctum'])->group(function ()
{
    Route::get('get-user',[UserGetController::class,'get_user'])->name('get_user');
    Route::get('get-list-order',[UserGetController::class,'get_list_order'])->name('get_list_order');
})->name('get_frontend');

