<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function index()
{
    // 1. БҮХ ТОГЛООМ (Category-г нь хамт дуудна - ЭНЭ ЧУХАЛ)
    $games = Game::with('category')->latest()->get(); 

    // 2. SLIDER
    $sliderGames = Game::whereNotNull('banner')->latest()->take(3)->get();
    if ($sliderGames->isEmpty()) {
        $sliderGames = Game::latest()->take(3)->get();
    }

    // 3. КАТЕГОРИУД (Хоосон байсан ч харуулна)
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

    // Төрөл устгах
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Category deleted successfully!');
    }
}