<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/', function () {
    return view('welcome');
});



Route::get('/', function () {
    // Тоглоомын мэдээллийг энд шууд зарлалаа
    $games = [
        1 => [
            'id' => 1,
            'title' => "The Last of Us Part I",
            'price' => 59.99,
            'sale_price' => 47.99,
            'genre' => "Action",
            'img' => "https://upload.wikimedia.org/wikipedia/en/4/46/Video_Game_Cover_-_The_Last_of_Us.jpg",
            'discount' => "-20%",
            'tag' => null
        ],
        2 => [
            'id' => 2,
            'title' => "Grand Theft Auto V",
            'price' => 29.99,
            'sale_price' => 14.99,
            'genre' => "Open World",
            'img' => "https://upload.wikimedia.org/wikipedia/en/a/a5/Grand_Theft_Auto_V.png",
            'discount' => "-50%",
            'tag' => null
        ],
        3 => [
            'id' => 3,
            'title' => "Death stranding 2",
            'price' => 0,
            'sale_price' => 0,
            'genre' => "Action",
            'img' => "https://static0.gamerantimages.com/wordpress/wp-content/uploads/2023/01/death-stranding-2-chvurches-motion-capture.jpg", 
            'discount' => null,
            'tag' => "COMING SOON"
        ],
        4 => [
            'id' => 4,
            'title' => "Tekken 8",
            'price' => 59.99,
            'sale_price' => null,
            'genre' => "RPG",
            'img' => "https://cdn11.bigcommerce.com/s-k0hjo2yyrq/products/3828/images/5744/PACKSHOT_GAME-T8-Standard_Edition__49027.1723035246.1280.1280.jpg?c=1",
            'discount' => null,
            'tag' => "GOTY"
        ],
        5 => [
            'id' => 5,
            'title' => "Expedition 33",
            'price' => 49.99,
            'sale_price' => 39.99,
            'genre' => "Stealth",
            'img' => "https://cdn1.epicgames.com/spt-assets/330dace5ffc74156987f91d454ac544b/project-w-1kt2x.jpg",
            'discount' => "-20%",
            'tag' => "NEW"
        ],
        6 => [
            'id' => 6,
            'title' => "Silent Hill f",
            'price' => 59.99,
            'sale_price' => null,
            'genre' => "Horror",
            'img' => "https://cdn1.epicgames.com/spt-assets/6d34282a26c544df88ccc57505cdd2f0/silent-hill-f-1q1eg.jpg",
            'discount' => null,
            'tag' => "HOT"
        ],
        7 => [
            'id' => 7,
            'title' => "The Witcher 3",
            'price' => 39.99,
            'sale_price' => 11.99,
            'genre' => "RPG",
            'img' => "https://upload.wikimedia.org/wikipedia/en/0/0c/Witcher_3_cover_art.jpg",
            'discount' => "-70%",
            'tag' => "HOT"
        ],
    ];

    // Welcome руу $games хувьсагчийг явуулж байна
    return view('welcome', ['games' => array_values($games)]);
});

