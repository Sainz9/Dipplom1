<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;    
use App\Models\Order;
class GameController extends Controller
{
   

 public function index()
{
    // 1. Бүх тоглоомыг категоритай нь дуудна
    $games = Game::with('categories')->latest()->get(); 
    
    public function index()
    {
      
        $games = Game::with('categories')->latest()->get();



    $sliderGames = Game::whereNotNull('banner')->latest()->take(5)->get();
    if ($sliderGames->isEmpty()) {
        $sliderGames = Game::latest()->take(5)->get();
    }

}
    $comingSoonGames = Game::where('tag', 'Тун удахгүй')->latest()->get();

 
    $categories = Category::with(['games' => function($query) {
        $query->latest()->take(10);
    }])->get();

    return view('welcome', compact('games', 'sliderGames', 'categories', 'comingSoonGames'));
}


    public function adminDashboard()
    {
        $games = Game::with('categories')->latest()->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.dashboard', compact('games', 'categories'));
    }

   
    public function store(Request $request)
    {
       
        $request->validate([
            'title'       => 'required',
            'price'       => 'required',
            'sale_price'  => 'nullable|numeric',
            'img'         => 'required',
            'categories'  => 'required|array', 
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable',
            'trailer'     => 'nullable',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable',
            'release_date'=> 'nullable|date',
            'description' => 'nullable',
            
            'min_os'      => 'nullable',
            'min_cpu'     => 'nullable',
            'min_gpu'     => 'nullable',
            'min_ram'     => 'nullable',
            'min_storage' => 'nullable',

      
            'rec_os'      => 'nullable',
            'rec_cpu'     => 'nullable',
            'rec_gpu'     => 'nullable',
            'rec_ram'     => 'nullable',
            'rec_storage' => 'nullable',
        ]);

     
        $data = $request->except(['categories', 'screenshots']);

       
        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), function($value) {
                return !is_null($value) && $value !== '';
            });
            $data['screenshots'] = array_values($screenshots);
        }

        $game = Game::create($data);

      
        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }

        return redirect()->back()->with('success', 'Game added successfully!');
    }

   
    public function edit($id)
    {
        $game = Game::with('categories')->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.game.edit', compact('game', 'categories'));
    }


    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

     
        $request->validate([
            'title'       => 'required',
            'price'       => 'required',
            'sale_price'  => 'nullable|numeric',
            'img'         => 'required',
            'categories'  => 'nullable|array',
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable',
            'trailer'     => 'nullable',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable',
            'release_date'=> 'nullable|date',
            'description' => 'nullable',
            
    
            'min_os'      => 'nullable',
            'min_cpu'     => 'nullable',
            'min_gpu'     => 'nullable',
            'min_ram'     => 'nullable',
            'min_storage' => 'nullable',

            'rec_os'      => 'nullable',
            'rec_cpu'     => 'nullable',
            'rec_gpu'     => 'nullable',
            'rec_ram'     => 'nullable',
            'rec_storage' => 'nullable',
        ]);

        $data = $request->except(['categories', 'screenshots']);

        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), function($value) {
                return !is_null($value) && $value !== '';
            });
            $data['screenshots'] = array_values($screenshots);
        } else {
            $data['screenshots'] = null;
        }

    
        $game->update($data);

    
        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Game updated successfully!');
    }


    // 6. SHOW SINGLE GAME
    public function show($id)
    {
        $game = Game::with('categories')->findOrFail($id);

        // Related Games (Олон төрлөөр шүүх)
        $relatedGames = Game::whereHas('categories', function($query) use ($game) {
            $query->whereIn('categories.id', $game->categories->pluck('id'));
        })
        ->where('id', '!=', $id)
        ->inRandomOrder()
        ->take(4)
        ->get();
>>>>>>> 2d56e543f76639e5ece05418fe45ae9589a65f8c


public function show($id)
{
    
    $game = Game::with('categories')->findOrFail($id);
    
    // 2. Төстэй тоглоомуудыг олох (Optional)
    $relatedGames = Game::whereHas('categories', function($query) use ($game) {
        $query->whereIn('categories.id', $game->categories->pluck('id'));
    })
    ->where('id', '!=', $id)
    ->inRandomOrder()
    ->take(4)
    ->get();

 
    return view('game', compact('game', 'relatedGames')); 
}
    // 7. DELETE GAME
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect()->back()->with('success', 'Game deleted successfully!');
    }

public function download($id)
    {
        $game = Game::findOrFail($id);

     
        if (empty($game->download_link)) {
            return back()->with('error', 'Энэ тоглоомд татах холбоос хараахан ороогүй байна.');
        }

   
        if ($game->price == 0) {
            return redirect($game->download_link);
        }

        if (Auth::check()) {
            $hasPaid = Order::where('user_id', Auth::id())
                            ->where('game_id', $id)
                            ->where('status', 'paid')
                            ->exists();
            
            if ($hasPaid) {
                return redirect($game->download_link);
            }
        }

        if (session('game_id') == $id) {
            return redirect($game->download_link);
        }

        return back()->with('error', 'Уучлаарай, та энэ тоглоомыг худалдаж аваагүй байна.');
    }

}

}

