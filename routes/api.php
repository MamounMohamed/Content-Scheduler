<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return auth()->guard('sanctum')->user();
})->middleware('auth:sanctum');

