<?php

// app/Http/Controllers/BookingController.php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Booking;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;

class BookingController extends Controller
{
    public function index()
    {
        try {
            $bookings = Booking::with('item', 'user')->get();
            return response()->json($bookings);
        } catch (\Exception $e) {
            Log::error('Error fetching bookings: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to fetch bookings'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'user_id' => 'required|integer|exists:users,id',
                'item_id' => 'required|integer|exists:items,id',
                'description' => 'nullable|string',
                'start_date' => 'required|date',
                'end_date' => 'required|date'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $booking = Booking::create($validator->validated());

            return response()->json($booking, 201);
        } catch (\Exception $e) {
            Log::error('Error creating booking: ' . $e->getMessage());
            Log::error('Request data: ' . json_encode($request->all()));
            return response()->json(['error' => 'Failed to create booking', 'message' => $e->getMessage()], 500);
        }
    }
}

