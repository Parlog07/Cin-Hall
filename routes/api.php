<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FilmController;

Route::get('/', function (Request $request) {
    return response()->json('hellow world');
});

Route::apiResource('films', FilmController::class);
