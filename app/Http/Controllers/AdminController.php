<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Support\Facades\URL;
class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
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
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'categories' => 'required|array',
            'img' => 'required',
        ]);

    
        $data = $request->except('categories');

     
        if (isset($data['img'])) {
            $data['img'] = str_replace(['"', "'", "src="], '', $data['img']);
        }

       
        if (isset($data['banner'])) {
            $data['banner'] = str_replace(['"', "'", "src="], '', $data['banner']);
        }
        
     
        if (isset($data['trailer'])) {
            $data['trailer'] = str_replace(['"', "'"], '', $data['trailer']);
        }


        $game = Game::create($data);

      
        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }

        return back()->with('success', 'Тоглоом амжилттай нэмэгдлээ!');
    }
}