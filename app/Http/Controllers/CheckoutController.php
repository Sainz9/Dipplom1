<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game; // Game моделийг дуудах

class CheckoutController extends Controller
{
    public function index(Request $request)
    {
  
        $gameId = $request->input('game_id');

    
        if (!$gameId) {
            return redirect('/'); 
        }

        $game = Game::findOrFail($gameId);

        return view('checkout', compact('game'));
    }
}