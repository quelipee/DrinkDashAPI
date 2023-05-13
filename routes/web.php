<?php

use App\ProductDomain\ProductController\ProductController;
use App\UserAdmDomain\AdmController\AdmController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('guest')->group(function (){
    Route::get('/', function () {
        return view('welcome');
    })->name('home_page');
})->name('guest_get');

Route::middleware(['guest'])->group(function (){
    Route::post('store_adm',[AdmController::class,'store'])->name('store_adm');
    Route::post('login_adm',[AdmController::class,'login'])->name('login_adm');
})->name('guest_post');

Route::middleware(['auth'])->group(function (){
    Route::get('index',[AdmController::class,'index'])->name('index');
    Route::get('users',[AdmController::class,'users'])->name('users');
    Route::get('add_product',[AdmController::class,'add_product'])->name('add_product');
    Route::get('edit_product/{id}',[AdmController::class,'edit_product'])->name('edit_product');
})->name('auth_get');

Route::middleware('auth')->group(function (){
    Route::post('logout',[AdmController::class,'logout'])->name('logout');
    Route::post('add_product',[ProductController::class,'insert_products'])->name('add_product');
    Route::put('edit_product_bd/{id}',[ProductController::class,'edit_product_bd'])->name('edit_product_bd');
})->name('auth_post');
