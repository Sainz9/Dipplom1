<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Game - {{ $game->title }}</title>
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
        input[type="date"]::-webkit-calendar-picker-indicator { filter: invert(1); cursor: pointer; }
    </style>
</head>
<body class="bg-dark text-gray-300 font-sans antialiased min-h-screen flex items-center justify-center p-6">

    <div class="max-w-3xl w-full">
        <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-brand mb-6 transition-colors text-sm font-bold uppercase tracking-wide">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
            Back to Dashboard
        </a>

        <div class="bg-card rounded-2xl border border-white/5 shadow-2xl overflow-hidden relative">
            <div class="h-1 w-full bg-gradient-to-r from-yellow-500 to-orange-600"></div>

            <div class="p-8">
                <div class="flex justify-between items-start mb-8">
                    <div>
                        <h1 class="text-2xl font-black text-white italic tracking-tight uppercase">Edit <span class="text-yellow-500">Game</span></h1>
                        <p class="text-gray-500 text-sm mt-1">Updating: <span class="text-white font-bold">{{ $game->title }}</span></p>
                    </div>
                    <div class="w-16 h-20 rounded overflow-hidden border border-white/10 shadow-lg relative">
                        <img src="{{ $game->img }}" class="w-full h-full object-cover" alt="Cover">
                        @if($game->tag)
                            <div class="absolute inset-0 bg-black/50 flex items-center justify-center">
                                <span class="text-[8px] font-bold text-white uppercase text-center px-1">{{ $game->tag }}</span>
                            </div>
                        @endif
                    </div>
                </div>

                @if ($errors->any())
                    <div class="bg-red-500/10 border border-red-500 text-red-500 p-3 rounded mb-4 text-xs">
                        <ul class="list-disc pl-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.game.update', $game->id) }}" method="POST" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div>
                        <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Game Title <span class="text-red-500">*</span></label>
                        <input type="text" name="title" value="{{ old('title', $game->title) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white transition-all shadow-inner" required>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Price (or Text) <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="text" name="price" value="{{ old('price', $game->price) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white font-mono" required>
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Sale Price (Optional)</label>
                            <div class="relative">
                                <input type="number" name="sale_price" value="{{ old('sale_price', $game->sale_price) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white font-mono">
                                <span class="absolute right-4 top-3 text-gray-500 text-xs">₮</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Category</label>
                            <div class="relative">
                                <select name="category_id" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white appearance-none cursor-pointer">
                                    @foreach($categories as $category)
                                        <option value="{{ $category->id }}" {{ $game->category_id == $category->id ? 'selected' : '' }}>
                                            {{ $category->name }}
                                        </option>
                                    @endforeach
                                </select>
                                <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Tag (Badge)</label>
                            <div class="relative">
                                <select name="tag" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white appearance-none cursor-pointer">
                                    <option value="" {{ $game->tag == '' ? 'selected' : '' }}>No Badge</option>
                                    <option value="Тун удахгүй" class="text-brand font-bold" {{ $game->tag == 'Тун удахгүй' ? 'selected' : '' }}>★ Тун удахгүй (Slider)</option>
                                    <option value="Шинэ" class="text-green-400" {{ $game->tag == 'Шинэ' ? 'selected' : '' }}>Шинэ</option>
                                    <option value="Захиалах" class="text-orange-500" {{ $game->tag == 'Захиалах' ? 'selected' : '' }}>Захиалах</option>
                                    <option value="Дуусж байгаа" class="text-red-400" {{ $game->tag == 'Дуусж байгаа' ? 'selected' : '' }}>Дуусж байгаа</option>
                                </select>
                                <div class="absolute right-4 top-3.5 pointer-events-none text-gray-500">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                                </div>
                            </div>
                        </div>

                        
                    </div>

                    <div>
                        <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Release Date</label>
                        <div class="relative">
                            <input type="date" name="release_date" value="{{ old('release_date', $game->release_date) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white appearance-none">
                        </div>
                    </div>

                    <div class="space-y-4 pt-4 border-t border-white/5">
                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Cover Image URL <span class="text-red-500">*</span></label>
                            <input type="text" name="img" value="{{ old('img', $game->img) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-yellow-500 focus:outline-none text-yellow-500/80 font-mono" required>
                        </div>
                        
                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Banner Image URL</label>
                            <input type="text" name="banner" value="{{ old('banner', $game->banner) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-yellow-500 focus:outline-none text-yellow-500/80 font-mono">
                        </div>

                        <div>
                            <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">YouTube Trailer</label>
                            <input type="text" name="trailer" value="{{ old('trailer', $game->trailer) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-yellow-500 focus:outline-none text-gray-400 font-mono">
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Screenshot 1</label>
                                <input type="text" name="screenshot1" value="{{ old('screenshot1', $game->screenshot1) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-yellow-500 focus:outline-none text-gray-400 font-mono">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Screenshot 2</label>
                                <input type="text" name="screenshot2" value="{{ old('screenshot2', $game->screenshot2) }}" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-xs focus:border-yellow-500 focus:outline-none text-gray-400 font-mono">
                            </div>
                        </div>
                    </div>

                    <div>
                         <label class="block text-xs text-gray-400 font-bold mb-2 uppercase">Description</label>
                         <textarea name="description" rows="4" class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-yellow-500 focus:outline-none text-white resize-none">{{ old('description', $game->description) }}</textarea>
                    </div>

                    <div class="bg-black/20 p-5 rounded-xl border border-white/5">
                        <h3 class="text-[10px] font-bold text-gray-500 uppercase tracking-widest mb-3">System Requirements</h3>
                        <div class="grid grid-cols-2 gap-3">
                            <input type="text" name="min_os" value="{{ old('min_os', $game->min_os) }}" placeholder="OS" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-yellow-500 outline-none">
                            <input type="text" name="min_cpu" value="{{ old('min_cpu', $game->min_cpu) }}" placeholder="CPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-yellow-500 outline-none">
                            <input type="text" name="min_gpu" value="{{ old('min_gpu', $game->min_gpu) }}" placeholder="GPU" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-yellow-500 outline-none">
                            <input type="text" name="min_ram" value="{{ old('min_ram', $game->min_ram) }}" placeholder="RAM" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-yellow-500 outline-none">
                            <input type="text" name="min_storage" value="{{ old('min_storage', $game->min_storage) }}" placeholder="Storage" class="bg-black/30 border border-white/10 rounded-lg px-3 py-2 text-xs text-white focus:border-yellow-500 outline-none col-span-2">
                        </div>
                    </div>

                    <div class="flex gap-4 pt-2">
                        <a href="{{ route('admin.dashboard') }}" class="w-1/3 bg-white/5 text-white py-4 rounded-xl font-bold text-sm uppercase text-center hover:bg-white/10 transition-all flex items-center justify-center">Cancel</a>
                        <button type="submit" class="w-2/3 bg-gradient-to-r from-yellow-500 to-orange-500 text-black py-4 rounded-xl font-black text-sm uppercase tracking-widest shadow-[0_0_20px_rgba(234,179,8,0.3)] hover:shadow-[0_0_30px_rgba(234,179,8,0.5)] hover:scale-[1.02] transition-all">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
</html>