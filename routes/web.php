<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Favorites and Resep Saya
    Route::get('/resep-saya', [ProfileController::class, 'resepSaya'])->name('resep-saya');
    Route::get('/favorites', [ProfileController::class, 'favorites'])->name('favorites');
});
