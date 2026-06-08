<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', [RecipeController::class, 'explore'])->name('explore');

    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.mine');
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    // Favorites and Resep Saya
    Route::get('/resep-saya', [ProfileController::class, 'resepSaya'])->name('resep-saya');
    Route::get('/favorites', [ProfileController::class, 'favorites'])->name('favorites');
    
    // Upload Resep (Ucup)
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');

    // Public route but placed after /recipes/create
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');


    // Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admin/recipes/{id}', [AdminController::class, 'destroyRecipe'])->name('admin.recipes.destroy');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});
});

require __DIR__.'/auth.php';
