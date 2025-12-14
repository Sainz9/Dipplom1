<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Game;

class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        // 'category' биш 'categories' гэж дуудах нь зөв (Олон төрөл учраас)
        $games = Game::with('categories')->latest()->get();
        return view('admin.dashboard', compact('categories', 'games'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create($request->all());
        return back()->with('success', 'Категори амжилттай нэмэгдлээ!');
    }

    // --- ЗАССАН ФУНКЦ ---
    public function storeGame(Request $request)
    {
        // 1. Validation (category_id БИШ categories байх ёстой)
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'categories' => 'required|array', // Олон сонголт
            'img' => 'required',
        ]);

        // 2. categories-ээс бусад өгөгдлийг авна
        $data = $request->except('categories');

        // Cover Image цэвэрлэх
        if (isset($data['img'])) {
            $data['img'] = str_replace(['"', "'", "src="], '', $data['img']);
        }

        // Banner Image цэвэрлэх
        if (isset($data['banner'])) {
            $data['banner'] = str_replace(['"', "'", "src="], '', $data['banner']);
        }
        
        // Trailer цэвэрлэх
        if (isset($data['trailer'])) {
            $data['trailer'] = str_replace(['"', "'"], '', $data['trailer']);
        }

        // 3. Тоглоомыг хадгалах
        $game = Game::create($data);

        // 4. Категориудыг холбох (Pivot table)
        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }

        return back()->with('success', 'Тоглоом амжилттай нэмэгдлээ!');
    }
}