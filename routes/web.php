<?php

use Illuminate\Support\Facades\Route;
use App\Http\Middleware\checkForPrice;
use App\Http\Controllers\Traveling\TravelingController;
Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::get('traveling/about/{id}', [App\Http\Controllers\Traveling\TravelingController::class, 'about'])->name('traveling.about');

//Booking
Route::get('traveling/reservation/{id}', [TravelingController::class, 'makeReservation'])->name('traveling.reservation');
Route::post('traveling/reservation/{id}', [TravelingController::class, 'storeReservation'])->name('traveling.reservation.store');


Route::get('traveling/pay', [TravelingController::class, 'payWithPaypal'])
    ->middleware(checkForPrice::class)  // Directly apply the middleware
    ->name('traveling.pay');

Route::get('traveling/success', [TravelingController::class, 'success'])
    ->middleware(checkForPrice::class)  // Directly apply the middleware
    ->name('traveling.success');