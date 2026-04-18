<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\PurchaseController; 
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LikeController;
use App\Http\Controllers\MypageController;
use App\Http\Controllers\Auth\RegisterController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/email/verify',function(){
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/', [ItemController::class, 'index'])->name('items.index');
Route::get('/item/{item_id}',[ItemController::class,'show'])->name('items.show');
Route::get('/register', function () {
    return view('auth.register');
})->name('register');

Route::middleware(['auth','verified'])->group(function () {
     Route::get('/mypage/profile', [ProfileController::class, 'edit'])->name('profiles.edit');
});

Route::middleware(['auth','verified','profile.completed'])->group(function () {
    Route::get('/mypage', [MypageController::class, 'index'])->name('mypages.index');
    Route::post('/mypage/profile',[ProfileController::class,'update'])->name('profiles.update');

    Route::get('/sell', [ItemController::class, 'create'])->name('items.create');
    Route::post('/sell',[ItemController::class,'store'])->name('items.store');
    
    Route::post('/items/{item}/like', [LikeController::class, 'store'])
        ->name('items.like');
    Route::delete('/items/{item}/like', [LikeController::class, 'destroy'])
        ->name('items.unlike');
    Route::post('/item/{item_id}/comment',[ItemController::class,'commentStore'])->name('items.comment');

    Route::get('/purchase/address/{item_id}',[PurchaseController::class,'edit'])->name('purchases.address.edit');
    Route::put('/purchase/address/{item_id}',[PurchaseController::class,'update'])->name('purchases.address.update');

    Route::get('/purchase/{item_id}',[PurchaseController::class,'buy'])->name('items.buy');
    Route::get('/purchase/{item_id}/calculate',[PurchaseController::class,'calculate'])->name('purchases.calculate');
    Route::post('/purchase/{item_id}',[PurchaseController::class,'purchase'])->name('items.purchase');
    Route::get('/purchase/{item_id}/success',[PurchaseController::class,'success'])->name('purchase.success');
});

Route::post('/register', [RegisterController::class, 'store'])
    ->middleware(['guest'])
    ->name('register');

Route::post('/login', [LoginController::class, 'store'])
    ->middleware(['guest'])
    ->name('login');