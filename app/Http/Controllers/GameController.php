<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use App\Models\Order;
use App\Models\User;

class GameController extends Controller
{
    // --- Нүүр хуудас (Public Home) ---
    public function index(Request $request)
    {
        $query = Game::with('categories');

        // Search & Filter Logic
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('genre')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->genre . '%');
            });
        }
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'free': $query->where('price', 0)->orWhere('tag', 'FreeGame'); break;
                case 'sale': $query->whereNotNull('sale_price')->where('sale_price', '>', 0); break;
                case 'under_20': $query->where('price', '<', 20000); break;
            }
        }

        $games = $query->orderBy('created_at', 'desc')->get();

        // Slider (Banner-тай тоглоомууд)
        $sliderGames = Game::whereNotNull('banner')->orderBy('created_at', 'desc')->take(5)->get();
        if ($sliderGames->isEmpty()) {
            $sliderGames = Game::orderBy('created_at', 'desc')->take(5)->get();
        }

        // Coming Soon
        $comingSoonGames = Game::where('tag', 'Тун удахгүй')->orderBy('created_at', 'desc')->take(4)->get();

        // Footer ангилал (Fix: games.created_at гэж тодотгох)
        $categories = Category::with(['games' => function($query) {
            $query->orderBy('games.created_at', 'desc')->take(10);
        }])->get();

        $navCategories = Category::orderBy('name', 'asc')->get();

        $sections = [
            'GOTY'          => ['title' => '🏆 Game of the Year', 'color' => 'yellow-500', 'border' => 'hover:border-yellow-500'],
            'BestSelling'   => ['title' => '💎 Best Sellers', 'color' => 'blue-500', 'border' => 'hover:border-blue-500'],
            'Шинэ'          => ['title' => '🔥 Шинэ (New)', 'color' => 'green-500', 'border' => 'hover:border-green-500'],
            'EditorsChoice' => ['title' => '🎖️ Редакторын сонголт', 'color' => 'pink-500', 'border' => 'hover:border-pink-500'],
            'Эрэлттэй'      => ['title' => '⚡ Эрэлттэй', 'color' => 'orange-500', 'border' => 'hover:border-orange-500'],
            'Trending'      => ['title' => '⚡ Эрэлттэй', 'color' => 'orange-500', 'border' => 'hover:border-orange-500'],
        ];

        return view('welcome', compact('games', 'sliderGames', 'categories', 'navCategories', 'comingSoonGames', 'sections'));
    }

    // --- Admin Dashboard (Тоглоомын жагсаалт) ---
    public function adminDashboard()
    {
        $games = Game::with('categories')->orderBy('created_at', 'desc')->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.dashboard', compact('games', 'categories'));
    }

    // --- Create Game (Store) ---
    public function store(Request $request)
    {
        $request->validate([
            'title'           => 'required',
            'price'           => 'required',
            'sale_price'      => 'nullable|numeric',
            'img_file'        => 'nullable|image|max:5120', 
            'img_url'         => 'nullable|url|required_without:img_file', 
            'categories'      => 'required|array',
            'categories.*'    => 'exists:categories,id',
            'banner_file'     => 'nullable|image|max:10240',
            'banner_url'      => 'nullable|url',
            'trailer_file'    => 'nullable|mimetypes:video/mp4,video/webm|max:51200',
            'trailer_url'     => 'nullable|url',
            'download_file'   => 'nullable|file|max:512000',
            'download_url'    => 'nullable|url',
            'tag'             => 'nullable',
            'description'     => 'nullable',
            'developer'       => 'nullable',
            'publisher'       => 'nullable',
            'release_date'    => 'nullable',
            'screenshots_files.*' => 'required|mimes:jpeg,jpg,png,gif,webp|max:10240',
        ]);

        $data = $request->except([
            'img_file', 'img_url', 
            'banner_file', 'banner_url', 
            'trailer_file', 'trailer_url', 
            'download_file', 'download_url', 
            'categories', 
            'screenshots_files'
        ]);

        // File Upload Logic
        if ($request->hasFile('img_file')) {
            $path = $request->file('img_file')->store('games/covers', 'public');
            $data['img'] = '/storage/' . $path;
        } elseif ($request->filled('img_url')) {
            $data['img'] = $request->img_url;
        }

        if ($request->hasFile('banner_file')) {
            $path = $request->file('banner_file')->store('games/banners', 'public');
            $data['banner'] = '/storage/' . $path;
        } elseif ($request->filled('banner_url')) {
            $data['banner'] = $request->banner_url;
        }

        if ($request->hasFile('trailer_file')) {
            $path = $request->file('trailer_file')->store('games/trailers', 'public');
            $data['trailer'] = '/storage/' . $path;
        } elseif ($request->filled('trailer_url')) {
            $data['trailer'] = $request->trailer_url;
        }

        if ($request->hasFile('download_file')) {
            $path = $request->file('download_file')->store('games/files', 'public');
            $data['download_link'] = '/storage/' . $path;
        } elseif ($request->filled('download_url')) {
            $data['download_link'] = $request->download_url;
        }

        // Screenshots
        $screenshots = [];
        if ($request->hasFile('screenshots_files')) {
            foreach ($request->file('screenshots_files') as $file) {
                $path = $file->store('games/screenshots', 'public');
                $screenshots[] = '/storage/' . $path;
            }
        }
        if (!empty($screenshots)) {
            $data['screenshots'] = $screenshots;
        }

        $game = Game::create($data);

        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }
        
        return redirect()->back()->with('success', 'Game added successfully!');
    }

    // --- Edit Page ---
    public function edit($id)
    {
        $game = Game::with('categories')->findOrFail($id);
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.game.edit', compact('game', 'categories'));
    }

    // --- Update Game ---
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $request->validate([
            'title'       => 'required',
            'price'       => 'required',
            'sale_price'  => 'nullable|numeric',
            'img_file'    => 'nullable|image|max:5120', 
            'categories'  => 'nullable|array',
            'categories.*'=> 'exists:categories,id',
            'screenshots_files.*' => 'image|max:5120',
        ]);

        $data = $request->except([
            'img_file', 'img_url', 
            'categories', 
            'screenshots_files', 
            'screenshots'
        ]); 

        if ($request->hasFile('img_file')) {
            $path = $request->file('img_file')->store('games/covers', 'public');
            $data['img'] = '/storage/' . $path;
        } elseif ($request->filled('img_url')) {
            $data['img'] = $request->img_url;
        }

        // Screenshots Update
        $currentScreenshots = $game->screenshots ?? [];
        if(is_string($currentScreenshots)) $currentScreenshots = json_decode($currentScreenshots, true) ?? [];

        if ($request->hasFile('screenshots_files')) {
            foreach ($request->file('screenshots_files') as $file) {
                $path = $file->store('games/screenshots', 'public');
                $currentScreenshots[] = '/storage/' . $path;
            }
            $data['screenshots'] = $currentScreenshots;
        }

        $game->update($data);

        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        }

        return redirect()->route('admin.dashboard')->with('success', 'Game updated successfully!');
    }

    // --- Show Game Details (Public) ---
    public function show($id)
    {
        $game = Game::with(['categories', 'reviews.user'])->findOrFail($id);

        $relatedGames = Game::whereHas('categories', function($query) use ($game) {
            $query->whereIn('categories.id', $game->categories->pluck('id'));
        })
        ->where('id', '!=', $id)
        ->inRandomOrder()
        ->take(4)
        ->get();

        return view('game', compact('game', 'relatedGames'));
    }

    // --- Delete Game ---
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
        return redirect()->back()->with('success', 'Game deleted successfully!');
    }

    // --- Download Game (Protected) ---
    public function download($id)
    {
        $game = Game::findOrFail($id);

        if (empty($game->download_link)) {
            return back()->with('error', 'Энэ тоглоомд татах холбоос байхгүй байна.');
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

        return back()->with('error', 'Уучлаарай, та энэ тоглоомыг худалдаж аваагүй байна.');
    }

    // --- Checkout Page ---
    public function checkout($id)
    {
        $game = Game::findOrFail($id);
        return view('checkout', compact('game'));
    }

    // --- Category Management ---
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name|max:255']);
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Шинэ төрөл амжилттай нэмэгдлээ!');
    }

    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();
        return redirect()->back()->with('success', 'Төрөл амжилттай устгагдлаа!');
    }

    // --- About Page ---
    public function about()
    {
        $gamesCount = Game::count();
        $gamersCount = User::count(); 
        return view('profile.about', compact('gamesCount', 'gamersCount'));    
    }
}