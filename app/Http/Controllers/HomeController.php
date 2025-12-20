<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Game; // Game моделио дуудаж байна

class HomeController extends Controller
{
    public function index() {
        //
        $games = Game::all();
       
        return view('welcome', compact('games'));
    }
}