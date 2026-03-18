<?php

use Illuminate\Http\Request;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use Illuminate\Support\Facades\Route;




Route::get('/', function () {
    return response()->json(['message' => 'Hello world!']);
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('jwt')->group(function () {
    Route::get('/user', [AuthController::class, 'getUser']);
    Route::put('/user', [AuthController::class, 'updateUser']);
    Route::post('/logout', [AuthController::class, 'logout']);
});


//create the Room
Route::post('/rooms', [RoomController::class, 'store']);

//Voir les sièges réservés en temps réel
Route::get('/sessions/{session}/seats', [SeatController::class, 'getSeatsBySession']);

//Réserver un siège
Route::post('/reservations', [ReservationController::class, 'store']);