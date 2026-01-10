<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class GamesController extends Controller
{
    // --- Нүүр хуудас (Public Home) ---
    public function index(Request $request)
    {
        $query = Game::with('categories');

        // Хайлт болон Шүүлтүүр
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
            }
        }

        $games = $query->latest()->paginate(24);

        $sliderGames = Game::whereNotNull('banner')->latest()->take(5)->get();
        if ($sliderGames->isEmpty()) {
            $sliderGames = Game::latest()->take(5)->get();
        }

        $comingSoonGames = Game::whereIn('tag', ['Тун удахгүй', 'ComingSoon', 'PreOrder'])->latest()->get();
        
        $categories = Category::with(['games' => fn($q) => $q->latest()->take(10)])->get();
        $navCategories = Category::orderBy('name', 'asc')->get();

        $sections = [
            'GOTY' => ['title' => '🏆 Game of the Year', 'color' => 'yellow-500', 'border' => 'hover:border-yellow-500'],
            'BestSelling' => ['title' => '💎 Best Sellers', 'color' => 'blue-500', 'border' => 'hover:border-blue-500'],
            'Trending' => ['title' => '⚡ Trending', 'color' => 'orange-500', 'border' => 'hover:border-orange-500'],
            'New' => ['title' => '🔥 New Releases', 'color' => 'green-500', 'border' => 'hover:border-green-500'],
        ];

        return view('welcome', compact('games', 'sliderGames', 'categories', 'navCategories', 'comingSoonGames', 'sections'));
    }

    // --- Admin Dashboard ---
    public function adminDashboard()
    {
        $games = Game::with('categories')->latest()->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.dashboard', compact('games', 'categories'));
    }

    // --- Create Game (Store) - ЗАСВАР ОРСОН ---
    public function store(Request $request)
    {
        // 1. Validation: HTML дээр name="img_url" байгаа тул энд img_url гэж шалгана
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'img_url' => 'required', // ЗАСВАР: img биш img_url болсон
            'categories' => 'required|array'
        ]);

        // 2. URL input-уудыг автоматаар хадгалахгүйн тулд хасна
        $data = $request->except(['screenshots_urls', 'categories', 'img_url', 'banner_url', 'trailer_url', 'download_url']);

        // 3. URL-уудыг Database-ийн баганууд руу гараар оноох
        $data['img'] = $request->img_url;
        $data['banner'] = $request->banner_url;
        $data['trailer'] = $request->trailer_url;
        $data['download_link'] = $request->download_url;

        // 4. Screenshots Logic (Textarea -> Array)
        if ($request->filled('screenshots_urls')) {
            // Шинэ мөр эсвэл таслалаар салгах
            $urls = preg_split("/\\r\\n|\\r|\\n|,/", $request->input('screenshots_urls'));
            // Хоосон зайг арилгах
            $cleanUrls = array_filter(array_map('trim', $urls), fn($v) => !empty($v));
            $data['screenshots'] = array_values($cleanUrls);
        }

        // 5. Хадгалах
        $game = Game::create($data);

        // 6. Категори холбох
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

    // --- Update Game (Засах) - ЗАСВАР ОРСОН ---
    public function update(Request $request, $id)
    {
        $game = Game::findOrFail($id);

        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'img_url' => 'required', // ЗАСВАР: img_url болгосон
        ]);

        $data = $request->except(['screenshots_urls', 'categories', 'img_url', 'banner_url', 'trailer_url', 'download_url']);

        // URL Mapping
        $data['img'] = $request->img_url;
        $data['banner'] = $request->banner_url;
        $data['trailer'] = $request->trailer_url;
        $data['download_link'] = $request->download_url;

        // Screenshots Logic
        if ($request->filled('screenshots_urls')) {
            $urls = preg_split("/\\r\\n|\\r|\\n|,/", $request->input('screenshots_urls'));
            $cleanUrls = array_filter(array_map('trim', $urls), fn($v) => !empty($v));
            $data['screenshots'] = array_values($cleanUrls);
        } else {
            $data['screenshots'] = []; 
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
        $game->categories()->detach();
        $game->delete();
        return redirect()->back()->with('success', 'Game deleted successfully!');
    }

    // --- Download Game (Protected) ---
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
            if (Auth::user()->usertype === 'admin') return redirect($game->download_link);

            $hasPaid = Order::where('user_id', Auth::id())
                            ->where('game_id', $id)
                            ->where('status', 'paid')
                            ->exists();

            if ($hasPaid) {
                // Pre-Order Logic Check
                if ($game->tag == 'PreOrder' && $game->release_date && now()->lt($game->release_date)) {
                    return back()->with('error', 'Баяр хүргэе! Та урьдчилсан захиалга хийсэн байна. Тоглоом ' . $game->release_date . '-нд нээгдэнэ.');
                }
                
                return redirect($game->download_link);
            }
        }
        return back()->with('error', 'Уучлаарай, та энэ тоглоомыг худалдаж аваагүй байна.');
    }

    // --- Checkout Page ---
    public function checkout($id)
    {
        $game = Game::findOrFail($id);
        return view('payment.index', compact('game'));
    }

    // --- Category Management ---
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name|max:255']);
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Шинэ төрөл амжилттай нэмэгдлээ!');
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