<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Category;
use App\Models\Game;
use Illuminate\Support\Facades\URL;
class AdminController extends Controller
{
    public function index()
    {
        $categories = Category::all();
        $games = Game::with('categories')->latest()->get();
        return view('admin.dashboard', compact('categories', 'games'));
    }

    public function storeCategory(Request $request)
    {
        $request->validate(['name' => 'required']);
        Category::create($request->all());
        return back()->with('success', 'Категори амжилттай нэмэгдлээ!');
    }

    // --- ЗАССАН ФУНКЦ ---
   // --- ЗАССАН ФУНКЦ (FILE & URL SUPPORT) ---
    public function storeGame(Request $request)
    {
        // 1. Validation: Файл эсвэл Линк хоёрын аль нэг нь байхад хангалттай
        $request->validate([
            'title' => 'required',
            'price' => 'required',
            'categories' => 'required|array',
            
            // ЗУРАГ: Файл байхгүй бол URL шаардана
            'img_file' => 'nullable|image|max:5120', 
            'img_url'  => 'nullable|string|required_without:img_file',

            // BANNER
            'banner_file' => 'nullable|image|max:10240',
            'banner_url'  => 'nullable|string',

            // TRAILER
            'trailer_file' => 'nullable|mimetypes:video/mp4,video/webm|max:51200',
            'trailer_url'  => 'nullable|string',

            // DOWNLOAD
            'download_file' => 'nullable|file|max:512000',
            'download_url'  => 'nullable|string',
        ]);

        // Request-ээс тусгай талбаруудыг хасаж бэлдэнэ
        $data = $request->except([
            'categories', 
            'img_file', 'img_url', 
            'banner_file', 'banner_url', 
            'trailer_file', 'trailer_url',
            'download_file', 'download_url',
            'screenshots_files'
        ]);

        // --- 1. COVER IMAGE ---
        if ($request->hasFile('img_file')) {
            $path = $request->file('img_file')->store('games/covers', 'public');
            $data['img'] = '/storage/' . $path;
        } elseif ($request->filled('img_url')) {
            // URL цэвэрлэх (хуучин кодоос тань авсан логик)
            $data['img'] = str_replace(['"', "'", "src="], '', $request->img_url);
        }

        // --- 2. BANNER ---
        if ($request->hasFile('banner_file')) {
            $path = $request->file('banner_file')->store('games/banners', 'public');
            $data['banner'] = '/storage/' . $path;
        } elseif ($request->filled('banner_url')) {
            $data['banner'] = str_replace(['"', "'", "src="], '', $request->banner_url);
        }

        // --- 3. TRAILER ---
        if ($request->hasFile('trailer_file')) {
            $path = $request->file('trailer_file')->store('games/trailers', 'public');
            $data['trailer'] = '/storage/' . $path;
        } elseif ($request->filled('trailer_url')) {
            $data['trailer'] = str_replace(['"', "'"], '', $request->trailer_url);
        }

        // --- 4. DOWNLOAD LINK ---
        if ($request->hasFile('download_file')) {
            $path = $request->file('download_file')->store('games/files', 'public');
            $data['download_link'] = '/storage/' . $path;
        } elseif ($request->filled('download_url')) {
            $data['download_link'] = $request->download_url;
        }

        // --- 5. SCREENSHOTS (Олон зураг upload) ---
        $screenshots = [];
        if ($request->hasFile('screenshots_files')) {
            foreach ($request->file('screenshots_files') as $file) {
                $path = $file->store('games/screenshots', 'public');
                $screenshots[] = '/storage/' . $path;
            }
        }
        if (!empty($screenshots)) {
            $data['screenshots'] = $screenshots; // Model дээр casts => 'array' байх хэрэгтэй
        }

        // CREATE GAME
        $game = Game::create($data);

        // ATTACH CATEGORIES
        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }

        return back()->with('success', 'Тоглоом амжилттай нэмэгдлээ!');
    }
}