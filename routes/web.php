<?php

use App\Http\Controllers\AdministrationController;
use App\Http\Controllers\BookingController;
use Illuminate\Support\Facades\Route;

Route::get('/items', [AdministrationController::class, 'index'])->name('administration.items.index');
Route::patch('/items/updateName/{id}', [AdministrationController::class, 'updateName'])->name('administration.items.updateName');
Route::patch('/items/updateStatus/{id}', [AdministrationController::class, 'updateStatus'])->name('administration.items.updateStatus');

Route::get('/calendar', function () {
    return view('administration.calendar');
});

Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings/{id}', [BookingController::class, 'show']);
Route::put('/bookings/{id}', [BookingController::class, 'update']);
Route::delete('/bookings/{id}', [BookingController::class, 'destroy']);

