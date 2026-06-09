<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Recipe;
use App\Models\SavedRecipe;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RecipeController extends Controller
{
    public function explore(Request $request)
    {
        $query = Recipe::with(['user', 'category'])->where('status', 'published');

        if ($request->has('q') && !empty($request->q)) {
            $q = $request->q;
            $query->where('title', 'like', '%' . $q . '%')
                  ->orWhere('tags', 'like', '%' . $q . '%')
                  ->orWhereHas('category', function($catQuery) use ($q) {
                      $catQuery->where('name', 'like', '%' . $q . '%');
                  });
        }

        if ($request->has('category') && !empty($request->category)) {
            $query->where('category_id', $request->category);
        }

        $recipes = $query->orderBy('created_at', 'desc')->get();
        $categories = Category::all();

        return view('explore', compact('recipes', 'categories'));
    }

    public function show($id)
    {
        $recipe = Recipe::with(['user', 'category'])->findOrFail($id);
        $recipe->increment('views');

        $isSaved = false;
        if (Auth::check()) {
            $isSaved = SavedRecipe::where('user_id', Auth::id())
                ->where('recipe_id', $recipe->id)
                ->exists();
        }

        // Parse ingredients and steps (from string separated by \n to array)
        $ingredients = array_filter(array_map('trim', explode("\n", $recipe->ingredients)));
        $steps = array_filter(array_map('trim', explode("\n", $recipe->steps)));

        return view('detail-resep', compact('recipe', 'isSaved', 'ingredients', 'steps'));
    }

    public function favorites()
    {
        $userId = Auth::id();
        $savedRecipes = SavedRecipe::with(['recipe.user', 'recipe.category'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
            
        $recipes = $savedRecipes->pluck('recipe');

        return view('favorites', compact('recipes'));
    }

    public function toggleFavorite(Request $request, $id)
    {
        $userId = Auth::id();
        $recipeId = $id;

        $existing = SavedRecipe::where('user_id', $userId)->where('recipe_id', $recipeId)->first();

        if ($existing) {
            $existing->delete();
            return response()->json(['status' => 'removed', 'message' => 'Dihapus dari favorit']);
        } else {
            SavedRecipe::create([
                'user_id' => $userId,
                'recipe_id' => $recipeId
            ]);
            return response()->json(['status' => 'saved', 'message' => 'Disimpan ke favorit']);
        }
    }

    public function myRecipes()
    {
        $recipes = Recipe::with('category')
            ->where('user_id', Auth::id())
            ->orderBy('created_at', 'desc')
            ->get();

        return view('resep-saya', compact('recipes'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('upload-resep', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'cooking_time' => 'required|integer|min:1',
            'portion' => 'nullable|integer|min:1',
            'difficulty' => 'required|in:Mudah,Sedang,Sulit',
            'calories' => 'nullable|integer|min:0|max:99999',
            'ingredients' => 'required|array|min:1',
            'steps' => 'required|array|min:1',
            'image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        $imageName = 'placeholder-food.jpg';
        if ($request->hasFile('image')) {
            $imageName = time() . '_' . rand(1000, 9999) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('assets/img'), $imageName);
        }

        $ingredientsStr = implode("\n", array_filter(array_map('trim', $request->ingredients)));
        $stepsStr = implode("\n", array_filter(array_map('trim', $request->steps)));

        Recipe::create([
            'user_id' => Auth::id(),
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $ingredientsStr,
            'steps' => $stepsStr,
            'cooking_time' => $request->cooking_time,
            'difficulty' => $request->difficulty,
            'portion' => $request->portion ?: null,
            'calories' => $request->filled('calories') ? (int) $request->calories : null,
            'image' => $imageName,
            'status' => 'published',
        ]);

        return redirect()->route('recipes.mine')->with('success', 'Resep berhasil diupload!');
    }

    public function edit($id)
    {
        $recipe = Recipe::where('user_id', Auth::id())->findOrFail($id);
        $categories = Category::all();
        
        // Parse strings to arrays for the form
        $ingredients = array_filter(array_map('trim', explode("\n", $recipe->ingredients)));
        $steps = array_filter(array_map('trim', explode("\n", $recipe->steps)));

        return view('edit-resep', compact('recipe', 'categories', 'ingredients', 'steps'));
    }

    public function update(Request $request, $id)
    {
        $recipe = Recipe::where('user_id', Auth::id())->findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:200',
            'category_id' => 'required|exists:categories,id',
            'description' => 'required|string',
            'cooking_time' => 'required|integer|min:1',
            'portion' => 'nullable|integer|min:1',
            'difficulty' => 'required|in:Mudah,Sedang,Sulit',
            'calories' => 'nullable|integer|min:0|max:99999',
            'ingredients' => 'required|array|min:1',
            'steps' => 'required|array|min:1',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048'
        ]);

        if ($request->hasFile('image')) {
            // Delete old image if it exists and isn't the placeholder
            if ($recipe->image && $recipe->image !== 'placeholder-food.jpg') {
                $oldImagePath = public_path('assets/img/' . $recipe->image);
                if (file_exists($oldImagePath)) {
                    unlink($oldImagePath);
                }
            }
            
            $imageName = time() . '_' . rand(1000, 9999) . '.' . $request->file('image')->extension();
            $request->file('image')->move(public_path('assets/img'), $imageName);
            $recipe->image = $imageName;
        }

        $ingredientsStr = implode("\n", array_filter(array_map('trim', $request->ingredients)));
        $stepsStr = implode("\n", array_filter(array_map('trim', $request->steps)));

        $recipe->update([
            'category_id' => $request->category_id,
            'title' => $request->title,
            'description' => $request->description,
            'ingredients' => $ingredientsStr,
            'steps' => $stepsStr,
            'cooking_time' => $request->cooking_time,
            'difficulty' => $request->difficulty,
            'portion' => $request->portion ?: null,
            'calories' => $request->filled('calories') ? (int) $request->calories : null,
        ]);

        return redirect()->route('recipes.show', $recipe->id)->with('success', 'Resep berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $recipe = Recipe::where('user_id', Auth::id())->findOrFail($id);
        
        if ($recipe->image && $recipe->image !== 'placeholder-food.jpg') {
            $imagePath = public_path('assets/img/' . $recipe->image);
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }
        
        $recipe->delete();
        return redirect()->route('recipes.mine')->with('success', 'Resep berhasil dihapus!');
    }
}
