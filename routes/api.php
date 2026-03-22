<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;

Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
}) ;

Route::apiResource('films', FilmController::class);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::get('/user', [AuthController::class, 'show']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->group(function () {
        Route::get('/sessions/filter', [SessionController::class, 'filter']);
        Route::apiResource('sessions', SessionController::class);
        
        //create the Room
        Route::post('/rooms', [RoomController::class, 'store']);

        //Lister les salles
        Route::get('/rooms', [RoomController::class, 'index']);

        //Voir une salle
        Route::get('/rooms/{id}', [RoomController::class, 'show']);

        //Modifier une salle
        Route::put('/rooms/{id}', [RoomController::class, 'update']);

        //Supprimer une salle
        Route::delete('/rooms/{id}', [RoomController::class, 'destroy']);

        //Voir les sièges réservés en temps réel
        Route::get('/sessions/{session}/seats', [SeatController::class, 'getSeatsBySession']);
        
        //Réserver un siège
        Route::post('/reservations', [ReservationController::class, 'store']);

        //voir les réservations
        Route::get('reservations', [ReservationController::class, 'index']);

        //Annuler une réservation
        Route::patch('reservations/{id}/cancel', [ReservationController::class, 'cancel']);

        //Modifier une réservation
        Route::put('/reservations/{id}', [ReservationController::class, 'update']);


    });
});

