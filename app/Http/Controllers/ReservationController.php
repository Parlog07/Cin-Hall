<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Http\Requests\UpdateReservationRequest;
use App\Models\Reservation;
use App\Models\Seat;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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

        // 1.récupérer tous les sièges sélectionnés
        $seatIds = $request->seat_ids;

        // récupérer les sièges du DB
        $seats = Seat::whereIn('id', $seatIds)->get();

        // 2.ajouter automatiquement les partenaires des sièges couple
        foreach ($seats as $seat) {
            if ($seat->type === 'couple' && $seat->seat_id) {
                if (!in_array($seat->seat_id, $seatIds)) {
                    $seatIds[] = $seat->seat_id;
                }
            }
        }
        
        // 3.Vérifier si sièges deja réservés
        $alreadyReserved = Seat::whereIn('id', $seatIds)
            ->whereHas('reservations', function ($query) use ($request) {
                $query->where('room_session_id', $request->room_session_id)
                ->whereIn('status', ['pending', 'paid']);
            })
            ->exists();

        if ($alreadyReserved) {
            return response()->json([
                'message' => 'One or more seats already reserved'
            ], 400);
        }

        // 4.Créer une réservation
        $reservation = Reservation::create([
            'room_session_id' => $request->room_session_id,
            'user_id' => Auth::user()->id,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addMinutes(15),
            'total_price' => $request->total_price
        ]);

        // 5.lier les sièges
        $reservation->seats()->attach($seatIds);

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
    public function update(UpdateReservationRequest $request, $id)
    {
        $reservation = Reservation::findOrFail($id);

        if (in_array($reservation->status, ['paid', 'expired'])) {
            return response()->json([
                'message' => 'Cannot modify this reservation'
            ], 403);
        }

        //modifier les sieges
        if ($request->has('seat_ids')) {
            $reservation->seats()->sync($request->seat_ids);
        }

        //modifier prix
        if ($request->has('total_price')) {
            $reservation->update([
                'total_price' => $request->total_price
            ]);
        }

        return response()->json([
            'message' => 'Reservation update'
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Reservation $reservation)
    {
        //
    }

    //Annuler une réservation
    public function cancel($id)
    {
        $reservation = Reservation::findOrFail($id);

        if ($reservation->status === 'paid') {
            return response()->json([
                'message' => 'Cannot cancel a paid reservation'
            ]);
        }

        $reservation->update([
            'status' => 'cancelled'
        ]);

        return response()->json([
            'message' => 'Reservation cancelled'
        ]);
    }
}
