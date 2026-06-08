<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Count recipes and favorites
        $recipeCount = $user->recipes()->count();
        $favoriteCount = $user->savedRecipes()->count();
        
        // Get user's recipes for the profile tab
        $recipes = $user->recipes()->with('category')->orderBy('created_at', 'desc')->get();

        return view('profile', compact('user', 'recipeCount', 'favoriteCount', 'recipes'));
    }

    // Default breeze methods below...
    public function edit(Request $request)
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    public function update(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'bio' => 'nullable|string|max:500',
            'profile_photo' => 'nullable|string|max:255',
        ]);

        $user = Auth::user();
        $user->full_name = $request->full_name;
        $user->bio = $request->bio;
        
        if ($request->has('profile_photo')) {
            $user->profile_photo = $request->profile_photo;
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil berhasil diperbarui!');
    }

    public function destroy(Request $request)
    {
        // Default Breeze delete logic
    }
}
