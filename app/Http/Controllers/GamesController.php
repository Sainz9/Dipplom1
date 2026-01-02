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

// Энэ функцийг бүгдийг нь хуулаад хуучин store функцийнхээ оронд тавиарай
    public function store(Request $request)
    {
        // 1. VALIDATION (Шалгалт)
        $request->validate([
            'title'           => 'required',
            'price'           => 'required',
            'sale_price'      => 'nullable|numeric',
            
            // --- ГОЛ ӨӨРЧЛӨЛТ ЭНД БАЙНА ---
            // Хуучин 'img' => 'required' гэдгийг УСТГАСАН.
            // Оронд нь файл эсвэл линк хоёрын аль нэг нь байхад болно гэж зааж өгч байна.
            'img_file'        => 'nullable|image|max:5120', 
            'img_url'         => 'nullable|url|required_without:img_file', 

            'categories'      => 'required|array',
            'categories.*'    => 'exists:categories,id',
            
            // Бусад талбарууд
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
            'screenshots_files.*' => 'image|max:5120',
        ]);

        // Data бэлдэх (Request-ээс файлуудыг хасаж авна)
        $data = $request->except([
            'img_file', 'img_url', 
            'banner_file', 'banner_url', 
            'trailer_file', 'trailer_url', 
            'download_file', 'download_url', 
            'categories', 'screenshots_files'
        ]);

        // --- 1. COVER IMAGE LOGIC ---
        // Хэрэв файл сонгосон бол файлыг хадгална
        if ($request->hasFile('img_file')) {
            $path = $request->file('img_file')->store('games/covers', 'public');
            $data['img'] = '/storage/' . $path;
        } 
        // Хэрэв файл байхгүй ч URL бичсэн бол URL-ийг хадгална
        elseif ($request->filled('img_url')) {
            $data['img'] = $request->img_url;
        }

        // --- 2. BANNER LOGIC ---
        if ($request->hasFile('banner_file')) {
            $path = $request->file('banner_file')->store('games/banners', 'public');
            $data['banner'] = '/storage/' . $path;
        } elseif ($request->filled('banner_url')) {
            $data['banner'] = $request->banner_url;
        }

        // --- 3. TRAILER LOGIC ---
        if ($request->hasFile('trailer_file')) {
            $path = $request->file('trailer_file')->store('games/trailers', 'public');
            $data['trailer'] = '/storage/' . $path;
        } elseif ($request->filled('trailer_url')) {
            $data['trailer'] = $request->trailer_url;
        }

        // --- 4. DOWNLOAD LOGIC ---
        if ($request->hasFile('download_file')) {
            $path = $request->file('download_file')->store('games/files', 'public');
            $data['download_link'] = '/storage/' . $path;
        } elseif ($request->filled('download_url')) {
            $data['download_link'] = $request->download_url;
        }

        // --- 5. SCREENSHOTS ---
        $screenshots = [];
        if ($request->hasFile('screenshots_files')) {
            foreach ($request->file('screenshots_files') as $file) {
                $path = $file->store('games/screenshots', 'public');
                $screenshots[] = '/storage/' . $path;
            }
        }
        // Хуучин screenshot input үлдсэн байж магадгүй тул шалгах
        if ($request->has('screenshots') && is_array($request->screenshots)) {
             $screenshots = array_merge($screenshots, $request->screenshots);
        }

        if (!empty($screenshots)) {
            $data['screenshots'] = $screenshots;
        }

        // CREATE GAME
        $game = Game::create($data);

        // CATEGORIES
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
