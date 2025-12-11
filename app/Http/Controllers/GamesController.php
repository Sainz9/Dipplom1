<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;

class GamesController extends Controller
{
    // 1. PUBLIC HOME PAGE
public function index()
{
    $sliderGames = Game::latest()->take(5)->get();

    return view('admin.dashboard', compact('sliderGames'));
}


    // 2. ADMIN DASHBOARD
    public function adminDashboard()
    {
        $games = Game::with('category')->latest()->get();
        // Категорийг сүүлд нэмснээр нь эрэмбэлэх
        $categories = Category::orderBy('id', 'desc')->get(); 
        
        return view('admin.dashboard', compact('games', 'categories'));
    }

    // 3. STORE (Шинэ тоглоом нэмэх)
    public function store(Request $request)
    {
        // Validation - "numeric" гэдгийг авч хаясан тул Текст бичиж болно
        $request->validate([
            'title'       => 'required',
            'price'       => 'required', 
            'img'         => 'required',
            'category_id' => 'required',
            'banner'      => 'nullable',
            'trailer'     => 'nullable',
            'screenshot1' => 'nullable',
            'screenshot2' => 'nullable',
            'tag'         => 'nullable',
            'rating'      => 'nullable',
            'release_date'=> 'nullable|date', // Огноо
            'min_os'      => 'nullable',
            'min_cpu'     => 'nullable',
            'min_gpu'     => 'nullable',
            'min_ram'     => 'nullable',
            'min_storage' => 'nullable',
        ]);

        $data = $request->all();
        // Checkbox handling
        $data['is_preorder'] = $request->has('is_preorder');

        Game::create($data);

        return redirect()->back()->with('success', 'Game added successfully!');
    }

    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $game = Game::findOrFail($id);
        $categories = Category::orderBy('id', 'desc')->get();
        return view('admin.game.edit', compact('game', 'categories'));
    }

    // 5. UPDATE GAME
    public function update(Request $request, $id)
    {
        $request->validate([
            'title'       => 'required',
            'price'       => 'required', // numeric байхгүй
            'img'         => 'required',
            'category_id' => 'required',
            'banner'      => 'nullable',
            'trailer'     => 'nullable',
            'screenshot1' => 'nullable',
            'screenshot2' => 'nullable',
            'tag'         => 'nullable',
            'rating'      => 'nullable',
            'release_date'=> 'nullable|date',
            'min_os'      => 'nullable',
            'min_cpu'     => 'nullable',
            'min_gpu'     => 'nullable',
            'min_ram'     => 'nullable',
            'min_storage' => 'nullable',
        ]);

        $game = Game::findOrFail($id);
        
        $data = $request->all();
        $data['is_preorder'] = $request->has('is_preorder');
        
        $game->update($data);

        return redirect()->route('admin.dashboard')->with('success', 'Game updated successfully!');
    }

    // 6. SHOW SINGLE GAME
    public function show($id)
    {
        $game = Game::with('category')->findOrFail($id);

        $relatedGames = Game::where('category_id', $game->category_id)
                            ->where('id', '!=', $id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('game.show', compact('game', 'relatedGames'));
    }

    // 7. DELETE GAME
    public function destroyGame($id)
{
    Game::destroy($id);
    return back()->with('success', 'Game deleted.');
}
}