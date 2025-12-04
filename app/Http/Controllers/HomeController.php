<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Game; // Game моделио дуудаж байна

class HomeController extends Controller
{
    public function index() {
        // Баазаас бүх тоглоомыг авна
        $games = Game::all();
        // welcome хуудас руу $games-ийг илгээнэ
        return view('welcome', compact('games'));
    }
}