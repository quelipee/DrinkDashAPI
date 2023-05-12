<?php

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
})->name('auth_get');

Route::middleware('auth')->group(function (){
    Route::post('logout',[AdmController::class,'logout'])->name('logout');
})->name('auth_post');
