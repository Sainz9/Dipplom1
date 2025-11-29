<?php

namespace App\Http\Controllers;

use App\Models\Product; // <-- Энд Game биш Product дуудаж байна
use Illuminate\Http\Request;

class GameController extends Controller
{
    // 1. Нүүр хуудсанд харуулах
    public function index() {
        // Product хүснэгтээс бүх тоглоомыг авна
        // Гэхдээ view рүүгээ 'games' гэсэн нэрээр явуулна (Ингэвэл welcome.blade.php-г засах шаардлагагүй)
        $games = Product::all(); 
        return view('welcome', ['games' => $games]);
    }

    // 2. Дэлгэрэнгүй хуудас
    public function show($id) {
        $game = Product::findOrFail($id);
        return view('game', ['game' => $game]);
    }

    // 3. Админ: Бараа нэмэх хуудас руу орох
    public function create()
    {
        return view('admin.games.create');
    }

    // 4. Админ: Барааг баазад хадгалах
    public function store(Request $request)
    {
        // Validation (Заавал бөглөх талбарууд)
        $request->validate([
            'title' => 'required',
            'price' => 'required|numeric',
            'img' => 'required',
            'genre' => 'required',
        ]);

        // Product (products хүснэгт) рүү хадгалах
        Product::create($request->all());

        return redirect()->route('admin.dashboard')->with('success', 'Тоглоом амжилттай нэмэгдлээ!');
    }
}