// 2. ДЭЛГЭРЭНГҮЙ ХУУДАС
Route::get('/games/{id}', function ($id) {
    // Тоглоомын дэлгэрэнгүй мэдээллийг энд дахиад бичнэ (Хамгийн найдвартай нь)
    $games = [
       1 => [
            'id' => 1,
            'title' => "The Last of Us Part I",
            'price' => 59.99, 'sale_price' => 47.99, 'genre' => "Action",
            'description' => "Endure and survive. Experience the emotional storytelling.",
            'long_description' => "In a ravaged civilization, where infected and hardened survivors run rampant, Joel, a weary protagonist, is hired to smuggle 14-year-old Ellie out of a military quarantine zone.",
            'img' => "https://upload.wikimedia.org/wikipedia/en/4/46/Video_Game_Cover_-_The_Last_of_Us.jpg",
            'banner' => "https://images.wallpapersden.com/image/download/the-last-of-us-part-1-remake-4k_b2luaWmUmZqaraWkpJRmbmdlrWZlbWU.jpg",
            // ЗӨВ ЛИНК (embed)
            'trailer' => "https://www.youtube.com/embed/WxjeV10H1F0",
            'discount' => "-20%", 'tag' => null, 'developer' => "Naughty Dog", 'publisher' => "Sony"
        ],
        2 => [
            'id' => 2,
            'title' => "Grand Theft Auto V",
            'price' => 29.99, 'sale_price' => 14.99, 'genre' => "Open World",
            'description' => "Experience Rockstar Games critically acclaimed open world game.",
            'long_description' => "When a young street hustler, a retired bank robber and a terrifying psychopath find themselves entangled with some of the most frightening elements of the criminal underworld.",
            'img' => "https://upload.wikimedia.org/wikipedia/en/a/a5/Grand_Theft_Auto_V.png",
            'banner' => "https://images.hdqwalls.com/wallpapers/gta-5-4k-3840x2160-u9.jpg",
            'trailer' => "https://www.youtube.com/embed/QkkoHAzjnUs",
            'discount' => "-50%", 'tag' => null, 'developer' => "Rockstar North", 'publisher' => "Rockstar Games"
        ],
        3 => [
            'id' => 3,
            'title' => "Death strading 2",
            'price' => 0, 'sale_price' => 0, 'genre' => "Action",
            'description' => "Death Stranding 2 — Товч танилцуулга",
            'long_description' => "Хөгжүүлэгч: Kojima Productions, Хэвлэгч: Sony Interactive Entertainment 
Wikipedia
+1

Гарах огноо: анх PS5 платформ дээр 2025 оны 6 р сарын 26 нд гарсан. 
TechRadar
+1

Төрөл: Action-adventure, нэг тоглогчтай (single-player) 
Wikipedia
+1

Өмнөх нь Death Stranding — DS2 нь түүний үргэлжлэл бөгөөд олон талаараа шинэчлэгдсэн.",
            'img' => "https://static0.gamerantimages.com/wordpress/wp-content/uploads/2023/01/death-stranding-2-chvurches-motion-capture.jpg",
            'banner' =>     "https://i.ytimg.com/vi/di51fb3ATng/maxresdefault.jpg",
            'trailer' =>  "https://www.youtube.com/embed/wbLstJHlC4U",
            'discount' => null, 'tag' => "COMING SOON", 'developer' => "Rockstar Games", 'publisher' => "Rockstar Games"
        ],
        4 => [
            'id' => 4,
            'title' => "Tekken 8",
            'price' => 59.99, 'sale_price' => null, 'genre' => "RPG",
            'description' => "THE NEW FANTASY ACTION RPG.",
            'long_description' => "Rise, Tarnished, and be guided by grace to brandish the power of the Elden Ring and become an Elden Lord in the Lands Between.",
            'img' => "https://upload.wikimedia.org/wikipedia/en/b/b9/Elden_Ring_Box_Art.jpg",
            'banner' => "https://images.hdqwalls.com/wallpapers/elden-ring-2022-game-4k-h2.jpg",
            'trailer' => "https://www.youtube.com/embed/E3Huy2cdih0",
            'discount' => null, 'tag' => "GOTY", 'developer' => "FromSoftware", 'publisher' => "Bandai Namco"
        ],
        5 => [
            'id' => 5,
            'title' => "Expedition 33",
            'price' => 49.99, 'sale_price' => 39.99, 'genre' => "Stealth",
            'description' => "Lead the Expedition to destroy the Paintress.",
            'long_description' => "Clair Obscur: Expedition 33 is a reactive turn-based RPG inspired by Belle Époque France.",
            'img' => "https://cdn1.epicgames.com/spt-assets/330dace5ffc74156987f91d454ac544b/project-w-1kt2x.jpg",
            'banner' => "https://images8.alphacoders.com/133/1330663.jpeg",
            'trailer' => "https://www.youtube.com/embed/O8g_kZqP_Rs",
            'discount' => "-20%", 'tag' => "NEW", 'developer' => "Sandfall", 'publisher' => "Kepler"
        ],
        6 => [
            'id' => 6,
            'title' => "Silent Hill f",
            'price' => 59.99, 'sale_price' => null, 'genre' => "Horror",
            'description' => "A completely new story set in 1960s Japan.",
            'long_description' => "Silent Hill f is an upcoming survival horror game developed by NeoBards Entertainment. The story is set in 1960s Japan.",
            'img' => "https://cdn1.epicgames.com/spt-assets/6d34282a26c544df88ccc57505cdd2f0/silent-hill-f-1q1eg.jpg",
            'banner' => "https://images.alphacoders.com/133/1335393.jpeg",
            'trailer' => "https://www.youtube.com/embed/r12w4iR-v8w",
            'discount' => null, 'tag' => "HOT", 'developer' => "NeoBards", 'publisher' => "Konami"
        ],
        7 => [
            'id' => 7,
            'title' => "The Witcher 3",
            'price' => 39.99, 'sale_price' => 11.99, 'genre' => "RPG",
            'description' => "You are Geralt of Rivia, mercenary monster slayer.",
            'long_description' => "The Witcher: Wild Hunt is a story-driven open world RPG set in a visually stunning fantasy universe.",
            'img' => "https://upload.wikimedia.org/wikipedia/en/0/0c/Witcher_3_cover_art.jpg",
            'banner' => "https://images.hdqwalls.com/wallpapers/the-witcher-3-wild-hunt-game-4k-9p.jpg",
            'trailer' => "https://www.youtube.com/embed/XHrskkHf958",
            'discount' => "-70%", 'tag' => "HOT", 'developer' => "CD PROJEKT", 'publisher' => "CD PROJEKT"
        ],
    ];

    if (!array_key_exists($id, $games)) {
        abort(404);
    }

    return view('game', ['game' => $games[$id], 'id' => $id]);
});

// БУСАД ROUTES
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');    
require __DIR__.'/admin-auth.php';
require __DIR__.'/auth.php';
