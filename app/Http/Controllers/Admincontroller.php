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
        $games = Game::with('category')->latest()->get();
        return view('admin.dashboard', compact('categories', 'games'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create($request->all());
        return back()->with('success', 'Категори амжилттай нэмэгдлээ!');
    }

    // ЭНЭ ФУНКЦИЙГ ШИНЭЧИЛЛЭЭ
    public function storeGame(Request $request)
{
    $request->validate([
        'title' => 'required',
        'price' => 'required',
        'category_id' => 'required',
        'img' => 'required',
    ]);

    $data = $request->all();

    // 1. Cover Image цэвэрлэх
    if (isset($data['img'])) {
        $data['img'] = str_replace(['"', "'", "src="], '', $data['img']);
    }

    // 2. Banner Image цэвэрлэх (ШИНЭ)
    if (isset($data['banner'])) {
        $data['banner'] = str_replace(['"', "'", "src="], '', $data['banner']);
    }
    
    // 3. Trailer цэвэрлэх
    if (isset($data['trailer'])) {
        $data['trailer'] = str_replace(['"', "'"], '', $data['trailer']);
    }

    Game::create($data);

    return back()->with('success', 'Тоглоом амжилттай нэмэгдлээ!');
}
}