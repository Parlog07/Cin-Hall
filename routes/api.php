<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;
Route::get('/', function (Request $request) {
    return response()->json('hellow world');
});

Route::apiResource('films', FilmController::class);
Route::get('films', [FilmController::class, 'index']);
Route::get('films/{film}', [FilmController::class, 'show']);
Route::post('films', [FilmController::class, 'store']);
Route::put('films/{film}', [FilmController::class, 'update']);
Route::delete('films/{film}', [FilmController::class, 'destroy']);