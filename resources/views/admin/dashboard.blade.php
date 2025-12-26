<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Admin Dashboard - PlayVision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: { 
                    colors: { brand: '#00D4FF', dark: '#0a0a0a', surface: '#121212', card: '#1c1c1c' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: #121212; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
        .custom-scrollbar::-webkit-scrollbar { width: 4px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
        .pb-safe { padding-bottom: env(safe-area-inset-bottom); }
    </style>
</head>
<body class="bg-dark text-gray-300 font-sans antialiased text-sm min-h-screen flex flex-col pb-safe">

    {{-- NAVBAR --}}
    <nav class="border-b border-white/5 bg-surface/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-4 md:px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4 md:gap-8">
                <div class="flex items-center gap-3 md:gap-4">
                    <div class="text-xl md:text-2xl font-black italic text-white tracking-tighter">Play<span class="text-brand">Vision</span></div>
                    <span class="bg-white/5 border border-white/5 text-gray-400 text-[10px] md:text-xs px-2 py-0.5 rounded uppercase tracking-widest font-bold">Admin</span>
                </div>
                
                <div class="hidden md:flex gap-1 bg-white/5 p-1 rounded-lg">
                    <a href="{{ route('admin.dashboard') }}" class="px-4 py-1.5 rounded-md bg-brand text-black font-bold text-xs uppercase shadow-lg">Games</a>
                    <a href="{{ route('admin.orders.index') }}" class="px-4 py-1.5 rounded-md text-gray-400 hover:text-white font-bold text-xs uppercase hover:bg-white/5 transition-all">Orders</a>
                </div>
            </div>

            <div class="flex items-center gap-4 md:gap-6">
                <a href="/" class="text-xs font-medium text-gray-400 hover:text-white transition-colors flex items-center gap-2 hidden md:flex">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                    Open Website
                </a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-xs font-bold text-red-500 hover:text-red-400 transition uppercase tracking-wide border border-red-500/20 hover:border-red-500/50 px-3 py-1.5 rounded-lg">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    {{-- MAIN CONTENT --}}
    <div class="flex-1 max-w-[1600px] mx-auto w-full p-4 md:p-6 grid grid-cols-1 xl:grid-cols-12 gap-6 md:gap-8 items-start">
        
        {{-- HEADER & STATS --}}
        <div class="xl:col-span-12 flex flex-col md:flex-row md:items-end justify-between border-b border-white/5 pb-4 mb-2 gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-white tracking-tight">Game Management</h1>
                <p class="text-gray-500 text-xs md:text-sm mt-1">Create games, manage categories, and update prices.</p>
            </div>
            
            <div class="grid grid-cols-3 gap-4 w-full md:w-auto">
                <div class="text-center md:text-right bg-white/5 p-2 rounded-lg md:bg-transparent md:p-0">
                    <div class="text-lg md:text-2xl font-mono text-green-500 font-bold">{{ $games->filter(fn($g) => is_numeric($g->price) && $g->price == 0)->count() }}</div>
                    <div class="text-[10px] md:text-xs text-gray-500 uppercase font-bold tracking-wider">Free</div>
                </div>
                <div class="text-center md:text-right bg-white/5 p-2 rounded-lg md:bg-transparent md:p-0">
                    <div class="text-lg md:text-2xl font-mono text-brand font-bold">{{ count($games) }}</div>
                    <div class="text-[10px] md:text-xs text-gray-500 uppercase font-bold tracking-wider">Games</div>
                </div>
                <div class="text-center md:text-right bg-white/5 p-2 rounded-lg md:bg-transparent md:p-0">
                    <div class="text-lg md:text-2xl font-mono text-purple-500 font-bold">{{ count($categories) }}</div>
                    <div class="text-[10px] md:text-xs text-gray-500 uppercase font-bold tracking-wider">Cats</div>
                </div>
            </div>
        </div>
        
        {{-- LEFT SIDE: FORMS --}}
        <div class="xl:col-span-4 space-y-6 order-2 xl:order-1">
            
            @if(session('success'))
                <div class="bg-green-500/10 border-l-4 border-green-500 text-green-400 p-4 rounded-r shadow-lg relative overflow-hidden animate-pulse">
                    <div class="font-bold text-sm">Success</div>
                    <div class="text-xs opacity-80">{{ session('success') }}</div>
                </div>
            @endif

            <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden relative group">
                <div class="h-1 w-full bg-gradient-to-r from-brand to-purple-600"></div>
                <div class="p-5 md:p-6">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded bg-brand text-black text-xs font-black">+</span> Add New Game
                    </h2>

                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded mb-4 text-xs">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error) <li>{{ $error }}</li> @endforeach
                            </ul>
                        </div>
                    @endif

                    {{-- CATEGORY ADD --}}
                    <form action="{{ route('categories.store') }}" method="POST" class="mb-6 pb-6 border-b border-white/5">
                        @csrf
                        <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Create New Category</label>
                        <div class="flex gap-2">
                            <input type="text" name="name" placeholder="e.g. RPG..." class="flex-1 bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-brand focus:outline-none text-white">
                            <button type="submit" class="bg-white/10 text-white px-3 py-2 rounded-lg font-bold text-xs uppercase hover:bg-brand hover:text-black transition">Add</button>
                        </div>
                    </form>

                    {{-- GAME ADD --}}
                    <form action="{{ route('admin.game.store') }}" method="POST" class="space-y-5">
                        @csrf
                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Game Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" placeholder="Enter game title..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none text-white transition-all shadow-inner" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Price <span class="text-red-500" id="priceReq">*</span></label>
                                <div class="relative">
                                    <input type="text" id="priceInput" name="price" placeholder="0 or Free" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white font-mono" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Sale Price</label>
                                <div class="relative">
                                    <input type="number" name="sale_price" placeholder="Optional" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white font-mono">
                                    <span class="absolute right-4 top-3 text-gray-600 text-xs">₮</span>
                                </div>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-3">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Developer</label>
                                <input type="text" name="developer" value="PlayVision" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 font-bold">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Publisher</label>
                                <input type="text" name="publisher" value="PlayVision" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 font-bold">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Release Date</label>
                                <input type="date" name="release_date" class="w-full bg-black/40 border border-white/10 rounded-xl px-3 py-3 text-xs focus:border-brand focus:outline-none text-white uppercase tracking-wider">
                            </div>
                        </div>

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1">Select Categories <span class="text-red-500">*</span></label>
                                <div class="relative mb-2">
                                    <input type="text" id="catSearch" placeholder="Search categories..." class="w-full bg-black/40 border border-white/10 rounded-lg pl-8 pr-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <div class="absolute left-2.5 top-2 text-gray-600"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg></div>
                                </div>
                                <div class="bg-black/40 border border-white/10 rounded-xl p-3 max-h-40 overflow-y-auto custom-scrollbar">
                                    <div class="grid grid-cols-2 gap-2" id="categoryList">
                                        @foreach($categories as $cat)
                                            <div class="flex items-center justify-between group p-1 rounded hover:bg-white/5 transition cat-item">
                                                <label class="flex items-center space-x-2 cursor-pointer w-full select-none">
                                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="w-4 h-4 rounded border-gray-600 text-brand focus:ring-brand bg-gray-700">
                                                    <span class="text-xs text-gray-300 group-hover:text-white cat-name">{{ $cat->name }}</span>
                                                </label>
                                                <button form="delete-cat-{{ $cat->id }}" class="text-gray-600 hover:text-red-500 px-1 opacity-0 group-hover:opacity-100 transition" title="Delete Category">×</button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Game Tag</label>
                                <div class="relative">
                                    <select name="tag" id="gameTag" onchange="checkPriceRequirement()" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white appearance-none cursor-pointer">
                                        <option value="">No Tag</option>
                                        <optgroup label="Basic">
                                            <option value="New" class="text-green-400">🔥 New</option>
                                            <option value="ComingSoon" class="text-gray-400">🚀 Coming Soon</option>
                                            <option value="FreeGame" class="text-green-500 font-bold">🎁 Free Game</option>
                                            <option value="Sale" class="text-red-400">🏷️ On Sale</option>
                                        </optgroup>
                                        <optgroup label="Awards & Trending">
                                            <option value="Trending" class="text-orange-400">⚡ Trending</option>
                                            <option value="BestSelling" class="text-blue-400">💎 Best Selling</option>
                                            <option value="GOTY" class="text-yellow-400 font-bold">🏆 GOTY</option>
                                            <option value="EditorsChoice" class="text-purple-400">🎖️ Editor's Choice</option>
                                        </optgroup>
                                        <optgroup label="Other">
                                            <option value="EarlyAccess" class="text-teal-400">🛠️ Early Access</option>
                                            <option value="PreOrder" class="text-indigo-400">📦 Pre-Order</option>
                                        </optgroup>
                                    </select>
                                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                </div>
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Cover Image URL <span class="text-red-500">*</span></label>
                                <input type="text" name="img" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 truncate font-mono" placeholder="https://..." required>
                            </div>
                            <div>
                                <label class="block text-xs text-brand font-semibold mb-1.5 ml-1">Banner Image URL (Wide)</label>
                                <input type="text" name="banner" class="w-full bg-black/40 border border-brand/20 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 truncate font-mono" placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Download Link</label>
                                <input type="text" name="download_link" class="w-full bg-black/40 border border-green-500/30 rounded-xl px-4 py-3 text-xs focus:border-green-500 focus:outline-none text-green-300 truncate font-mono" placeholder="https://drive.google.com/...">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">YouTube Trailer</label>
                                <input type="text" name="trailer" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-gray-400 truncate font-mono" placeholder="https://youtube.com/...">
                            </div>
                        </div>

                        <div class="border-t border-white/5 pt-4 mt-2">
                            <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1 flex justify-between items-center">
                                <span>Game Screenshots (Max 15)</span>
                                <span class="text-[10px] text-gray-600 font-normal">Scroll to see all</span>
                            </label>
                            <div class="grid grid-cols-1 gap-3 max-h-[200px] overflow-y-auto pr-2 custom-scrollbar bg-black/20 p-2 rounded-lg border border-white/5">
                                @for($i = 1; $i <= 15; $i++)
                                    <div class="relative group">
                                        <span class="absolute left-3 top-2.5 text-[10px] text-gray-600 font-mono font-bold select-none">#{{ $i }}</span>
                                        <input type="text" name="screenshots[]" class="w-full bg-black/40 border border-white/10 rounded-lg pl-8 pr-3 py-2 text-xs focus:border-brand focus:outline-none text-gray-300 placeholder-gray-700 font-mono transition-colors hover:border-white/20 focus:bg-black/60" placeholder="https://... image link">
                                    </div>
                                @endfor
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Description</label>
                            <textarea name="description" rows="3" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white resize-none placeholder-gray-700"></textarea>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-black/20 p-4 rounded-xl border border-white/5 relative group">
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Хамгийн бага (Minimum)</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="min_os" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="min_cpu" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="min_gpu" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="min_ram" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="min_storage" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none col-span-2 placeholder-gray-600">
                                </div>
                            </div>

                            <div class="bg-black/20 p-4 rounded-xl border border-white/5 relative group">
                                <h3 class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3">Зөвлөмжит (Recommended)</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="rec_os" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="rec_cpu" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="rec_gpu" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="rec_ram" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-600">
                                    <input type="text" name="rec_storage" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none col-span-2 placeholder-gray-600">
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="w-full bg-gradient-to-r from-brand to-cyan-600 text-black py-4 rounded-xl font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(0,212,255,0.2)] hover:shadow-[0_0_30px_rgba(0,212,255,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all mb-10 md:mb-0">
                            Publish Game
                        </button>
                    </form>

                    @foreach($categories as $cat)
                        <form id="delete-cat-{{ $cat->id }}" action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete {{ $cat->name }}?');" class="hidden">
                            @csrf @method('DELETE')
                        </form>
                    @endforeach

                </div>
            </div>
        </div>

        {{-- RIGHT SIDE: GAME LIST --}}
        <div class="xl:col-span-8 h-full order-1 xl:order-2">
            <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden flex flex-col h-full min-h-[500px] md:min-h-[800px]">
                <div class="px-4 md:px-6 py-5 border-b border-white/5 flex flex-col md:flex-row justify-between items-start md:items-center bg-white/[0.02] gap-4 md:gap-0">
                    <div class="flex items-center gap-3 w-full md:w-auto">
                        <h2 class="text-lg font-bold text-white whitespace-nowrap">Library</h2>
                        <div class="h-4 w-[1px] bg-gray-700"></div>
                        <input type="text" id="gameSearch" placeholder="Search games..." class="bg-transparent border-none text-sm focus:ring-0 text-white placeholder-gray-600 w-full md:w-64" onkeyup="filterGames()">
                    </div>
                    <div class="flex gap-2 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                        <button onclick="filterType('all')" class="filter-btn bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-400 hover:text-white transition active whitespace-nowrap">All</button>
                        <button onclick="filterType('free')" class="filter-btn bg-green-500/10 hover:bg-green-500/20 px-3 py-1.5 rounded-lg text-xs font-bold text-green-500 transition whitespace-nowrap">Free Games</button>
                    </div>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse min-w-[800px]" id="gamesTable">
                        <thead>
                            <tr class="bg-black/40 border-b border-white/5">
                                <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest w-[45%]">Game Details</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Categories</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest">Price</th>
                                <th class="py-4 px-6 text-[10px] font-bold text-gray-500 uppercase tracking-widest text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @foreach($games as $game)
                            <tr class="hover:bg-white/[0.02] transition-colors group game-row" data-price="{{ $game->price }}">
                                <td class="py-4 px-6">
                                    <div class="flex items-start gap-5">
                                        <div class="relative w-12 h-16 shrink-0 rounded-lg overflow-hidden shadow-lg border border-white/10 group-hover:border-brand/50 transition-colors">
                                            <img src="{{ $game->img }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="pt-1">
                                            <div class="font-bold text-base text-gray-200 group-hover:text-brand transition-colors leading-tight game-title">{{ $game->title }}</div>
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                @if($game->tag)
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded border bg-white/5 text-gray-400 border-white/10 uppercase">{{ $game->tag }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($game->categories as $category)
                                            <span class="inline-block bg-white/5 hover:bg-white/10 px-2 py-0.5 rounded text-[10px] font-medium text-gray-300 border border-white/5 transition-colors">{{ $category->name }}</span>
                                        @endforeach
                                    </div>
                                </td>
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex flex-col">
                                        @if(is_numeric($game->price) && $game->price == 0)
                                            <span class="text-green-500 font-bold text-sm tracking-wide">FREE</span>
                                        @else
                                            <span class="font-mono text-sm text-gray-200">{{ number_format((float)$game->price) }}₮</span>
                                            @if($game->sale_price)
                                                <span class="text-gray-600 line-through text-xs font-mono">{{ number_format((float)$game->sale_price) }}₮</span>
                                            @endif
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right align-middle">
                                    <div class="flex justify-end items-center gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.game.edit', $game->id) }}" class="p-2 rounded-lg bg-yellow-500/10 text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all border border-yellow-500/20" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.game.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Delete {{ $game->title }}?');">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all border border-red-500/20">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        // --- AUTO PRICE DISABLE SCRIPT ---
        function checkPriceRequirement() {
            const tag = document.getElementById('gameTag').value;
            const priceInput = document.getElementById('priceInput');
            const priceReq = document.getElementById('priceReq');

            if (tag === 'ComingSoon' || tag === 'Тун удахгүй' || tag === 'EarlyAccess' || tag === 'PreOrder') {
                // If it's a special tag, price is optional
                priceInput.removeAttribute('required');
                priceReq.style.display = 'none'; // Hide asterisk
                if (tag === 'ComingSoon' || tag === 'Тун удахгүй') {
                     // For pure Coming Soon, we can even set placeholder
                     priceInput.placeholder = "Not Available Yet";
                }
            } else {
                // Otherwise, require price
                priceInput.setAttribute('required', 'required');
                priceReq.style.display = 'inline'; // Show asterisk
            }
        }

        // --- FILTER SCRIPT ---
        function filterGames() {
            var input, filter, table, tr, td, i, txtValue;
            input = document.getElementById("gameSearch");
            filter = input.value.toUpperCase();
            table = document.getElementById("gamesTable");
            tr = table.getElementsByTagName("tr");
            for (i = 0; i < tr.length; i++) {
                td = tr[i].querySelector(".game-title");
                if (td) {
                    txtValue = td.textContent || td.innerText;
                    if (txtValue.toUpperCase().indexOf(filter) > -1) {
                        tr[i].style.display = "";
                    } else {
                        tr[i].style.display = "none";
                    }
                }       
            }
        }

        function filterType(type) {
            const btns = document.querySelectorAll('.filter-btn');
            btns.forEach(btn => {
                if(btn.innerText.toLowerCase().includes(type == 'all' ? 'all' : 'free')) {
                    btn.classList.add('bg-white/10', 'text-white');
                    btn.classList.remove('bg-white/5', 'text-gray-400');
                } else {
                    btn.classList.remove('bg-white/10', 'text-white');
                    btn.classList.add('bg-white/5', 'text-gray-400');
                }
            });

            const rows = document.querySelectorAll('.game-row');
            rows.forEach(row => {
                const price = row.getAttribute('data-price');
                if (type === 'all') {
                    row.style.display = '';
                } else if (type === 'free') {
                    if (price == 0) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                }
            });
        }

        // CATEGORY SEARCH SCRIPT
        const catSearch = document.getElementById('catSearch');
        if(catSearch) {
            catSearch.addEventListener('keyup', function() {
                let filter = this.value.toLowerCase();
                let items = document.querySelectorAll('.cat-item');

                items.forEach(item => {
                    let text = item.querySelector('.cat-name').textContent.toLowerCase();
                    if(text.includes(filter)) {
                        item.style.display = "";
                    } else {
                        item.style.display = "none";
                    }
                });
            });
        }
    </script>
</body>
</html>