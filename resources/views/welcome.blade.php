<!DOCTYPE html>
<html lang="mn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayVision - Home</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: { brand: '#00D4FF', darkBG: '#0a0a0f', darkSurface: '#121218' },
                    boxShadow: { 'neon': '0 0 15px rgba(0, 212, 255, 0.3)' },
                    animation: { 'fade-in-up': 'fadeInUp 0.8s ease-out forwards' },
                    keyframes: { fadeInUp: { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } } }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0a0a0f; color: #e5e7eb; overflow-x: hidden; }
        .swiper-button-next, .swiper-button-prev { color: #fff !important; width: 40px !important; height: 40px !important; background: rgba(255,255,255,0.1); border-radius: 50%; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); transition: all 0.3s; }
        .swiper-button-next:hover, .swiper-button-prev:hover { background: #00D4FF; color: #000 !important; border-color: #00D4FF; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #121218; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen overflow-x-hidden">

    <nav class="fixed w-full z-50 top-0 bg-darkBG/90 backdrop-blur-xl border-b border-white/[0.05]">
        <div class="max-w-[1920px] mx-auto px-6 lg:px-12">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="text-2xl font-black tracking-tighter uppercase italic text-white hover:text-brand transition-colors">
                    Play<span class="text-brand">Vision</span>
                </a>
                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('about') }}" class="text-sm font-bold tracking-wider uppercase text-white hover:text-brand transition-colors">Бидний тухай</a>
                    <div class="relative z-50 group" id="searchComponent">
                        <div class="flex items-center bg-white/5 border border-white/10 rounded-full hover:border-brand/50 w-[400px]">
                            <button onclick="toggleCategoryMenu()" class="flex items-center gap-2 pl-4 pr-3 py-2 text-gray-400 hover:text-brand transition-colors border-r border-white/10 outline-none">
                                <span class="text-xs font-bold uppercase tracking-wider">Төрөл</span>
                            </button>
                            <input type="text" id="globalSearch" placeholder="Тоглоом хайх..." class="w-full bg-transparent border-none outline-none text-sm text-white placeholder-gray-500 px-4 py-2">
                        </div>
                        <div id="categoryDropdown" class="hidden absolute top-full left-0 mt-3 w-full bg-[#121218] border border-white/10 rounded-2xl shadow-2xl overflow-hidden z-50">
                            <div class="p-3 border-b border-white/10 bg-[#1a1a20]">
                                <input type="text" id="catSearchInput" placeholder="Хайх..." class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white outline-none">
                            </div>
                            <div class="max-h-64 overflow-y-auto custom-scroll p-3">
                                <div class="grid grid-cols-2 gap-2">
                                    @if(isset($navCategories) && count($navCategories) > 0)
                                        @foreach($navCategories as $cat)
                                        <a href="{{ url('/') }}?genre={{ $cat->name ?? '' }}" class="cat-item flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 hover:text-brand transition-colors">
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-300 hover:text-white cat-name">{{ $cat->name ?? 'Category' }}</span>
                                        </a>
                                        @endforeach
                                    @else
                                        <p class="text-gray-500 text-xs text-center col-span-2 py-4">Төрөл олдсонгүй</p>
                                    @endif
                                </div>
                            </div>
                        </div>
                        <div id="searchResults" class="absolute top-full left-0 w-full mt-3 bg-[#121218] border border-white/10 rounded-2xl shadow-2xl hidden overflow-hidden z-50">
                            <div id="resultsContainer" class="max-h-64 overflow-y-auto custom-scroll"></div>
                        </div>
                    </div>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-brand border border-brand px-5 py-2.5 rounded-full hover:bg-brand">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-white hover:text-brand uppercase">Нэвтрэх</a>
                    @endauth
                </div>
                <button id="mobileMenuBtn" class="lg:hidden text-white hover:text-brand"><svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
            </div>
        </div>
        <div id="mobileMenu" class="hidden lg:hidden bg-[#0a0a0f] border-b border-white/10 absolute w-full left-0 top-20 p-6 flex flex-col gap-6 shadow-2xl z-40">
            <a href="{{ route('about') }}" class="text-lg font-bold text-white">Бидний тухай</a>
             @auth <a href="{{ url('/dashboard') }}" class="text-brand font-bold">Dashboard</a> @else <a href="{{ route('login') }}" class="text-white font-bold">Нэвтрэх</a> @endauth
        </div>
    </nav>

    <main class="pt-20 flex-grow">
        {{-- HERO SLIDER --}}
        <section class="relative h-[500px] md:h-[650px] w-full group overflow-hidden">
            <div class="swiper heroSwiper h-full w-full">
                <div class="swiper-wrapper">
                    @if(isset($sliderGames) && $sliderGames->count() > 0)
                        @foreach($sliderGames as $game)
                        <div class="swiper-slide relative">
                            <img src="{{ $game->banner ?? $game->img }}" class="w-full h-full object-cover object-top">
                            <div class="absolute inset-0 bg-gradient-to-r from-darkBG via-darkBG/60 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-transparent to-transparent"></div>
                            <div class="absolute bottom-0 left-0 p-8 md:p-16 lg:p-24 max-w-4xl animate-fade-in-up">
                                <h1 class="text-4xl md:text-7xl font-black uppercase leading-none mb-6 text-white drop-shadow-2xl">{{ $game->title }}</h1>
                                <a href="{{ route('game.show', $game->id) }}" class="bg-brand hover:bg-white text-black px-10 py-4 font-bold rounded transition-all uppercase text-sm tracking-widest">Дэлгэрэнгүй</a>
                            </div>
                        </div>
                        @endforeach
                    @endif
                </div>
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>
            </div>
        </section>

        <div class="py-12 px-4 md:px-8 lg:px-12 space-y-16 -mt-10 relative z-10">

            {{-- 1. COMING SOON --}}
            @if(isset($comingSoonGames) && $comingSoonGames->count() > 0)
            <section class="relative group/section">
                <h2 class="text-2xl font-bold text-white mb-4 uppercase italic">Coming Soon</h2>
                <div class="swiper swiperComing !overflow-visible !pb-10">
                    <div class="swiper-wrapper">
                        @foreach($comingSoonGames as $game)
                            <div class="swiper-slide hover:scale-105 transition-transform duration-300">
                                <a href="{{ route('game.show', $game->id) }}" class="block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-white/5 hover:border-purple-500">
                                    <img src="{{ $game->img }}" class="w-full h-full object-cover">
                                    <div class="absolute bottom-0 p-4 w-full bg-gradient-to-t from-black/90 to-transparent">
                                        <h3 class="font-bold text-white truncate">{{ $game->title }}</h3>
                                        <span class="text-xs text-gray-400 font-bold uppercase border border-white/20 px-2 py-1 rounded">Тун удахгүй</span>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            {{-- 2. DYNAMIC SECTIONS --}}
            @php
                $sections = [
                    'GOTY'          => ['title' => '🏆 Game of the Year', 'color' => 'yellow-500'],
                    'BestSelling'   => ['title' => '💎 Best Sellers', 'color' => 'blue-500'],
                    'Шинэ'          => ['title' => '🔥 Шинэ (New)', 'color' => 'green-500'],
                    'EditorsChoice' => ['title' => '🎖️ Редакторын сонголт', 'color' => 'pink-500'],
                    'Trending'      => ['title' => '⚡ Trending', 'color' => 'orange-500'],
                ];
            @endphp

            @foreach($sections as $key => $style)
                @php 
                    $filteredGames = collect();
                    if ($key === 'Шинэ' || $key === 'New') {
                        $filteredGames = $games->whereNotIn('tag', ['Тун удахгүй', 'FreeGame'])->take(10);
                    } else {
                        $filteredGames = $games->where('tag', $key);
                    }
                @endphp

                @if($filteredGames->count() > 0)
                <section class="relative group/section">
                    <div class="flex justify-between items-end mb-4 px-2">
                        <h2 class="text-2xl font-bold text-white uppercase italic flex items-center gap-2">
                            <span class="w-1 h-6 bg-{{ $style['color'] }} rounded-full"></span> {{ $style['title'] }}
                        </h2>
                        <div class="flex gap-2">
                            <button class="prev-{{ $key }} w-8 h-8 rounded border border-white/10 flex items-center justify-center hover:bg-white/10 text-white"><</button>
                            <button class="next-{{ $key }} w-8 h-8 rounded border border-white/10 flex items-center justify-center hover:bg-white/10 text-white">></button>
                        </div>
                    </div>
                    <div class="swiper swiper-{{ $key }} !overflow-visible !pb-10">
                        <div class="swiper-wrapper">
                            @foreach($filteredGames as $game)
                                <div class="swiper-slide hover:scale-105 transition-transform duration-300">
                                    <a href="{{ route('game.show', $game->id) }}" class="block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-white/5 hover:border-{{ $style['color'] }}">
                                        <img src="{{ $game->img }}" class="w-full h-full object-cover">
                                        <div class="absolute bottom-0 p-4 w-full bg-gradient-to-t from-black/90 to-transparent">
                                            <h3 class="font-bold text-white truncate">{{ $game->title }}</h3>
                                            <div class="flex justify-between items-center mt-1">
                                                @if(is_numeric($game->price) && $game->price == 0)
                                                    <span class="text-brand font-bold text-sm">FREE</span>
                                                @elseif($game->sale_price > 0)
                                                    <div class="flex flex-col leading-none">
                                                        <span class="text-[10px] text-gray-500 line-through">{{ number_format($game->price) }}₮</span>
                                                        <span class="text-green-400 font-bold text-sm">{{ number_format($game->sale_price) }}₮</span>
                                                    </div>
                                                @else
                                                    <span class="text-gray-300 font-bold text-sm">{{ number_format((float)$game->price) }}₮</span>
                                                @endif
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif
            @endforeach

        </div>

        <footer class="bg-darkSurface border-t border-white/5 py-12 mt-auto text-center">
            <a href="#" class="text-2xl font-black tracking-tighter uppercase italic text-white">Play<span class="text-brand">Vision</span></a>
            <p class="text-gray-500 text-xs mt-2">&copy; 2025 PlayVision Entertainment.</p>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if(menuBtn) menuBtn.addEventListener('click', () => mobileMenu.classList.toggle('hidden'));

        function toggleCategoryMenu() { document.getElementById('categoryDropdown').classList.toggle('hidden'); }
        
        const commonBreakpoints = {
            320: { slidesPerView: 2, spaceBetween: 12 },
            640: { slidesPerView: 3, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 24 },
            1280: { slidesPerView: 5, spaceBetween: 24 }
        };

        new Swiper('.heroSwiper', { loop: true, autoplay: { delay: 5000 }, navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' } });
        new Swiper('.swiperComing', { slidesPerView: 2, spaceBetween: 16, breakpoints: commonBreakpoints });
        
        const sections = @json(array_keys($sections));
        sections.forEach(key => {
            new Swiper('.swiper-' + key, {
                slidesPerView: 2, spaceBetween: 16,
                navigation: { nextEl: '.next-' + key, prevEl: '.prev-' + key },
                breakpoints: commonBreakpoints
            });
        });

        // Search Logic
        const searchInput = document.getElementById('globalSearch');
        const resultsContainer = document.getElementById('resultsContainer');
        const allGames = @json($games ?? []); 
        if(searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                resultsContainer.innerHTML = '';
                if(query.length === 0) { document.getElementById('searchResults').classList.add('hidden'); return; }
                const filtered = allGames.filter(g => g.title.toLowerCase().includes(query));
                if(filtered.length > 0) {
                    filtered.forEach(game => {
                        const div = document.createElement('div');
                        div.className = 'p-3 hover:bg-white/5 border-b border-white/5 flex gap-3 items-center cursor-pointer';
                        div.onclick = () => window.location.href = `/games/${game.id}`;
                        div.innerHTML = `<img src="${game.img}" class="w-8 h-10 object-cover rounded"><h4 class="text-sm font-bold text-white">${game.title}</h4>`;
                        resultsContainer.appendChild(div);
                    });
                    document.getElementById('searchResults').classList.remove('hidden');
                }
            });
        }
    </script>
</body> 
</html>