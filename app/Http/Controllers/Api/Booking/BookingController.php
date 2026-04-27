<?php

namespace App\Http\Controllers\Api\Booking;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookingRequest;
use App\Http\Resources\BookingResource;
use App\Models\Booking;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth as FacadesAuth;
use Illuminate\Support\Facades\DB;

class BookingController extends Controller
{
    //
    public function index()
    {

        // Code to list all bookings
        $userId = FacadesAuth::user()->id;
        $bookings = Booking::with('event')->where('user_id', $userId)->get();
        return response()->json([
            'success' => true,
            'data' =>  BookingResource::collection($bookings)
        ]);
    }
    public function allWData()
    {
        // Code to list all bookings
        $bookings = Booking::with('event.category', 'user')->get();
        return response()->json([
            'success' => true,
            'data' =>  BookingResource::collection($bookings)
        ]);
    }
    public function show($id)
    {
        $user = FacadesAuth::user()->id;

        $booking = Booking::with('event')->where('user_id', $user)->where('id', $id)->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking)
        ]);
    }
    public function showWithData($id)
    {

        $booking = Booking::with('event.category', 'user')->where('id', $id)->first();
        // dd($booking);
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
        return response()->json([
            'success' => true,
            'data' => new BookingResource($booking)
        ]);
    }
    public function destroy($id)
    {
        $userId = FacadesAuth::user()->id;

        $booking = Booking::where('user_id', $userId)->where('id', $id)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
        $booking->delete();
        return response()->json([
            'success' => true,
            'message' => 'Booking deleted successfully'
        ]);
    }

    public function create(BookingRequest $request, $eventId)
    {
        // $userId = FacadesAuth::user()->id;
        $userId = FacadesAuth::user()->id;
        if (!$userId) {
            return response()->json([
                'success' => false,

                'message' => 'User not authenticated ',
            ], 401);
        }
        return DB::transaction(function () use ($eventId, $userId, $request) {

            $event = Event::lockForUpdate()->find($eventId);

            if (!$event) {
                return response()->json([
                    'success' => false,
                    'message' => 'Event not found'
                ], 404);
            }

            if ($event->available_seats <= 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'No available seats'
                ], 400);
            }
            $bookingExists = Booking::where('user_id', $userId)->where('event_id', $eventId)->exists();
            if ($bookingExists) {
                return response()->json([
                    'success' => false,
                    'message' => 'Booking already exists'
                ], 400);
            }
            $event->decrement('available_seats');

            $booking = Booking::create([
                'user_id' => $userId,
                'event_id' => $eventId,
                'status' => $request->status ?? 'pending',
            ]);

            $booking->load(['user', 'event.category']);

            return response()->json([
                'success' => true,
                'data' => new BookingResource($booking)
            ], 201);
        });
    }
    public function update(BookingRequest $request, $id)
    {
        $userId = FacadesAuth::user()->id;

        $booking = Booking::where('user_id', $userId)->where('id', $id)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'message' => 'Booking not found'
            ], 404);
        }
        $booking->update([
            'status' => $request->status ?? $booking->status,
        ]);
        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => new BookingResource($booking)
        ]);
    }
}
