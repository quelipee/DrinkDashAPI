<?php

use App\ProductDomain\ProductController\ProductController;
use App\ProductDomain\ProductService\ProductService;
use App\UserDomain\UserController\UserController;
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


Route::middleware(['guest'])->group(function ()
{
    Route::post('register',[UserController::class,'store'])->name('register');
    Route::post('login',[UserController::class,'login'])->name('login');
});

Route::middleware(['auth'])->group(function ()
{
    Route::post('order_product/{id}',[UserController::class,'order_product'])->name('order_product');
    Route::post('deposit',[UserController::class,'deposit'])->name('deposit');
    Route::post('buy_product/{id}',[UserController::class,'buy_product'])->name('buy_product');

    Route::post('logout', [UserController::class,'logout'])->name('logout');
});


