<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index()
    {
        return response()->json(Booking::all());
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'start' => 'required|date',
            'end' => 'required|date|after_or_equal:start',
        ]);

        $booking = Booking::create([
            'item_id' => 1, // Assuming a default item_id or this should be passed in the request
            'user_id' => 1, // Assuming a default user_id or this should be passed in the request
            'start_date' => $validatedData['start'],
            'end_date' => $validatedData['end'],
        ]);

        return response()->json($booking, 201);
    }

    public function show($id)
    {
        $booking = Booking::findOrFail($id);
        return response()->json($booking);
    }

    public function update(Request $request, $id)
    {
        $booking = Booking::findOrFail($id);

        $validatedData = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'start' => 'sometimes|required|date',
            'end' => 'sometimes|required|date|after_or_equal:start',
        ]);

        $booking->update([
            'item_id' => 1, // Assuming a default item_id or this should be passed in the request
            'user_id' => 1, // Assuming a default user_id or this should be passed in the request
            'start_date' => $validatedData['start'],
            'end_date' => $validatedData['end'],
        ]);

        return response()->json($booking, 200);
    }

    public function destroy($id)
    {
        $booking = Booking::findOrFail($id);
        $booking->delete();

        return response()->json(null, 204);
    }
}
