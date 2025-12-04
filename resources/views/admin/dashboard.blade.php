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
                extend: { colors: { brand: '#00D4FF', dark: '#0f0f0f', surface: '#18181b' } }
            }
        }
    </script>
</head>
<body class="bg-dark text-white font-sans antialiased">

    <nav class="border-b border-white/10 bg-surface/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 h-16 flex items-center justify-between">
            <div class="text-2xl font-black italic">Play<span class="text-brand">Vision</span> <span class="text-sm not-italic font-normal text-gray-400 ml-2">| Admin Dashboard</span></div>
            <div class="flex items-center gap-4">
                <a href="/" class="text-sm hover:text-brand">Vist Site</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="bg-red-600/20 text-red-500 px-4 py-1.5 rounded text-sm font-bold hover:bg-red-600 hover:text-white transition">Log Out</button>
                </form>
            </div>
        </div>
    </nav>

    <div class="max-w-7xl mx-auto px-4 py-10 grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <div class="lg:col-span-3 flex items-center justify-between mb-2">
            <h1 class="text-3xl font-bold uppercase tracking-wide border-l-4 border-brand pl-4">Dashboard</h1>
            <span class="text-gray-500 text-sm font-medium">{{ now()->format('Y-m-d') }}</span>
        </div>
        
        <div class="lg:col-span-1 space-y-8">
            
            @if(session('success'))
                <div class="bg-green-500/10 border border-green-500 text-green-500 p-4 rounded-lg text-sm font-bold">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded-lg text-sm font-bold">
                    <ul class="list-disc pl-5">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- ADD CATEGORY -->
            <div class="bg-surface p-6 rounded-2xl border border-white/5 shadow-xl">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span class="text-brand">#</span> Add Category
                </h2>
                <form action="{{ route('admin.category.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Category Name</label>
                        <input type="text" name="name" placeholder="e.g. RPG, Horror" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white" required>
                    </div>
                    <button type="submit" class="w-full bg-white/5 hover:bg-brand hover:text-black text-white py-3 rounded-lg font-bold transition-all text-sm">SAVE CATEGORY</button>
                </form>
            </div>

            <!-- ADD GAME FORM -->
            <div class="bg-surface p-6 rounded-2xl border border-white/5 shadow-xl">
                <h2 class="text-xl font-bold mb-4 flex items-center gap-2">
                    <span class="text-brand">+</span> Add New Game
                </h2>
                <form action="{{ route('admin.game.store') }}" method="POST" class="space-y-4">
                    @csrf
                    
                    <!-- Basic Info -->
                    <div>
                        <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Game Title</label>
                        <input type="text" name="title" placeholder="e.g. Death Stranding 2" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Price (₮)</label>
                            <input type="number" name="price" placeholder="59000" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white" required>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Sale Price (₮)</label>
                            <input type="number" name="sale_price" placeholder="Optional" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Category</label>
                            <select name="category_id" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white appearance-none">
                                @foreach($categories as $cat)
                                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Tag (Badge)</label>
                            <input type="text" name="tag" placeholder="NEW, HOT" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white">
                        </div>
                    </div>

                    <!-- Images -->
                    <div class="space-y-4 border-t border-white/10 pt-4">
                        <div>
                            <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Cover Image URL (Portrait)</label>
                            <input type="text" name="img" placeholder="https://.../cover.jpg" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white" required>
                        </div>
                        
                        {{-- ЭНД БАННЕР НЭМЛЭЭ --}}
                        <div>
                            <label class="block text-xs text-brand uppercase font-bold mb-1">Banner Image URL (Landscape)</label>
                            <input type="text" name="banner" placeholder="https://.../banner_wide.jpg" class="w-full bg-black/50 border border-brand/50 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white">
                            <p class="text-[10px] text-gray-500 mt-1">Leave empty to use Cover Image as background.</p>
                        </div>
                    </div>
                    
                    <div class="border-t border-white/10 pt-4">
                        <label class="block text-xs text-gray-400 uppercase font-bold mb-1">YouTube Trailer URL</label>
                        <input type="text" name="trailer" placeholder="https://www.youtube.com/watch?v=..." class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white">
                    </div>

                    <div>
                         <label class="block text-xs text-gray-400 uppercase font-bold mb-1">Description</label>
                         <textarea name="description" rows="3" class="w-full bg-black/50 border border-white/10 rounded-lg p-3 text-sm focus:border-brand focus:outline-none text-white"></textarea>
                    </div>

                    <!-- Specs -->
                    <div class="bg-white/5 p-4 rounded-xl border border-white/5">
                        <h3 class="text-xs font-bold text-gray-300 uppercase mb-3 border-b border-white/10 pb-1">System Requirements</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="min_os" placeholder="OS" class="bg-black/50 border border-white/10 rounded p-2 text-xs text-white">
                            <input type="text" name="min_cpu" placeholder="CPU" class="bg-black/50 border border-white/10 rounded p-2 text-xs text-white">
                            <input type="text" name="min_gpu" placeholder="GPU" class="bg-black/50 border border-white/10 rounded p-2 text-xs text-white">
                            <input type="text" name="min_ram" placeholder="RAM" class="bg-black/50 border border-white/10 rounded p-2 text-xs text-white">
                            <input type="text" name="min_storage" placeholder="Storage" class="bg-black/50 border border-white/10 rounded p-2 text-xs text-white col-span-2">
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-brand text-black py-3 rounded-lg font-bold hover:bg-white transition-all text-sm uppercase">Publish Game</button>
                </form>
            </div>
        </div>

        <!-- RIGHT SIDE: LIST -->
        <div class="lg:col-span-2">
            <div class="bg-surface rounded-2xl border border-white/5 shadow-xl overflow-hidden">
                <div class="p-6 border-b border-white/5 flex justify-between items-center">
                    <h2 class="text-xl font-bold">All Games</h2>
                    <span class="bg-white/10 text-xs px-2 py-1 rounded">{{ count($games) }} items</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-black/20 text-gray-400 text-xs uppercase">
                                <th class="p-4">Game</th>
                                <th class="p-4">Category</th>
                                <th class="p-4">Price</th>
                                <th class="p-4 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5 text-sm">
                            @foreach($games as $game)
                            <tr class="hover:bg-white/5 transition-colors group">
                                <td class="p-4 flex items-center gap-3">
                                    <img src="{{ $game->img }}" class="w-10 h-14 object-cover rounded bg-gray-800">
                                    <div>
                                        <div class="font-bold text-white">{{ $game->title }}</div>
                                        @if($game->tag)
                                            <span class="text-[10px] bg-brand/20 text-brand px-1.5 py-0.5 rounded">{{ $game->tag }}</span>
                                        @endif
                                        @if($game->banner)
                                            <div class="text-[10px] text-green-500 mt-1">✓ Banner Added</div>
                                        @endif
                                    </div>
                                </td>
                                <td class="p-4 text-gray-400">{{ $game->category->name }}</td>
                                <td class="p-4">
                                    @if($game->price == 0)
                                        <span class="text-brand font-bold">Free/Soon</span>
                                    @else
                                        {{ number_format($game->price) }}₮
                                        @if($game->sale_price)
                                            <span class="text-gray-500 line-through text-xs ml-1">{{ number_format($game->sale_price) }}₮</span>
                                        @endif
                                    @endif
                                </td>
                                <td class="p-4 text-right">
                                    <form action="{{ route('admin.game.destroy', $game->id) }}" method="POST" onsubmit="return confirm('Are you sure?');">
                                        @csrf
                                        @method('DELETE')
                                        <button class="text-gray-500 hover:text-red-500 transition-colors p-2">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</body>
</html>