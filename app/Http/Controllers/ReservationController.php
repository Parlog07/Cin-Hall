<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $reservations = Reservation::all() ;
        return response()->json(['data' => $reservations] , 200) ;
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreReservationRequest $request)
    {
        $reservation = Reservation::create([
            'room_session_id' => $request->room_session_id,
            'user_id' => Auth::user()->id,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(15),
            'total_price' => $request->total_price
        ]);

        // lier les sièges
        $reservation->seats()->attach($request->seat_ids);

        return response()->json([
            'message' => 'Reservation created',
            'data' => $reservation
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Reservation $reservation)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Reservation $reservation)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReservationRequest $request, Reservation $reservation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    public function updateAfterPayment(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            return response()->json([
                'message' => 'You are not allowed to update this reservation.',
            ], 403);
        }

        $payment = $reservation->payment()->latest('id')->first();

        if (!$payment instanceof Payment) {
            return response()->json([
                'message' => 'No payment found for this reservation.',
            ], 404);
        }

        $reservation->syncStatusFromPayment($payment);

        return response()->json([
            'message' => 'Reservation status updated successfully.',
            'reservation' => $reservation->load('payment'),
        ], 200);
    }
}
