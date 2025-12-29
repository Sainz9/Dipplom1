<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;
use App\Models\Order; // !!! ЗАХИАЛГА ШАЛГАХАД ХЭРЭГТЭЙ
use Illuminate\Support\Facades\Auth;
class GamesController extends Controller
{
    
    public function index()
    {
        $games = Game::with('categories')->latest()->get(); 
        $sliderGames = Game::whereNotNull('banner')->latest()->take(5)->get();
        if ($sliderGames->isEmpty()) {
            $sliderGames = Game::latest()->take(5)->get();
        }

        $comingSoonGames = Game::where('tag', 'Тун удахгүй')->latest()->get();
        $categories = Category::with('games')->get();

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
            'title'       => 'required|string|max:255',
            'price'       => 'required',
            'img'         => 'required|string|max:255',
            'categories'  => 'required|array',
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable|string|max:65535',
            'trailer'     => 'nullable|string|max:255',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable|string|max:100',
            'release_date'=> 'nullable|date',
            'min_os'      => 'nullable|string|max:100',
            'min_cpu'     => 'nullable|string|max:255',
            'min_gpu'     => 'nullable|string|max:255',
            'min_ram'     => 'nullable|string|max:100',
            'min_storage' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $data = $request->except(['categories', 'screenshots']);

     
        $data['screenshots'] = $request->input('screenshots') 
            ? array_values(array_filter($request->input('screenshots'), fn($v) => $v !== null && $v !== ''))
            : null;

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
            'title'       => 'required|string|max:255',
            'price'       => 'required',
            'img'         => 'required|string|max:255',
            'categories'  => 'nullable|array',
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable|string|max:65535',
            'trailer'     => 'nullable|string|max:255',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable|string|max:100',
            'release_date'=> 'nullable|date',
            'min_os'      => 'nullable|string|max:100',
            'min_cpu'     => 'nullable|string|max:255',
            'min_gpu'     => 'nullable|string|max:255',
            'min_ram'     => 'nullable|string|max:100',
            'min_storage' => 'nullable|string|max:100',
            'description' => 'nullable|string',
        ]);

        $data = $request->except(['categories', 'screenshots']);

        $data['screenshots'] = $request->input('screenshots') 
            ? array_values(array_filter($request->input('screenshots'), fn($v) => $v !== null && $v !== ''))
            : null;

        $game->update($data);

        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Game updated successfully!');
    }


    public function show($id)
    {
        $game = Game::with('categories')->findOrFail($id);

        $relatedGames = Game::whereHas('categories', function($query) use ($game) {
                                $query->whereIn('categories.id', $game->categories->pluck('id'));
                            })
                            ->where('id', '!=', $id)
                            ->inRandomOrder()
                            ->take(4)
                            ->get();

        return view('game', compact('game', 'relatedGames'));
    }

  
     public function destroyGame($id)
{
    Game::destroy($id);
    return back()->with('success', 'Game deleted.');
}
public function about()
{
    return view('profile.about');
}
}
