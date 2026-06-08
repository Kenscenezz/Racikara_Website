<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RecipeController;
use App\Http\Controllers\AdminController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/explore', [RecipeController::class, 'explore'])->name('explore');


Route::middleware('auth')->group(function () {
    Route::get('/favorites', [RecipeController::class, 'favorites'])->name('favorites');
    Route::post('/recipes/{id}/favorite', [RecipeController::class, 'toggleFavorite'])->name('recipes.favorite.toggle');
    
    Route::get('/my-recipes', [RecipeController::class, 'myRecipes'])->name('recipes.mine');
    Route::get('/recipes/create', [RecipeController::class, 'create'])->name('recipes.create');
    Route::post('/recipes', [RecipeController::class, 'store'])->name('recipes.store');
    Route::get('/recipes/{id}/edit', [RecipeController::class, 'edit'])->name('recipes.edit');
    Route::put('/recipes/{id}', [RecipeController::class, 'update'])->name('recipes.update');
    Route::delete('/recipes/{id}', [RecipeController::class, 'destroy'])->name('recipes.destroy');
    
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    
    // Breeze default profile routes
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Public route but placed after /recipes/create
Route::get('/recipes/{id}', [RecipeController::class, 'show'])->name('recipes.show');

// Admin Routes
Route::middleware(['auth'])->group(function () {
    Route::get('/admin', [AdminController::class, 'index'])->name('admin.index');
    Route::delete('/admin/recipes/{id}', [AdminController::class, 'destroyRecipe'])->name('admin.recipes.destroy');
    Route::delete('/admin/users/{id}', [AdminController::class, 'destroyUser'])->name('admin.users.destroy');
});

require __DIR__.'/auth.php';
