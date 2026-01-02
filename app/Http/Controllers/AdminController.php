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
      $request->validate([
            'title'           => 'required',
            'price'           => 'required',
            'sale_price'      => 'nullable|numeric',
            
            // ЗУРАГ: Файл эсвэл URL-ийн аль нэг нь заавал байх ёстой
            'img_file'        => 'nullable|image|max:5120', 
            'img_url'         => 'nullable|url|required_without:img_file', 

            'categories'      => 'required|array',
            'categories.*'    => 'exists:categories,id',
            
            // Бусад (File эсвэл URL)
            'banner_file'     => 'nullable|image|max:10240',
            'banner_url'      => 'nullable|url',
            
            'trailer_file'    => 'nullable|mimetypes:video/mp4,video/webm|max:51200',
            'trailer_url'     => 'nullable|url',
            
            'download_file'   => 'nullable|file|max:512000',
            'download_url' => 'nullable|string',
            
            'tag'             => 'nullable',
            'description'     => 'nullable',
            'developer'       => 'nullable',
            'publisher'       => 'nullable',
            'release_date'    => 'nullable',
            
            // SCREENSHOTS (Олон зураг)
            'screenshots_files.*' => 'image|max:5120',
        ]);

        // Request-ээс файлуудыг хасаж бэлдэнэ
        $data = $request->except([
            'img_file', 'img_url', 
            'banner_file', 'banner_url', 
            'trailer_file', 'trailer_url', 
            'download_file', 'download_url', 
            'categories', 
            'screenshots_files' // <--- Энийг хасах чухал!
        ]);

        // 1. COVER IMAGE
        if ($request->hasFile('img_file')) {
            $path = $request->file('img_file')->store('games/covers', 'public');
            $data['img'] = '/storage/' . $path;
        } elseif ($request->filled('img_url')) {
            $data['img'] = $request->img_url;
        }

        // 2. BANNER
        if ($request->hasFile('banner_file')) {
            $path = $request->file('banner_file')->store('games/banners', 'public');
            $data['banner'] = '/storage/' . $path;
        } elseif ($request->filled('banner_url')) {
            $data['banner'] = $request->banner_url;
        }

        // 3. TRAILER
        if ($request->hasFile('trailer_file')) {
            $path = $request->file('trailer_file')->store('games/trailers', 'public');
            $data['trailer'] = '/storage/' . $path;
        } elseif ($request->filled('trailer_url')) {
            $data['trailer'] = $request->trailer_url;
        }

        // 4. DOWNLOAD LINK
        if ($request->hasFile('download_file')) {
            $path = $request->file('download_file')->store('games/files', 'public');
            $data['download_link'] = '/storage/' . $path;
        } elseif ($request->filled('download_url')) {
            $data['download_link'] = $request->download_url;
        }

        // 5. SCREENSHOTS (ЭНД НЭМЭГДСЭН)
        $screenshots = [];
        if ($request->hasFile('screenshots_files')) {
            foreach ($request->file('screenshots_files') as $file) {
                $path = $file->store('games/screenshots', 'public');
                $screenshots[] = '/storage/' . $path;
            }
        }
        
        // Хэрэв screenshot байвал хадгална
        if (!empty($screenshots)) {
            $data['screenshots'] = $screenshots;
        }

        // Create Game
        $game = Game::create($data);

        // Attach Categories
        if ($request->has('categories')) {
            $game->categories()->attach($request->input('categories'));
        }
        
        return redirect()->back()->with('success', 'Game added successfully!');
    }
}