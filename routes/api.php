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
        
        //Voir les sièges réservés en temps réel
        Route::get('/sessions/{session}/seats', [SeatController::class, 'getSeatsBySession']);
        
        //Réserver un siège
        Route::post('/reservations', [ReservationController::class, 'store']);

        //Annuler une réservation
        Route::patch('reservations/{id}/cancel', [ReservationController::class, 'cancel']);

        //Modifier une réservation
        Route::put('/reservations/{id}', [ReservationController::class, 'update']);
    });
});

