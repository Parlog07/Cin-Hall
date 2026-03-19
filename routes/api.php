<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\SeatController;
use App\Http\Controllers\SessionController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return response()->json('hellow world');
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::get('films', [FilmController::class, 'index']);
Route::get('films/{film}', [FilmController::class, 'show']);

Route::middleware('jwt')->group(function () {

    Route::get('/dashboard', [DashboardController::class, 'show'])->middleware('admin');
    Route::get('/user', [AuthController::class, 'show']);
    Route::put('/user', [AuthController::class, 'update']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::middleware('admin')->group(function () {
        Route::post('films', [FilmController::class, 'store']);
        Route::put('films/{film}', [FilmController::class, 'update']);
        Route::delete('films/{film}', [FilmController::class, 'destroy']);

        Route::get('/sessions/filter', [SessionController::class, 'filter']);
        Route::apiResource('sessions', SessionController::class);
    });
});


//create the Room
Route::post('/rooms', [RoomController::class, 'store']);

//Voir les sièges réservés en temps réel
Route::get('/sessions/{session}/seats', [SeatController::class, 'getSeatsBySession']);

//Réserver un siège
Route::post('/reservations', [ReservationController::class, 'store']);
