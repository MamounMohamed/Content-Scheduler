<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::group(['prefix'=>'posts'], function(){
    Route::post('/', [\App\Http\Controllers\PostController::class, 'store']);
    Route::get('{id}', [\App\Http\Controllers\PostController::class, 'show']);
    Route::put('/{id}', [\App\Http\Controllers\PostController::class, 'update']);
    Route::delete('/{id}', [\App\Http\Controllers\PostController::class, 'destroy']);

});
