<?php

use App\Http\Controllers\AdministrationController;
use Illuminate\Support\Facades\Route;

Route::get('/items', [AdministrationController::class, 'index'])->name('administration.items.index');
Route::patch('/items/updateName/{id}', [AdministrationController::class, 'updateName'])->name('administration.items.updateName');
Route::patch('/items/updateStatus/{id}', [AdministrationController::class, 'updateStatus'])->name('administration.items.updateStatus');

Route::get('/calendar', function () {
    return view('administration.calendar');
});

// routes/web.php
use App\Http\Controllers\BookingController;
use App\Http\Controllers\GroupController;

Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/group', [GroupController::class, 'getGroups']);


Route::get('/test-calendar', function () {
    return view('test');
});
