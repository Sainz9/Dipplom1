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
                    colors: {
                        brand: '#00D4FF', 
                        darkBG: '#0a0a0f', 
                        darkSurface: '#121218',
                    },
                    boxShadow: {
                        'neon': '0 0 15px rgba(0, 212, 255, 0.3)',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.8s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>

    <style>
        body { background-color: #0a0a0f; color: #e5e7eb; }
        
        .swiper-button-next, .swiper-button-prev { 
            color: #00D4FF !important; 
            width: 40px !important; 
            height: 40px !important; 
            background: rgba(0,0,0,0.8); 
            border-radius: 50%; 
            border: 1px solid rgba(255,255,255,0.1);
            backdrop-filter: blur(4px);
            transition: all 0.3s;
        }
        .swiper-button-next:hover, .swiper-button-prev:hover {
            background: #00D4FF;
            color: #000 !important;
        }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 16px !important; font-weight: bold; }
        .swiper-button-disabled { opacity: 0; pointer-events: none; }
        
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #121218; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen">

    <nav class="fixed w-full z-50 top-0 bg-darkBG/90 backdrop-blur-xl border-b border-white/[0.05]">
        <div class="max-w-[1920px] mx-auto px-6 lg:px-12">
            <div class="flex justify-between items-center h-20">
                <a href="/" class="text-2xl font-black tracking-tighter uppercase italic text-white hover:text-brand transition-colors">
                    Play<span class="text-brand">Vision</span>
                </a>
                
                <div class="flex items-center space-x-6">
                    <div class="relative hidden md:block group">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 text-gray-500 group-focus-within:text-brand transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                            </svg>
                        </div>
                        <input type="text" id="globalSearch" placeholder="Search games..." class="bg-white/5 border border-white/10 rounded-full py-2 pl-10 pr-4 text-sm text-white focus:border-brand focus:bg-black/50 focus:outline-none w-64 transition-all placeholder-gray-500">
                        
                        <div id="searchResults" class="absolute top-full left-0 w-full mt-2 bg-[#1a1a20] border border-white/10 rounded-xl shadow-2xl hidden z-50 overflow-hidden">
                            <div id="resultsContainer" class="max-h-80 overflow-y-auto custom-scroll"></div>
                        </div>
                    </div>

                    @auth
                        <a href="{{ url('/admin/dashboard') }}" class="text-xs font-bold text-brand border border-brand px-5 py-2.5 rounded-full hover:bg-brand hover:text-black transition-all shadow-[0_0_10px_rgba(0,212,255,0.2)]">
                            ADMIN PANEL
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-white hover:text-brand transition-colors uppercase tracking-wider">Log In</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20 flex-grow">
        
        <section class="relative h-[500px] md:h-[650px] w-full group overflow-hidden">
            <div class="swiper heroSwiper h-full w-full">
                <div class="swiper-wrapper">
                    
                  @if(isset($sliderGames) && $sliderGames->count() > 0)
    @foreach($sliderGames as $game)

        @if($game->tag == 'Тун удахгүй')
        <div class="swiper-slide relative">
            <img src="{{ $game->banner ?? $game->img }}" 
                 class="w-full h-full object-cover object-top">

            <div class="absolute inset-0 bg-gradient-to-r from-darkBG via-darkBG/60 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-transparent to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 p-8 md:p-16 lg:p-24 max-w-4xl animate-fade-in-up">

                <span class="bg-brand text-black text-xs font-black px-3 py-1.5 rounded mb-4 inline-block uppercase tracking-wider shadow-neon">
                    {{ $game->tag }}
                </span>

                <h1 class="text-4xl md:text-7xl font-black uppercase leading-none mb-6 text-white drop-shadow-2xl">
                    {{ $game->title }}
                </h1>

                <p class="text-gray-300 text-sm md:text-lg mb-8 line-clamp-2 max-w-xl font-medium drop-shadow-md leading-relaxed">
                    {{ $game->description }}
                </p>

                <div class="flex items-center gap-4">
                    <a href="{{ route('game.show', $game->id) }}" 
                       class="bg-brand text-black px-10 py-4 font-bold rounded hover:bg-white transition-all hover:scale-105 uppercase text-sm tracking-widest shadow-neon">
                        View Details
                    </a>
                </div>
            </div>
        </div>
        @endif

    @endforeach
@endif


                
                
             
            </div>
        </section>

        <div class="py-12 px-4 md:px-8 lg:px-12 space-y-16 -mt-10 relative z-10">
            
            {{-- 1. COMING SOON ROW (ТУСДАА) --}}
            @php
                // "Тун удахгүй" Tag-тай бүх тоглоомыг энд шүүж авна
                $comingSoonList = $games->where('tag', 'Тун удахгүй');
            @endphp

            @if($comingSoonList->count() > 0)
            <section class="relative group/section">
                <div class="flex justify-between items-end mb-4 px-2">
                    <h2 class="text-2xl md:text-3xl font-bold text-white uppercase italic tracking-wide flex items-center gap-3">
                        <span class="w-1 h-8 bg-purple-600 rounded-full shadow-[0_0_15px_rgba(147,51,234,0.5)]"></span>
                        Coming Soon (Тун удахгүй)
                    </h2>
                    
                    <div class="flex gap-2 opacity-0 group-hover/section:opacity-100 transition-opacity duration-300">
                        <button class="prev-coming w-8 h-8 rounded-full border border-white/20 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button class="next-coming w-8 h-8 rounded-full border border-white/20 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </div>
                </div>

                <div class="swiper swiperComing !overflow-visible !pb-10">
                    <div class="swiper-wrapper">
                        @foreach($comingSoonList as $game)
                        <div class="swiper-slide transition-transform duration-300 hover:z-20 hover:scale-105">
                            <a href="{{ route('game.show', $game->id) }}" class="block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-purple-500/30 hover:border-purple-500 hover:shadow-[0_0_20px_rgba(147,51,234,0.3)] group">
                                <img src="{{ $game->img }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-transparent to-transparent opacity-90"></div>
                                
                                <div class="absolute top-2 right-2 bg-purple-600 text-white text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase animate-pulse">Coming Soon</div>

                                <div class="absolute bottom-0 p-4 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                    <h3 class="font-bold text-white truncate text-base mb-1 group-hover:text-purple-400 transition-colors">{{ $game->title }}</h3>
                                    
                                    <div class="flex justify-between items-center mt-2">
                                        <span class="text-gray-400 font-bold text-xs uppercase tracking-wider border border-gray-600 px-2 py-0.5 rounded">
                                            {{ !empty($game->release_date) ? \Carbon\Carbon::parse($game->release_date)->format('Y-m-d') : 'TBA' }}
                                        </span>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            {{-- 
                2. БУСАД КАТЕГОРИУД (ЭНГИЙН ТОГЛООМУУД)
                Эндээс "Тун удахгүй" тоглоомуудыг НУУНА (@if($game->tag != 'Тун удахгүй'))
            --}}
            @if(isset($categories) && count($categories) > 0)
                @foreach($categories as $category)
                    {{-- "Тун удахгүй" БИШ тоглоом байгаа эсэхийг шалгана. Байхгүй бол гарчгийг нь ч гаргахгүй --}}
                    @php
                        $regularGamesCount = $category->games->where('tag', '!=', 'Тун удахгүй')->count();
                    @endphp

                    @if($regularGamesCount > 0)
                    <section class="relative group/section">
                        <div class="flex justify-between items-end mb-4 px-2">
                            <h2 class="text-2xl md:text-3xl font-bold text-white uppercase italic tracking-wide flex items-center gap-3">
                                <span class="w-1 h-8 bg-brand rounded-full shadow-neon"></span>
                                {{ $category->name }}
                            </h2>
                            
                            <div class="flex gap-2 opacity-0 group-hover/section:opacity-100 transition-opacity duration-300">
                                <button class="prev-{{ $category->id }} w-8 h-8 rounded-full border border-white/20 flex items-center justify-center hover:bg-brand hover:text-black hover:border-brand transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                                </button>
                                <button class="next-{{ $category->id }} w-8 h-8 rounded-full border border-white/20 flex items-center justify-center hover:bg-brand hover:text-black hover:border-brand transition-all">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </button>
                            </div>
                        </div>

                        <div class="swiper categorySwiper category-swiper-{{ $category->id }} !overflow-visible !pb-10">
                            <div class="swiper-wrapper">
                                @foreach($category->games as $game)
                                
                                {{-- 
                                    ХАМГИЙН ЧУХАЛ ХЭСЭГ: 
                                    Хэрэв 'Тун удахгүй' бол ЭНГИЙН ЖАГСААЛТАД ХАРУУЛАХГҮЙ 
                                --}}
                                @if($game->tag != 'Тун удахгүй')
                                <div class="swiper-slide transition-transform duration-300 hover:z-20 hover:scale-105">
                                    <a href="{{ route('game.show', $game->id) }}" class="block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-white/5 hover:border-brand/50 hover:shadow-neon group">
                                        <img src="{{ $game->img }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
                                        <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-transparent to-transparent opacity-90"></div>
                                        
                                        @if($game->discount)
                                            <div class="absolute top-2 right-2 bg-brand text-black text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase">{{ $game->discount }}</div>
                                        @elseif($game->tag)
                                            <div class="absolute top-2 right-2 bg-black/60 border border-white/10 text-white text-[10px] font-bold px-2 py-1 rounded backdrop-blur-sm">{{ $game->tag }}</div>
                                        @endif

                                        <div class="absolute bottom-0 p-4 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
                                            <h3 class="font-bold text-white truncate text-base mb-1 group-hover:text-brand transition-colors">{{ $game->title }}</h3>
                                            
                                            <div class="flex justify-between items-center mt-2">
                                                @if(is_numeric($game->price) && $game->price == 0)
                                                    <span class="text-brand font-bold text-sm tracking-wider">FREE</span>
                                                @elseif(is_numeric($game->price))
                                                    <span class="text-gray-300 font-bold text-sm">{{ number_format($game->price) }}₮</span>
                                                @else
                                                    <span class="text-xs text-gray-400 font-bold">{{ $game->price }}</span>
                                                @endif
                                                
                                                <div class="bg-white/10 p-1.5 rounded-full hover:bg-brand hover:text-black transition-colors text-white">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                                @endif {{-- End If Check --}}
                                @endforeach
                            </div>
                        </div>
                    </section>
                    @endif
                @endforeach
            @endif

        </div>

        <footer class="bg-darkSurface border-t border-white/5 py-12 mt-auto">
            <div class="max-w-[1920px] mx-auto px-6 text-center">
                <a href="#" class="text-2xl font-black tracking-tighter uppercase italic inline-block mb-4 text-white">
                    Play<span class="text-brand">Vision</span>
                </a>
                <p class="text-gray-500 text-xs">&copy; 2025 PlayVision Entertainment. All Rights Reserved.</p>
            </div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // 1. Hero Slider
        new Swiper('.heroSwiper', { 
            loop: true, effect: 'fade', speed: 1000, 
            autoplay: { delay: 6000, disableOnInteraction: false },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        // 2. Coming Soon Swiper
        new Swiper('.swiperComing', {
            slidesPerView: 2, spaceBetween: 16,
            navigation: { nextEl: '.next-coming', prevEl: '.prev-coming' },
            breakpoints: {
                640: { slidesPerView: 3, spaceBetween: 20 },
                1024: { slidesPerView: 4, spaceBetween: 24 },
                1280: { slidesPerView: 5, spaceBetween: 24 },
                1536: { slidesPerView: 6, spaceBetween: 32 }
            }
        });

        // 3. Category Swipers
        @if(isset($categories))
            @foreach($categories as $category)
                new Swiper('.category-swiper-{{ $category->id }}', {
                    slidesPerView: 2, spaceBetween: 16,
                    navigation: { nextEl: '.next-{{ $category->id }}', prevEl: '.prev-{{ $category->id }}' },
                    breakpoints: {
                        640: { slidesPerView: 3, spaceBetween: 20 },
                        1024: { slidesPerView: 4, spaceBetween: 24 },
                        1280: { slidesPerView: 5, spaceBetween: 24 },
                        1536: { slidesPerView: 6, spaceBetween: 32 }
                    }
                });
            @endforeach
        @endif

        // 4. Search Logic
        const searchInput = document.getElementById('globalSearch');
        const resultsContainer = document.getElementById('resultsContainer');
        const searchResults = document.getElementById('searchResults');
        const allGames = @json($games ?? []); 

        if(searchInput) {
            searchInput.addEventListener('input', (e) => {
                const query = e.target.value.toLowerCase();
                resultsContainer.innerHTML = '';
                if(query.length === 0) { searchResults.classList.add('hidden'); return; }

                const filtered = allGames.filter(g => g.title.toLowerCase().includes(query));
                if(filtered.length > 0) {
                    filtered.forEach(game => {
                        const div = document.createElement('div');
                        div.className = 'p-3 hover:bg-white/5 border-b border-white/5 flex gap-3 items-center cursor-pointer transition-colors group';
                        div.onclick = () => window.location.href = `/games/${game.id}`;
                        div.innerHTML = `<img src="${game.img}" class="w-10 h-14 object-cover rounded"><h4 class="text-sm font-bold text-white">${game.title}</h4>`;
                        resultsContainer.appendChild(div);
                    });
                    searchResults.classList.remove('hidden');
                } else { searchResults.classList.add('hidden'); }
            });
            document.addEventListener('click', (e) => {
                if(!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                    searchResults.classList.add('hidden');
                }
            });
        }
    </script>
</body>
</html>