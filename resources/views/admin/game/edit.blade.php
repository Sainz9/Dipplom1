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

                <form action="{{ route('admin.game.update', $game->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- 1. BASIC INFO --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Game Title <span class="text-red-500">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $game->title) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none text-white transition-all shadow-inner" required>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Price <span class="text-red-500">*</span></label>
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

                    {{-- 2. MEDIA (URL ONLY) --}}
                    <div class="space-y-4 pt-2">
                        
                        {{-- Cover Image --}}
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <label class="block text-xs text-gray-400 font-semibold mb-2">1. Cover Image (Босоо) - URL <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-[10px] text-brand font-bold">LINK:</span>
                                <input type="text" name="img_url" value="{{ $game->img }}" placeholder="https://image.com/cover.jpg" class="w-full bg-black/40 border border-white/10 rounded-lg pl-12 pr-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-700">
                            </div>
                        </div>

                        {{-- Banner Image --}}
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <label class="block text-xs text-gray-400 font-semibold mb-2">2. Banner Image (Хэвтээ) - URL</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-[10px] text-brand font-bold">LINK:</span>
                                <input type="text" name="banner_url" value="{{ $game->banner }}" placeholder="https://image.com/banner.jpg" class="w-full bg-black/40 border border-white/10 rounded-lg pl-12 pr-3 py-2 text-xs text-white focus:border-brand outline-none placeholder-gray-700">
                            </div>
                        </div>

                        {{-- Game File --}}
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <label class="block text-xs text-green-500 font-semibold mb-2">3. Game Download Link</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-[10px] text-green-500 font-bold">URL:</span>
                                <input type="text" name="download_url" value="{{ $game->download_link }}" placeholder="Google Drive Link..." class="w-full bg-black/40 border border-white/10 rounded-lg pl-12 pr-3 py-2 text-xs text-white focus:border-green-500 outline-none placeholder-gray-700">
                            </div>
                        </div>

                        {{-- Trailer --}}
                        <div class="p-3 bg-white/5 rounded-xl border border-white/10">
                            <label class="block text-xs text-red-500 font-semibold mb-2">4. YouTube Trailer Link</label>
                            <div class="relative">
                                <span class="absolute left-3 top-2.5 text-[10px] text-red-500 font-bold">URL:</span>
                                <input type="text" name="trailer_url" value="{{ $game->trailer }}" placeholder="YouTube Embed Link..." class="w-full bg-black/40 border border-white/10 rounded-lg pl-12 pr-3 py-2 text-xs text-white focus:border-red-500 outline-none placeholder-gray-700">
                            </div>
                        </div>
                    </div>

                    <div class="space-y-4">
                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1">Categories</label>
                            <div class="bg-black/40 border border-white/10 rounded-xl p-3 max-h-40 overflow-y-auto custom-scrollbar">
                                <div class="grid grid-cols-2 sm:grid-cols-3 gap-2">
                                    @foreach($categories as $cat)
                                        <div class="flex items-center group p-1 rounded hover:bg-white/5 transition">
                                            <label class="flex items-center space-x-2 cursor-pointer w-full select-none">
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

                        <div>
                            <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Game Status / Badge</label>
                            <div class="relative">
                                <select name="tag" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white appearance-none cursor-pointer">
                                    <option value="">Сонгоогүй (No Badge)</option>
                                    
                                    <optgroup label="Үндсэн төлөв">
                                        <option value="Шинэ" {{ $game->tag == 'Шинэ' ? 'selected' : '' }} class="text-green-400">🔥 Шинэ (New Release)</option>
                                        <option value="Тун удахгүй" {{ $game->tag == 'Тун удахгүй' ? 'selected' : '' }} class="text-gray-400">🚀 Тун удахгүй (Coming Soon)</option>
                                        <option value="FreeGame" {{ $game->tag == 'FreeGame' ? 'selected' : '' }} class="text-green-500 font-bold">🎁 Үнэгүй (Free to Play)</option>
                                        <option value="Хямдралтай" {{ $game->tag == 'Хямдралтай' ? 'selected' : '' }} class="text-red-400">🏷️ Хямдралтай (On Sale)</option>
                                    </optgroup>

                                    <optgroup label="Эрэлт & Шагнал">
                                        <option value="Trending" {{ $game->tag == 'Trending' ? 'selected' : '' }} class="text-orange-400">⚡ Эрэлттэй (Trending)</option>
                                        <option value="BestSelling" {{ $game->tag == 'BestSelling' ? 'selected' : '' }} class="text-blue-400">💎 Шилдэг борлуулалт (Top Seller)</option>
                                        <option value="GOTY" {{ $game->tag == 'GOTY' ? 'selected' : '' }} class="text-yellow-400 font-bold">🏆 Оны шилдэг (Game of the Year)</option>
                                        <option value="EditorsChoice" {{ $game->tag == 'EditorsChoice' ? 'selected' : '' }} class="text-purple-400">🎖️ Редакторын онцлох (Editor's Choice)</option>
                                    </optgroup>

                                    <optgroup label="Бусад">
                                        <option value="EarlyAccess" {{ $game->tag == 'EarlyAccess' ? 'selected' : '' }} class="text-teal-400">🛠️ Туршилтын хувилбар (Early Access)</option>
                                        <option value="PreOrder" {{ $game->tag == 'PreOrder' ? 'selected' : '' }} class="text-indigo-400">📦 Урьдчилсан захиалга (Pre-Order)</option>
                                    </optgroup>
                                </select>
                                <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- 5. SCREENSHOTS (URL ONLY) --}}
                   {{-- 5. SCREENSHOTS (Editable Textarea) --}}
                    <div class="border-t border-white/5 pt-4 mt-2">
                        <label class="block text-xs text-gray-400 font-semibold mb-2 ml-1">Screenshot URLs</label>
                        
                        {{-- Logic to convert Array to String (New Line Separated) --}}
                        @php
                            $currentScreenshots = [];
                            if (isset($game->screenshots)) {
                                if (is_array($game->screenshots)) {
                                    $currentScreenshots = $game->screenshots;
                                } elseif (is_string($game->screenshots)) {
                                    $decoded = json_decode($game->screenshots, true);
                                    if (is_array($decoded)) {
                                        $currentScreenshots = $decoded;
                                    }
                                }
                            }
                            // Массивыг шинэ мөрөөр тусгаарласан текст болгох
                            $screenshotsString = implode("\n", $currentScreenshots);
                        @endphp
                        
                        {{-- Preview Images (Харагдах байдал) --}}
                        @if(count($currentScreenshots) > 0)
                            <div class="flex gap-2 overflow-x-auto mb-3 pb-2 custom-scrollbar">
                                @foreach($currentScreenshots as $ss)
                                    <div class="relative group shrink-0">
                                        <img src="{{ $ss }}" class="w-24 h-14 object-cover rounded border border-white/10">
                                        <div class="absolute inset-0 bg-black/50 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity text-[10px] text-white pointer-events-none">
                                            Preview
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif

                        {{-- Editable Textarea --}}
                        <textarea name="screenshots_urls" rows="6" 
                            placeholder="https://image1.jpg&#10;https://image2.jpg&#10;https://image3.jpg" 
                            class="w-full bg-black/40 border border-white/10 rounded-lg px-4 py-3 text-xs text-white focus:border-brand resize-y placeholder-gray-600 custom-scrollbar leading-relaxed font-mono"
                            spellcheck="false">{{ $screenshotsString }}</textarea>
                        
                        <p class="text-[10px] text-gray-500 mt-2 ml-1 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            Нэг мөрөнд нэг URL байхаар Enter дарж бичнэ үү.
                        </p>
                    </div>

                    {{-- 6. DESCRIPTION --}}
                    <div>
                        <label class="block text-xs text-gray-400 font-semibold mb-1.5 ml-1">Description</label>
                        <textarea name="description" rows="5" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white resize-none custom-scrollbar">{{ old('description', $game->description) }}</textarea>
                    </div>

                    {{-- 7. SYSTEM REQUIREMENTS --}}
                    <div class="bg-black/20 p-5 rounded-xl border border-white/5">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Minimum --}}
                            <div>
                                <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-white/5 pb-1">Minimum</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="min_os" value="{{ old('min_os', $game->min_os) }}" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="min_cpu" value="{{ old('min_cpu', $game->min_cpu) }}" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="min_gpu" value="{{ old('min_gpu', $game->min_gpu) }}" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="min_ram" value="{{ old('min_ram', $game->min_ram) }}" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="min_storage" value="{{ old('min_storage', $game->min_storage) }}" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none col-span-2">
                                </div>
                            </div>
                            {{-- Recommended --}}
                            <div>
                                <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3 border-b border-white/5 pb-1">Recommended</h3>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="rec_os" value="{{ old('rec_os', $game->rec_os) }}" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="rec_cpu" value="{{ old('rec_cpu', $game->rec_cpu) }}" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="rec_gpu" value="{{ old('rec_gpu', $game->rec_gpu) }}" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="rec_ram" value="{{ old('rec_ram', $game->rec_ram) }}" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none">
                                    <input type="text" name="rec_storage" value="{{ old('rec_storage', $game->rec_storage) }}" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-brand outline-none col-span-2">
                                </div>
                            </div>
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