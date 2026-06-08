<?php

namespace App\Http\Controllers;

use App\Models\Recipe;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        if (Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        $stats = [
            'users' => User::count(),
            'recipes' => Recipe::count(),
            'views' => Recipe::sum('views'),
            'admins' => User::where('role', 'admin')->count()
        ];

        $users = User::orderBy('created_at', 'desc')->get();
        $recipes = Recipe::with(['user', 'category'])->orderBy('created_at', 'desc')->get();

        return view('admin', compact('stats', 'users', 'recipes'));
    }

    public function destroyRecipe($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        $recipe = Recipe::findOrFail($id);
        
        if ($recipe->image && $recipe->image !== 'placeholder-food.jpg') {
            $imagePath = public_path('assets/img/' . $recipe->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $recipe->delete();
        
        return redirect()->route('admin.index')->with('success', 'Resep berhasil dihapus oleh Admin');
    }

    public function destroyUser($id)
    {
        if (Auth::user()->role !== 'admin') {
            abort(403);
        }

        if (Auth::id() == $id) {
            return redirect()->route('admin.index')->with('error', 'Tidak dapat menghapus akun sendiri!');
        }

        $user = User::findOrFail($id);
        
        // Also delete their recipe images
        $recipes = $user->recipes;
        foreach($recipes as $recipe) {
            if ($recipe->image && $recipe->image !== 'placeholder-food.jpg') {
                $imagePath = public_path('assets/img/' . $recipe->image);
                if (file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }
        }

        $user->delete();
        
        return redirect()->route('admin.index')->with('success', 'User dan semua datanya berhasil dihapus');
    }
}
