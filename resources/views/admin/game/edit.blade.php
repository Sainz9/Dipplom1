<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game - {{ $game->title }} | PlayVision Admin</title>
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
<body class="bg-dark text-gray-300 font-sans antialiased min-h-screen flex flex-col items-center py-10">

    <div class="max-w-4xl w-full px-6">
        
        {{-- HEADER & BACK BUTTON --}}
        <div class="flex justify-between items-center mb-6">
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 text-gray-500 hover:text-brand transition-colors text-xs font-bold uppercase tracking-widest">
                <div class="w-8 h-8 rounded-full bg-white/5 group-hover:bg-brand group-hover:text-black flex items-center justify-center transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                </div>
                Back to Dashboard
            </a>
            <div class="text-right">
                <div class="text-2xl font-black italic text-white tracking-tighter">Play<span class="text-brand">Vision</span></div>
                <div class="text-[10px] text-gray-600 uppercase font-bold tracking-widest">Editor Mode</div>
            </div>
        </div>

        {{-- MAIN CARD --}}
        <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden relative">
            <div class="h-1 w-full bg-gradient-to-r from-brand to-purple-600"></div>

            <div class="p-8">
                <div class="flex justify-between items-start mb-8 border-b border-white/5 pb-6">
                    <div>
                        <h1 class="text-3xl font-bold text-white tracking-tight">Edit Game Details</h1>
                        <p class="text-gray-500 text-sm mt-1">Updating ID: <span class="text-brand font-mono">#{{ $game->id }}</span></p>
                    </div>
                    {{-- CURRENT COVER PREVIEW --}}
                    <div class="relative w-20 h-28 rounded-lg overflow-hidden border border-white/10 shadow-lg group">
                        <img src="{{ $game->img }}" class="w-full h-full object-cover opacity-80 group-hover:opacity-100 transition">
                        @if($game->tag)
                            <div class="absolute bottom-0 left-0 w-full bg-brand text-black text-[8px] font-bold text-center py-0.5 uppercase">
                                {{ $game->tag }}
                            </div>
                        @endif
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500 text-red-500 p-4 rounded-xl mb-6 text-sm">
                        <ul class="list-disc pl-4 space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.game.update', $game->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- 1. BASIC INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Game Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $game->title) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none text-white transition-all shadow-inner" required>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Price (Text Allowed) <span class="text-red-500">*</span></label>
                            <input type="text" name="price" value="{{ old('price', $game->price) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white font-mono" required>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Sale Price</label>
                            <div class="relative">
                                <input type="number" name="sale_price" value="{{ old('sale_price', $game->sale_price) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white font-mono">
                                <span class="absolute right-4 top-3 text-gray-600 text-xs">₮</span>
                            </div>
                        </div>
                    </div>

                    {{-- 2. CATEGORIES & TAGS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        {{-- Categories Checkbox --}}
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1">Categories</label>
                            <div class="bg-black/40 border border-white/10 rounded-xl p-3 max-h-40 overflow-y-auto custom-scrollbar">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($categories as $cat)
                                        <div class="flex items-center group p-1 rounded hover:bg-white/5 transition">
                                            <label class="flex items-center space-x-2 cursor-pointer w-full">
                                                <input type="checkbox" name="categories[]" value="{{ $cat->id }}" 
                                                    class="w-4 h-4 rounded border-gray-600 text-brand focus:ring-brand bg-gray-700"
                                                    {{ in_array($cat->id, $game->categories->pluck('id')->toArray()) ? 'checked' : '' }}>
                                                <span class="text-xs text-gray-300 group-hover:text-white">{{ $cat->name }}</span>
                                            </label>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Tag & Date --}}
                        <div class="space-y-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Badge Tag</label>
                                <div class="relative">
                                    <select name="tag" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white appearance-none cursor-pointer">
                                        <option value="" {{ $game->tag == '' ? 'selected' : '' }}>No Badge</option>
                                        <option value="Тун удахгүй" class="text-brand font-bold" {{ $game->tag == 'Тун удахгүй' ? 'selected' : '' }}>★ Тун удахгүй</option>
                                        <option value="Шинэ" class="text-green-400" {{ $game->tag == 'Шинэ' ? 'selected' : '' }}>Шинэ</option>
                                        <option value="Захиалах" class="text-orange-500" {{ $game->tag == 'Захиалах' ? 'selected' : '' }}>Захиалах</option>
                                        <option value="Дуусж байгаа" class="text-red-400" {{ $game->tag == 'Дуусж байгаа' ? 'selected' : '' }}>Дуусж байгаа</option>
                                    </select>
                                    <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg></div>
                                </div>
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Release Date</label>
                                <input type="date" name="release_date" value="{{ old('release_date', $game->release_date) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white">
                            </div>
                        </div>
                    </div>

                    {{-- 3. MEDIA LINKS --}}
                    <div class="space-y-4 pt-4 border-t border-white/5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Cover Image URL <span class="text-red-500">*</span></label>
                                <input type="text" name="img" value="{{ old('img', $game->img) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 truncate font-mono" required>
                            </div>
                            <div>
                                <label class="block text-xs text-brand font-semibold mb-1.5 ml-1">Banner Image URL</label>
                                <input type="text" name="banner" value="{{ old('banner', $game->banner) }}" class="w-full bg-black/40 border border-brand/20 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-brand/80 truncate font-mono">
                            </div>
                        </div>
                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">YouTube Trailer URL</label>
                            <input type="text" name="trailer" value="{{ old('trailer', $game->trailer) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-brand focus:outline-none text-gray-400 truncate font-mono">
                        </div>
                    </div>

                    {{-- 4. SCREENSHOTS (1-15) --}}
                    <div>
                        <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1 flex justify-between items-center">
                            <span>Game Screenshots (Max 15)</span>
                            <span class="text-[10px] text-gray-600 font-normal">Edit existing or add new</span>
                        </label>
                        <div class="grid grid-cols-1 gap-3 max-h-[200px] overflow-y-auto pr-2 custom-scrollbar bg-black/20 p-2 rounded-lg border border-white/5">
                            @php
                                $currentScreenshots = is_array($game->screenshots) ? $game->screenshots : (json_decode($game->screenshots, true) ?? []);
                            @endphp
                            @for($i = 0; $i < 15; $i++)
                                <div class="relative group">
                                    <span class="absolute left-3 top-2.5 text-[10px] text-gray-600 font-mono font-bold select-none">#{{ $i + 1 }}</span>
                                    <input type="text" 
                                           name="screenshots[]" 
                                           value="{{ $currentScreenshots[$i] ?? '' }}"
                                           class="w-full bg-black/40 border border-white/10 rounded-lg pl-8 pr-3 py-2 text-xs focus:border-brand focus:outline-none text-gray-300 placeholder-gray-700 font-mono transition-colors hover:border-white/20 focus:bg-black/60" 
                                           placeholder="https://...">
                                </div>
                            @endfor
                        </div>
                    </div>

                    {{-- 5. DESCRIPTION --}}
                    <div>
                        <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white resize-none custom-scrollbar">{{ old('description', $game->description) }}</textarea>
                    </div>

                    {{-- 6. SYSTEM REQUIREMENTS --}}
                    <div class="bg-black/20 p-5 rounded-xl border border-white/5">
                        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">System Requirements</h3>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <input type="text" name="min_os" value="{{ old('min_os', $game->min_os) }}" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                            <input type="text" name="min_cpu" value="{{ old('min_cpu', $game->min_cpu) }}" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                            <input type="text" name="min_gpu" value="{{ old('min_gpu', $game->min_gpu) }}" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                            <input type="text" name="min_ram" value="{{ old('min_ram', $game->min_ram) }}" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                            <input type="text" name="min_storage" value="{{ old('min_storage', $game->min_storage) }}" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none col-span-2 md:col-span-4">
                        </div>
                    </div>

                    {{-- ACTIONS --}}
                    <div class="flex gap-4 pt-4 border-t border-white/5">
                        <a href="{{ route('admin.dashboard') }}" class="w-1/3 bg-white/5 text-gray-400 py-4 rounded-xl font-bold text-sm uppercase text-center hover:bg-white/10 hover:text-white transition-all flex items-center justify-center">
                            Cancel
                        </a>
                        <button type="submit" class="w-2/3 bg-gradient-to-r from-brand to-cyan-600 text-black py-4 rounded-xl font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(0,212,255,0.2)] hover:shadow-[0_0_30px_rgba(0,212,255,0.4)] hover:scale-[1.02] active:scale-[0.98] transition-all">
                            Update Game
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>

</body>
</html>