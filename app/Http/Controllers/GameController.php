<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category; // <--- 1. Model дуудна

class GameController extends Controller
{
    public function index()
    {
        $games = Game::all();
        
        // 2. Категориудыг татна
        $categories = Category::all(); 

        // 3. View рүү 'categories'-ийг хамт явуулна
        return view('welcome', [
            'games' => $games, 
            'categories' => $categories
        ]);
    }

    public function show($id)
    {
        $game = Game::findOrFail($id);
        return view('game', ['game' => $game, 'id' => $id]);
    }
}