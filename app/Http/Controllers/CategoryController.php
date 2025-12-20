<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
{
  
    $games = Game::with('category')->latest()->get(); 

    $sliderGames = Game::whereNotNull('banner')->latest()->take(3)->get();
    if ($sliderGames->isEmpty()) {
        $sliderGames = Game::latest()->take(3)->get();
    }

    $categories = Category::withCount('games')->get();

    return view('welcome', [
        'games' => $games,
        'sliderGames' => $sliderGames,
        'categories' => $categories
    ]);
}
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255'
        ]);

        Category::create([
            'name' => $request->name
        ]);

        return redirect()->back()->with('success', 'Category added successfully!');
    }


    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}