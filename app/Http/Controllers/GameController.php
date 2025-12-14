<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;

class GameController extends Controller
{
    // 1. PUBLIC HOME PAGE
    public function index()
    {
        // Олон категоритой болсон тул 'categories' гэж дуудна
        $games = Game::with('categories')->latest()->get(); 

        $sliderGames = Game::whereNotNull('banner')->latest()->take(5)->get();
        if ($sliderGames->isEmpty()) {
            $sliderGames = Game::latest()->take(5)->get();
        }

        $comingSoonGames = Game::where('tag', 'Тун удахгүй')->latest()->get();

        // Pivot table ашиглаж байгаа үед
        $categories = Category::withCount('games')->get();

        return view('welcome', compact('games', 'sliderGames', 'categories', 'comingSoonGames'));
    }

    // 2. ADMIN DASHBOARD
    public function adminDashboard()
    {
        $games = Game::with('categories')->latest()->get();
        $categories = Category::orderBy('name', 'asc')->get(); 
        return view('admin.dashboard', compact('games', 'categories'));
    }

    // 3. STORE (Шинэ тоглоом нэмэх)
    public function store(Request $request)
    {
        // Validation - category_id-г ХАСАЖ, categories-ийг НЭМСЭН
        $request->validate([
            'title'       => 'required',
            'price'       => 'required',
            'img'         => 'required',
            'categories'  => 'required|array', // <--- Олон сонголт (CheckBox)
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable',
            'trailer'     => 'nullable',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable',
            'release_date'=> 'nullable|date',
            'min_os'      => 'nullable',
            'min_cpu'     => 'nullable',
            'min_gpu'     => 'nullable',
            'min_ram'     => 'nullable',
            'min_storage' => 'nullable',
            'description' => 'nullable',
        ]);

        // category_id болон categories-ийг хасаж өгөгдлөө бэлдэнэ
        // Учир нь 'categories' нь pivot table руу орно, 'category_id' байхгүй
        $data = $request->except(['categories', 'screenshots']);

        // Screenshots цэвэрлэх
        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), function($value) {
                return !is_null($value) && $value !== '';
            });
            $data['screenshots'] = array_values($screenshots);
        }

        // Тоглоом үүсгэх
        $game = Game::create($data);

        // Олон категорийг хавсаргана (Pivot table)
        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }

        return redirect()->back()->with('success', 'Game added successfully!');
    }

    // 4. SHOW EDIT FORM
    public function edit($id)
    {
        $game = Game::with('categories')->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.game.edit', compact('game', 'categories'));
    }

    // 5. UPDATE GAME
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $request->validate([
            'title'       => 'required',
            'price'       => 'required',
            'img'         => 'required',
            'categories'  => 'nullable|array',
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable',
            'trailer'     => 'nullable',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable',
            'release_date'=> 'nullable|date',
            'min_os'      => 'nullable',
            'min_cpu'     => 'nullable',
            'min_gpu'     => 'nullable',
            'min_ram'     => 'nullable',
            'min_storage' => 'nullable',
            'description' => 'nullable',
        ]);
        
        $data = $request->except(['categories', 'screenshots']);
        
        // Screenshots шинэчлэх
        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), function($value) {
                return !is_null($value) && $value !== '';
            });
            $data['screenshots'] = array_values($screenshots);
        } else {
            $data['screenshots'] = null; 
        }

        // Үндсэн мэдээллийг шинэчлэх
        $game->update($data);

        // Категориудыг шинэчлэх (Sync - хуучныг устгаад шинийг нэмнэ)
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

        return view('game', compact('game', 'relatedGames'));
    }

    // 7. DELETE GAME
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();

        return redirect()->back()->with('success', 'Game deleted successfully!');
    }
}