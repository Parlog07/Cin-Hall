<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Models\Room;
use App\Models\Seat;

class RoomController extends Controller
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
    

    public function store(StoreRoomRequest $request)
    {
        // 1. Créer la salle
        $room = Room::create($request->validated());

        $coupleSeats = $request->couple_seats ?? [];
        $seats = []; // stocker les objets (pas seulement les ids)

        // 2. Créer les sièges
        for ($i = 1; $i <= $room->capacity; $i++) {

            if ($room->type === 'VIP') {
                $seatType = in_array($i, $coupleSeats) ? 'couple' : 'VIP';
            } else {
                $seatType = 'normal';
            }

            $seat = $room->seats()->create([
                'number' => $i,
                'type' => $seatType
            ]);

            $seats[$i] = $seat; // stocker objet complet
        }

        // 3. Lier les sièges couples
        if ($room->type === 'VIP' && !empty($request->seat_adjacent)) {

            foreach ($request->seat_adjacent as $seatNumber => $adjacentNumber) {

                // vérifier que les deux sièges existent
                if (isset($seats[$seatNumber]) && isset($seats[$adjacentNumber]) && $seats[$seatNumber]->type === 'couple') {

                    $seats[$seatNumber]->seat_id = $seats[$adjacentNumber]->id;
                    $seats[$seatNumber]->save();
                }
            }
        }

        return response()->json([
            'message' => 'Room created successfully',
            'data' => $room
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Room $room)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Room $room)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRoomRequest $request, Room $room)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Room $room)
    {
        //
    }
}
