<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;

class CartController extends Controller
{
 
    public function addToCart(Request $request)
    {
        $gameId = $request->input('game_id');
        $game = Game::findOrFail($gameId);
        $cart = session()->get('cart', []);

        if(isset($cart[$gameId])) {
            return redirect()->back()->with('info', 'Энэ тоглоом аль хэдийн сагсанд байна!');
        } else {
            $cart[$gameId] = [
                "title" => $game->title,
                "price" => $game->sale_price ?? $game->price, 
                "img" => $game->img,
                "quantity" => 1
            ];
        }

        
        session()->put('cart', $cart);

        return redirect()->back()->with('success', 'Сагсанд амжилттай нэмэгдлээ!');
    }

  
    public function index()
    {
        return view('cart'); 
    }
}