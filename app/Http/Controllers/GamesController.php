<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Game;
use App\Models\Category;
use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class GamesController extends Controller // GamesController биш GameController гэж нэрлэх нь зөв
{
    // ==========================================
    // FRONTEND: НҮҮР ХУУДАС
    // ==========================================
    public function index(Request $request)
    {
        // 1. Үндсэн Query
        $query = Game::with('categories');

        // 2. Хайлт
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 3. Төрөл (Genre)
        if ($request->filled('genre')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->genre . '%');
            });
        }

        // 4. Платформ
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        // 5. Үнэ
        if ($request->filled('price')) {
            switch ($request->price) {
                case 'free':
                    $query->where('price', 0)->orWhere('tag', 'FreeGame');
                    break;
                case 'sale':
                    $query->whereNotNull('sale_price')->where('sale_price', '>', 0);
                    break;
                case 'under_20':
                    $query->where('price', '<', 20000);
                    break;
            }
        }

        $games = $query->latest()->get();

        $sliderGames = Game::whereNotNull('banner')->latest()->take(5)->get();
        if ($sliderGames->isEmpty()) {
            $sliderGames = Game::latest()->take(5)->get();
        }

        $comingSoonGames = Game::where('tag', 'Тун удахгүй')->latest()->get();
        
        // ЭНЭ ДУТУУ БАЙСАН
        $navCategories = Category::orderBy('name', 'asc')->get(); 
        
        $categories = Category::with(['games' => function($q) {
            $q->latest()->take(10);
        }])->get();

        $sections = [
            'GOTY'          => ['title' => '🏆 Game of the Year', 'color' => 'yellow-500', 'border' => 'hover:border-yellow-500'],
            'BestSelling'   => ['title' => '💎 Best Sellers', 'color' => 'blue-500', 'border' => 'hover:border-blue-500'],
            'Trending'      => ['title' => '⚡ Trending', 'color' => 'orange-500', 'border' => 'hover:border-orange-500'],
            'EditorsChoice' => ['title' => '🎖️ Editor\'s Choice', 'color' => 'pink-500', 'border' => 'hover:border-pink-500'],
            'New'           => ['title' => '🔥 New Releases', 'color' => 'green-500', 'border' => 'hover:border-green-500'],
        ];

        return view('welcome', compact('games', 'sliderGames', 'categories', 'navCategories', 'comingSoonGames', 'sections'));
    }

    // ==========================================
    // BACKEND: ADMIN DASHBOARD
    // ==========================================
    public function adminDashboard()
    {
        $games = Game::with('categories')->latest()->get();
        $categories = Category::orderBy('name', 'asc')->get(); 
        return view('admin.dashboard', compact('games', 'categories'));
    }

    // Category Store (Дутуу байсан)
    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required|unique:categories,name|max:255']);
        Category::create(['name' => $request->name]);
        return redirect()->back()->with('success', 'Шинэ төрөл нэмэгдлээ!');
    }

    // Category Destroy (Дутуу байсан)
    public function destroyCategory($id)
    {
        $cat = Category::findOrFail($id);
        $cat->games()->detach();
        $cat->delete();
        return redirect()->back()->with('success', 'Төрөл устгагдлаа!');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'       => 'required|string|max:255',
            'price'       => 'required',
            'sale_price'  => 'nullable|numeric',
            'img'         => 'required|string|max:255',
            'categories'  => 'required|array',
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable|string|max:255',
            'trailer'     => 'nullable|string|max:255',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable|string|max:100',
            'release_date'=> 'nullable|date',
            'download_link' => 'nullable|string', // Шинэ
            'description' => 'nullable|string',
            // Specs
            'min_os' => 'nullable', 'min_cpu' => 'nullable', 'min_gpu' => 'nullable', 'min_ram' => 'nullable', 'min_storage' => 'nullable',
            'rec_os' => 'nullable', 'rec_cpu' => 'nullable', 'rec_gpu' => 'nullable', 'rec_ram' => 'nullable', 'rec_storage' => 'nullable',
            'developer' => 'nullable', // Шинэ
            'publisher' => 'nullable', // Шинэ
        ]);

        $data = $request->except(['categories', 'screenshots']);

        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), fn($v) => $v !== null && $v !== '');
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
            'title'       => 'required|string|max:255',
            'price'       => 'required',
            'sale_price'  => 'nullable|numeric',
            'img'         => 'required|string|max:255',
            'categories'  => 'nullable|array',
            'categories.*'=> 'exists:categories,id',
            'banner'      => 'nullable|string|max:255',
            'trailer'     => 'nullable|string|max:255',
            'screenshots' => 'nullable|array',
            'tag'         => 'nullable|string|max:100',
            'release_date'=> 'nullable|date',
            'download_link' => 'nullable|string',
            'description' => 'nullable|string',
            // Specs
            'min_os' => 'nullable', 'min_cpu' => 'nullable', 'min_gpu' => 'nullable', 'min_ram' => 'nullable', 'min_storage' => 'nullable',
            'rec_os' => 'nullable', 'rec_cpu' => 'nullable', 'rec_gpu' => 'nullable', 'rec_ram' => 'nullable', 'rec_storage' => 'nullable',
            'developer' => 'nullable',
            'publisher' => 'nullable',
        ]);

        $data = $request->except(['categories', 'screenshots']);

        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), fn($v) => $v !== null && $v !== '');
            $data['screenshots'] = array_values($screenshots); 
        } else {
            $data['screenshots'] = null; // Хоосон болгох
        }

        $game->update($data);

        if ($request->has('categories')) {
            $game->categories()->sync($request->input('categories'));
        } else {
            $game->categories()->detach();
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

    // destroyGame биш destroy гэж нэрлэв (Стандарт)
    public function destroy($id)
    {
        $game = Game::findOrFail($id);
        $game->delete();
        return redirect()->back()->with('success', 'Game deleted.');
    }
    
    // DOWNLOAD (Дутуу байсан)
    public function download($id)
    {
        $game = Game::findOrFail($id);

        if (empty($game->download_link)) {
            return back()->with('error', 'Энэ тоглоомд татах холбоос байхгүй байна.');
        }

        if ($game->price == 0 || $game->tag == 'FreeGame') {
            return redirect($game->download_link);
        }

        if (Auth::check()) {
            if (Auth::user()->usertype === 'admin') {
                return redirect($game->download_link);
            }
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

    public function about()
    {
        return view('profile.about');
    }
}