<?php

use App\Models\Booking;

Route::get('/bookings', function () {
    return Booking::all();
});
