<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
    </style>
</head>
<body class="bg-dark text-gray-300 font-sans antialiased text-sm min-h-screen flex flex-col">

    <nav class="border-b border-white/5 bg-surface/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-[1600px] mx-auto px-6 h-16 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="text-2xl font-black italic text-white tracking-tighter">Play<span class="text-brand">Vision</span></div>
                <span class="bg-white/5 border border-white/5 text-gray-400 text-xs px-2 py-0.5 rounded uppercase tracking-widest font-bold">Admin</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="/" class="text-xs font-medium text-gray-400 hover:text-white transition-colors">Open Website</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-xs font-bold text-red-500 hover:text-red-400 transition uppercase tracking-wide">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="flex-1 max-w-[1600px] mx-auto w-full p-6 grid grid-cols-1 xl:grid-cols-12 gap-8 items-start">
        
        <div class="xl:col-span-12 flex items-end justify-between border-b border-white/5 pb-4 mb-2">
            <div>
                <h1 class="text-3xl font-bold text-white tracking-tight">Game Management</h1>
                <p class="text-gray-500 text-sm mt-1">Create games, manage categories, and update prices.</p>
            </div>
            <div class="text-right">
                <div class="text-2xl font-mono text-brand font-bold">{{ count($games) }}</div>
                <div class="text-xs text-gray-500 uppercase font-bold tracking-wider">Total Games</div>
            </div>
        </div>
        
        <div class="xl:col-span-4 space-y-6">
            
            @if(session('success'))
                <div class="bg-green-500/10 border-l-4 border-green-500 text-green-400 p-4 rounded-r shadow-lg relative overflow-hidden">
                    <div class="font-bold text-sm">Success</div>
                    <div class="text-xs opacity-80">{{ session('success') }}</div>
                </div>
            @endif

            <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden relative group">
                <div class="h-1 w-full bg-gradient-to-r from-brand to-purple-600"></div>
                <div class="p-6">
                    <h2 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                        <span class="flex items-center justify-center w-6 h-6 rounded bg-brand text-black text-xs font-black">+</span>
                        Add New Game
                    </h2>

                    @if ($errors->any())
                        <div class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded mb-4 text-xs">
                            <ul class="list-disc pl-4">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('admin.category.store') }}" method="POST" class="mb-6 pb-6 border-b border-white/5">
                        @csrf
                        <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Create New Category</label>
                        <div class="flex gap-2">
                            <input type="text" name="name" placeholder="e.g. RPG, FPS..." class="flex-1 bg-black/40 border border-white/10 rounded-lg px-3 py-2 text-sm focus:border-brand focus:outline-none text-white">
                            <button type="submit" class="bg-white/10 text-white px-3 py-2 rounded-lg font-bold text-xs uppercase hover:bg-brand hover:text-black transition">Add</button>
                        </div>
                    </form>
                    <form action="{{ route('admin.game.store') }}" method="POST" class="space-y-5">
                        @csrf
                        
                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Game Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" placeholder="Enter game title..." class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none text-white placeholder-gray-600 transition-all shadow-inner" required>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Price (Text Allowed) <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <input type="text" name="price" placeholder="0 or Тун удахгүй" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white font-mono" required>
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

                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1">Select Categories <span class="text-red-500">*</span></label>
                                <div class="bg-black/40 border border-white/10 rounded-xl p-3 max-h-40 overflow-y-auto custom-scrollbar">
                                    <div class="grid grid-cols-2 gap-2">
                                        @foreach($categories as $cat)
                                            <div class="flex items-center justify-between group p-1 rounded hover:bg-white/5 transition">
                                                <label class="flex items-center space-x-2 cursor-pointer w-full">
                                                    <input type="checkbox" name="categories[]" value="{{ $cat->id }}" class="w-4 h-4 rounded border-gray-600 text-brand focus:ring-brand bg-gray-700">
                                                    <span class="text-xs text-gray-300 group-hover:text-white">{{ $cat->name }}</span>
                                                </label>
                                                
                                                {{-- DELETE CATEGORY BUTTON (Small 'x') --}}
                                                <button form="delete-cat-{{ $cat->id }}" class="text-gray-600 hover:text-red-500 px-1 opacity-0 group-hover:opacity-100 transition">
                                                    ×
                                                </button>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <p class="text-[10px] text-gray-600 mt-1 ml-1">Check multiple categories.</p>
                            </div>

                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Badge Tag</label>
                                <div class="relative">
                                    <select name="tag" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white appearance-none cursor-pointer">
                                        <option value="">No Badge</option>
                                        <option value="Тун удахгүй" class="text-brand font-bold">★ Тун удахгүй (Slider)</option>
                                        <option value="Шинэ" class="text-green-400">Шинэ</option>
                                        <option value="Захиалах" class="text-orange-500">Захиалах</option>
                                        <option value="Дуусж байгаа" class="text-red-400">Дуусж байгаа</option>
                                    </select>
                                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Release Date</label>
                            <div class="relative">
                                <input type="date" name="release_date" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white appearance-none">
                            </div>
                        </div>

                        <div class="space-y-4 pt-2">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Cover Image URL <span class="text-red-500">*</span></label>
                                <input type="text" name="img" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 truncate font-mono" placeholder="https://..." required>
                            </div>
                            <div>
                                <label class="block text-xs text-brand font-semibold mb-1.5 ml-1 flex justify-between">
                                    <span>Banner Image URL (Wide)</span>
                                    <span class="text-[10px] opacity-60 font-normal">Optional</span>
                                </label>
                                <input type="text" name="banner" class="w-full bg-black/40 border border-brand/20 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 truncate font-mono" placeholder="https://...">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">YouTube Trailer</label>
                                <input type="text" name="trailer" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-gray-400 truncate font-mono" placeholder="https://youtube.com/...">
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
                                            <input type="text" 
                                                   name="screenshots[]" 
                                                   class="w-full bg-black/40 border border-white/10 rounded-lg pl-8 pr-3 py-2 text-xs focus:border-brand focus:outline-none text-gray-300 placeholder-gray-700 font-mono transition-colors hover:border-white/20 focus:bg-black/60" 
                                                   placeholder="https://... image link">
                                        </div>
                                    @endfor
                                </div>
                            </div>
                        </div>

                        <div>
                             <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Description</label>
                             <textarea name="description" rows="3" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white resize-none placeholder-gray-700"></textarea>
                        </div>

                        <div class="bg-black/20 p-4 rounded-xl border border-white/5">
                            <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">System Requirements</h3>
                            <div class="grid grid-cols-2 gap-3">
                                <input type="text" name="min_os" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                <input type="text" name="min_cpu" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                <input type="text" name="min_gpu" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                <input type="text" name="min_ram" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                <input type="text" name="min_storage" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none col-span-2">
                            </div>
                        </div>

                        <button type="submit" class="w-full bg-gradient-to-r from-brand to-cyan-600 text-black py-4 rounded-xl font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(0,212,255,0.2)] hover:shadow-[0_0_30px_rgba(0,212,255,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Publish Game
                        </button>
                    </form>

                    @foreach($categories as $cat)
                        <form id="delete-cat-{{ $cat->id }}" action="{{ route('admin.category.destroy', $cat->id) }}" method="POST" onsubmit="return confirm('Delete {{ $cat->name }}?');" class="hidden">
                            @csrf
                            @method('DELETE')
                        </form>
                    @endforeach

                </div>
            </div>
        </div>

        <div class="xl:col-span-8 h-full">
            <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden flex flex-col h-full min-h-[800px]">
                
                <div class="px-6 py-5 border-b border-white/5 flex justify-between items-center bg-white/[0.02]">
                    <div class="flex items-center gap-3">
                        <h2 class="text-lg font-bold text-white">Library</h2>
                        <div class="h-4 w-[1px] bg-gray-700"></div>
                        <input type="text" placeholder="Search games..." class="bg-transparent border-none text-sm focus:ring-0 text-white placeholder-gray-600 w-64">
                    </div>
                    <div class="flex gap-2">
                        <button class="bg-white/5 hover:bg-white/10 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-400 hover:text-white transition">All</button>
                        <button class="bg-transparent hover:bg-white/5 px-3 py-1.5 rounded-lg text-xs font-bold text-gray-500 hover:text-white transition">On Sale</button>
                    </div>
                </div>
                
                <div class="overflow-x-auto flex-1">
                    <table class="w-full text-left border-collapse">
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
                            <tr class="hover:bg-white/[0.02] transition-colors group">
                                <td class="py-4 px-6">
                                    <div class="flex items-start gap-5">
                                        <div class="relative w-12 h-16 shrink-0 rounded-lg overflow-hidden shadow-lg border border-white/10 group-hover:border-brand/50 transition-colors">
                                            <img src="{{ $game->img }}" class="w-full h-full object-cover">
                                        </div>
                                        <div class="pt-1">
                                            <div class="font-bold text-base text-gray-200 group-hover:text-brand transition-colors leading-tight">{{ $game->title }}</div>
                                            
                                            <div class="flex flex-wrap items-center gap-2 mt-2">
                                                @if($game->tag)
                                                    <span class="text-[10px] font-bold px-2 py-0.5 rounded border text-gray-400 bg-gray-500/10 border-gray-500/20">{{ $game->tag }}</span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                
                                <td class="py-4 px-6 align-middle">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($game->categories as $category)
                                            <span class="inline-block bg-white/5 hover:bg-white/10 px-2 py-0.5 rounded text-[10px] font-medium text-gray-300 border border-white/5 transition-colors">
                                                {{ $category->name }}
                                            </span>
                                        @endforeach
                                        
                                        @if($game->categories->isEmpty())
                                            <span class="text-gray-600 text-xs italic">No Category</span>
                                        @endif
                                    </div>
                                </td>

                                <td class="py-4 px-6 align-middle">
                                    <div class="flex flex-col">
                                        @if(is_numeric($game->price) && $game->price == 0)
                                            <span class="text-brand font-bold text-sm tracking-wide">FREE</span>
                                        @elseif(is_numeric($game->price))
                                            <span class="font-mono text-sm text-gray-200">{{ number_format($game->price) }}₮</span>
                                            @if($game->sale_price)
                                                <span class="text-gray-600 line-through text-xs font-mono">{{ number_format($game->sale_price) }}₮</span>
                                            @endif
                                        @else
                                            <span class="font-bold text-sm text-gray-400 uppercase tracking-wide">{{ $game->price }}</span>
                                        @endif
                                    </div>
                                </td>
                                <td class="py-4 px-6 text-right align-middle">
                                    <div class="flex justify-end items-center gap-3 opacity-80 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('admin.game.edit', $game->id) }}" class="p-2 rounded-lg bg-yellow-500/10 text-yellow-500 hover:bg-yellow-500 hover:text-black transition-all border border-yellow-500/20" title="Edit">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                        </a>
                                        <form action="{{ route('admin.game.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete {{ $game->title }}?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="p-2 rounded-lg bg-red-500/10 text-red-500 hover:bg-red-500 hover:text-white transition-all border border-red-500/20" title="Delete">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                            
                            @if(count($games) == 0)
                            <tr>
                                <td colspan="4" class="py-20 text-center text-gray-600">
                                    <div class="text-4xl mb-4 opacity-20">🎮</div>
                                    <p>No games added yet.</p>
                                </td>
                            </tr>
                            @endif
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>
</html>