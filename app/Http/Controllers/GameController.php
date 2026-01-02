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
    // Нүүр хуудас (Хайлт болон Шүүлтүүртэй)
    public function index(Request $request)
    {
        // 1. Үндсэн Query
        $query = Game::with('categories');

        // 2. Хайлт (Search Input)
        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        // 3. Төрөл (Genre/Category)
        if ($request->filled('genre')) {
            $query->whereHas('categories', function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->genre . '%');
            });
        }

        // 4. Платформ (Platform)
        if ($request->filled('platform')) {
            $query->where('platform', 'like', '%' . $request->platform . '%');
        }

        // 5. Үнэ (Price)
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

        // Бүх тоглоом (Шүүлтүүртэй)
        $games = $query->latest()->get();

        // Slider-т зориулсан тоглоомууд
        $sliderGames = Game::whereNotNull('banner')->latest()->take(5)->get();
        if ($sliderGames->isEmpty()) {
            $sliderGames = Game::latest()->take(5)->get();
        }

        // Coming Soon
        $comingSoonGames = Game::where('tag', 'Тун удахгүй')->latest()->get();

        // --- ЭНД ЗАСВАР ХИЙСЭН (Ambiguous column name алдааг засах) ---
        // Категориуд (Footer хэсэгт эсвэл өөр газар хэрэг болж магадгүй)
        $categories = Category::with(['games' => function($query) {
            // 'latest()' нь зөвхөн 'created_at' гэж дууддаг тул хоёр хүснэгт нийлэхэд алдаа гардаг.
            // Тиймээс хүснэгтийн нэрийг тодорхой зааж өгөв: 'games.created_at'
            $query->orderBy('games.created_at', 'desc')->take(10);
        }])->get();

        // --- Navbar дээрх Dropdown цэсэнд зориулсан (АВТОМАТААР ШИНЭЧЛЭГДЭНЭ) ---
        $navCategories = Category::orderBy('name', 'asc')->get();

        // --- Нүүр хуудасны хэсгүүдийн тохиргоо ---
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

    public function adminDashboard()
    {
        $games = Game::with('categories')->latest()->get();
        $categories = Category::orderBy('name', 'asc')->get();
        return view('admin.dashboard', compact('games', 'categories'));
    }

    // --- ШИНЭ ТӨРӨЛ НЭМЭХ (Category Add) ---
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:categories,name|max:255',
        ]);

        Category::create([
            'name' => $request->name,
            // Laravel автоматаар цагийг бөглөдөг тул created_at гараар бичих шаардлагагүй, гэхдээ үлдээлээ
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Шинэ төрөл амжилттай нэмэгдлээ!');
    }

    // --- ТӨРӨЛ УСТГАХ (Category Delete) ---
    public function destroyCategory($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->back()->with('success', 'Төрөл амжилттай устгагдлаа!');
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
            'platform'    => 'nullable', 
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
            'developer'   => 'nullable',
            'publisher'   => 'nullable',
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
            'platform'    => 'nullable',
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
            'developer'   => 'nullable',
            'publisher'   => 'nullable',
        ]);

        $data = $request->except(['categories', 'screenshots']);

        if ($request->has('screenshots')) {
            $screenshots = array_filter($request->input('screenshots'), fn($v) => $v !== null && $v !== '');
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

    public function about()
    {
        // Database-ээс нийт тоглоомын тоог авах
        $gamesCount = Game::count();

        // Database-ээс нийт бүртгэлтэй хэрэглэгчийн тоог авах
        $gamersCount = User::count(); 

        // View рүү хувьсагчуудаа дамжуулах
        return view('profile.about', compact('gamesCount', 'gamersCount'));    
    }
}