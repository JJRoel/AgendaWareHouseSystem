<?php

use App\Http\Controllers\AdministrationController;

Route::get('/items', [AdministrationController::class, 'index'])->name('administration.items.index');
Route::patch('/items/updateName/{id}', [AdministrationController::class, 'updateName'])->name('administration.items.updateName');
Route::patch('/items/updateStatus/{id}', [AdministrationController::class, 'updateStatus'])->name('administration.items.updateStatus');

// Add this new route for the calendar view
Route::get('/calendar', function () {
    return view('administration.calendar');
});


