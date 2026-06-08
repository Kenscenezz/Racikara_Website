<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $recentRecipes = Recipe::with(['user', 'category'])
            ->where('status', 'published')
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();
            
        $popularRecipes = Recipe::with(['user', 'category'])
            ->where('status', 'published')
            ->orderBy('views', 'desc')
            ->limit(6)
            ->get();

        return view('home', compact('categories', 'recentRecipes', 'popularRecipes'));
    }
}
