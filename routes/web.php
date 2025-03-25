<?php

use App\Http\Controllers\PostController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PlatformController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::resource('posts', PostController::class)->middleware(['auth:sanctum', 'verified']);
Route::group(['prefix' => 'platforms'], function () {
    Route::get('/', [PlatformController::class, 'index'])->name('platforms.index');
    Route::put('/{id}', [PlatformController::class, 'toggleActive'])->name('platforms.toggle-active');
})->middleware(['auth:sanctum', 'verified']);
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});




require __DIR__ . '/test.php';
require __DIR__ . '/auth.php';
