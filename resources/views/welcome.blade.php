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
        body { background-color: #0a0a0f; color: #e5e7eb; overflow-x: hidden; }
        .swiper-button-next, .swiper-button-prev { color: #fff !important; width: 40px !important; height: 40px !important; background: rgba(255,255,255,0.1); border-radius: 50%; border: 1px solid rgba(255,255,255,0.1); backdrop-filter: blur(4px); transition: all 0.3s; }
        .swiper-button-next:hover, .swiper-button-prev:hover { background: #00D4FF; color: #000 !important; border-color: #00D4FF; }
        .swiper-button-next:after, .swiper-button-prev:after { font-size: 16px !important; font-weight: bold; }
        .swiper-button-disabled { opacity: 0; pointer-events: none; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #121218; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
    </style>
</head>
<body class="antialiased flex flex-col min-h-screen overflow-x-hidden">

    {{-- 
        **************************************************************
        * ЗАСВАР 1: Function-ийг хувьсагч болгож энд зарлалаа.      *
        * Ингэснээр "Cannot redeclare function" алдаа гарахгүй.      *
        **************************************************************
    --}}
    @php
        $renderGameCard = function($game, $customBorder = '') {
            // Safety Checks
            if (!$game) return;

            // Badge Logic
            $badgeHTML = '';
            $tagText = $game->tag ?? ''; // Null safety

            // Price Logic Checks
            $isFree = (is_numeric($game->price) && $game->price == 0) || $tagText == 'FreeGame';
            $isOnSale = ($game->sale_price && is_numeric($game->sale_price) && $game->sale_price > 0);

            if ($isOnSale) {
                $badgeHTML = '<div class="absolute top-2 right-2 z-20"><span class="bg-red-600 text-white text-[10px] font-black uppercase px-2 py-1 rounded-md border border-red-500 shadow-lg animate-pulse">🏷️ SALE</span></div>';
            } elseif ($isFree) {
                $badgeHTML = '<div class="absolute top-2 right-2 z-20"><span class="bg-green-500 text-black text-[10px] font-black uppercase px-2 py-1 rounded-md border border-green-400 shadow-lg">🎁 FREE</span></div>';
            } elseif ($tagText) {
                $badgeClass = 'bg-gray-600 text-white border-gray-500';
                $icon = '✨';

                switch($tagText) {
                    case 'GOTY': $badgeClass = 'bg-yellow-500 text-black border-yellow-400 shadow-yellow-500/50'; $icon = '🏆'; break;
                    case 'BestSelling': $badgeClass = 'bg-blue-500 text-white border-blue-400 shadow-blue-500/50'; $icon = '💎'; break;
                    case 'EditorsChoice': $badgeClass = 'bg-purple-600 text-white border-purple-400'; $icon = '🎖️'; break;
                    case 'Trending': $badgeClass = 'bg-orange-500 text-white border-orange-400'; $icon = '⚡'; break;
                    case 'Тун удахгүй': $badgeClass = 'bg-gray-700 text-gray-300 border-gray-600'; $icon = '🚀'; break;
                    case 'EarlyAccess': $badgeClass = 'bg-teal-600 text-white border-teal-500'; $icon = '🛠️'; $tagText = 'Туршилтын хувилбар'; break;
                    case 'PreOrder': $badgeClass = 'bg-indigo-600 text-white border-indigo-500'; $icon = '📦'; $tagText = 'Урьдчилсан захиалга'; break;
                }

                $badgeHTML = "<div class='absolute top-2 right-2 z-20'>
                    <span class='{$badgeClass} text-[10px] font-black uppercase px-2 py-1 rounded-md border flex items-center gap-1 shadow-lg transform group-hover:scale-105 transition-transform'>
                        <span>{$icon}</span> <span>{$tagText}</span>
                    </span>
                </div>";
            }

            // Categories (Safe Loop)
            $categoriesHTML = '';
            if($game->categories) {
                foreach($game->categories->unique('id')->take(3) as $c) {
                    // ЗАСВАР 2: Null Safety ($c->name ?? '') - Нэр хоосон байвал алдаа өгөхгүй
                    $catName = \Str::before($c->name ?? 'Game', ' (');
                    $categoriesHTML .= "<span class='text-[8px] font-black uppercase tracking-wider bg-black/70 text-gray-200 border border-white/10 px-1.5 py-0.5 rounded backdrop-blur-md shadow-sm'>{$catName}</span>";
                }
            }

            // Price & Buttons
            $isComingSoon = ($tagText === 'Тун удахгүй');
            $priceHTML = '';
            $actionBtn = '';
            $route = route('game.show', $game->id);

            if ($isComingSoon) {
                $priceHTML = '<span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest border border-white/10 px-2 py-1 rounded bg-white/5">Тун удахгүй</span>';
                $actionBtn = '<div class="bg-white/5 p-1.5 rounded-full text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></div>';
            } else {
                if($isFree) {
                    $priceHTML = '<span class="text-brand font-bold text-sm tracking-wider">FREE</span>';
                } elseif(is_numeric($game->price)) {
                    if($isOnSale) {
                        $priceHTML = '<div class="flex flex-col leading-none"><span class="text-[10px] text-gray-500 line-through">' . number_format((float)$game->price) . '₮</span><span class="text-green-400 font-bold text-sm">' . number_format((float)$game->sale_price) . '₮</span></div>';
                    } else {
                        $priceHTML = '<span class="text-gray-300 font-bold text-sm">' . number_format((float)$game->price) . '₮</span>';
                    }
                } else {
                    $priceHTML = '<span class="text-xs text-gray-400 font-bold">' . ($game->price ?? '') . '</span>';
                }
                $actionBtn = '<div class="bg-white/10 p-1.5 rounded-full group-hover:bg-brand group-hover:text-black transition-colors text-white shadow-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>';
            }

            $borderClass = $customBorder ?: 'hover:border-brand/50';

            // Safe Image Output
            $imgSrc = $game->img ?? '';

            echo "
            <div class='swiper-slide transition-transform duration-300 hover:z-20 hover:scale-105'>
                <a href='{$route}' class='block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-white/5 {$borderClass} hover:shadow-neon group'>
                    <img src='{$imgSrc}' class='w-full h-full object-cover transition-transform duration-700 group-hover:scale-110' loading='lazy'>
                    <div class='absolute inset-0 bg-gradient-to-t from-darkBG via-darkBG/20 to-transparent opacity-90'></div>
                    {$badgeHTML}
                    <div class='absolute bottom-[68px] left-3 z-20 flex flex-wrap gap-1 group-hover:bottom-[82px] transition-all duration-300 pointer-events-none'>{$categoriesHTML}</div>
                    <div class='absolute bottom-0 p-4 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300'>
                        <h3 class='font-bold text-white truncate text-base mb-1 group-hover:text-brand transition-colors leading-tight'>{$game->title}</h3>
                        <div class='flex justify-between items-center mt-2'>
                            {$priceHTML}
                            {$actionBtn}
                        </div>
                    </div>
                </a>
            </div>";
        };
    @endphp

    <nav class="fixed w-full z-50 top-0 bg-darkBG/90 backdrop-blur-xl border-b border-white/[0.05]">
        <div class="max-w-[1920px] mx-auto px-6 lg:px-12">
            <div class="flex justify-between items-center h-20">
                
                <a href="/" class="text-2xl font-black tracking-tighter uppercase italic text-white hover:text-brand transition-colors">
                    Play<span class="text-brand">Vision</span>
                </a>

                <div class="hidden lg:flex items-center space-x-6">
                    <a href="{{ route('about') }}" class="text-sm font-bold tracking-wider uppercase text-white hover:text-brand transition-colors">Бидний тухай</a>
                    
                    <div class="relative z-50 group" id="searchComponent">
                        <div class="flex items-center bg-white/5 border border-white/10 rounded-full hover:border-brand/50 focus-within:border-brand focus-within:shadow-neon transition-all duration-300 w-[400px]">
                            
                            <button onclick="toggleCategoryMenu()" class="flex items-center gap-2 pl-4 pr-3 py-2 text-gray-400 hover:text-brand transition-colors border-r border-white/10 outline-none group/btn">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                                <span class="text-xs font-bold uppercase tracking-wider">Төрөл</span>
                                <svg class="w-3 h-3 transition-transform duration-300 group-hover/btn:rotate-180" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </button>

                            <div class="flex-1 relative">
                                <input type="text" id="globalSearch" placeholder="Тоглоом хайх..." class="w-full bg-transparent border-none outline-none text-sm text-white placeholder-gray-500 px-4 py-2 focus:ring-0">
                            </div>

                            <div class="pr-4 text-gray-500">
                                <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        <div id="categoryDropdown" class="hidden absolute top-full left-0 mt-3 w-full bg-[#121218] border border-white/10 rounded-2xl shadow-2xl overflow-hidden animate-fade-in-up z-50">
                            
                            {{-- New: Category Search Input --}}
                            <div class="p-3 border-b border-white/10 bg-[#1a1a20]">
                                <input type="text" id="catSearchInput" placeholder="Төрөл хайх (Жш: RPG, Action)..." class="w-full bg-black/40 border border-white/10 rounded-lg px-3 py-1.5 text-xs text-white focus:border-brand outline-none">
                            </div>

                            <div class="max-h-64 overflow-y-auto custom-scroll p-3">
                                <div class="grid grid-cols-2 gap-2" id="categoryList">
                                    @if(isset($navCategories) && count($navCategories) > 0)
                                        @foreach($navCategories as $cat)
                                        {{-- ЗАСВАР 3: $cat->name ?? 'Category' болгож засав --}}
                                        <a href="{{ url('/') }}?genre={{ $cat->name ?? '' }}" class="cat-item flex items-center gap-3 p-2 rounded-lg hover:bg-white/5 hover:text-brand transition-colors border border-transparent hover:border-white/10">
                                            <span class="text-xs font-bold uppercase tracking-wider text-gray-300 hover:text-white cat-name">
                                                {{ $cat->name ?? 'Category' }}
                                            </span>
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
                        <a href="{{ url('/dashboard') }}" class="text-xs font-bold text-brand border border-brand px-5 py-2.5 rounded-full hover:bg-brand hover:text-black transition-all shadow-[0_0_10px_rgba(0,212,255,0.2)]">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-xs font-bold text-white hover:text-brand transition-colors uppercase tracking-wider">Нэвтрэх</a>
                    @endauth
                </div>

                <button id="mobileMenuBtn" class="lg:hidden text-white hover:text-brand transition-colors focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                </button>
            </div>
        </div>

        <div id="mobileMenu" class="hidden lg:hidden bg-[#0a0a0f] border-b border-white/10 absolute w-full left-0 top-20 p-6 flex flex-col gap-6 shadow-2xl z-40">
            <div class="flex flex-col gap-4">
                <a href="{{ route('about') }}" class="text-lg font-bold text-white hover:text-brand">Бидний тухай</a>
            </div>
            <div class="border-t border-white/10 pt-4 flex items-center justify-between">
                 @auth
                    <a href="{{ url('/dashboard') }}" class="text-brand font-bold">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-white font-bold hover:text-brand">Нэвтрэх</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-20 flex-grow">
        
        <section class="relative h-[500px] md:h-[650px] w-full group overflow-hidden">
            <div class="swiper heroSwiper h-full w-full">
                <div class="swiper-wrapper">
                    @if(isset($sliderGames) && $sliderGames->count() > 0)
                        @foreach($sliderGames->where('tag', 'Тун удахгүй') as $game)
                        <div class="swiper-slide relative">
                            <img src="{{ $game->banner ?? $game->img }}" class="w-full h-full object-cover object-top">
                            <div class="absolute inset-0 bg-gradient-to-r from-darkBG via-darkBG/60 to-transparent"></div>
                            <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-transparent to-transparent"></div>
                            
                            <div class="absolute bottom-0 left-0 p-8 md:p-16 lg:p-24 max-w-4xl animate-fade-in-up">
                                <span class="bg-purple-600 text-white text-xs font-black px-3 py-1.5 rounded mb-4 inline-block uppercase tracking-widest shadow-[0_0_15px_rgba(147,51,234,0.5)]">
                                    {{ $game->tag ?? 'Featured' }}
                                </span>
                                <h1 class="text-4xl md:text-7xl font-black uppercase leading-none mb-6 text-white drop-shadow-2xl">
                                    {{ $game->title }}
                                </h1>
                                <p class="text-gray-300 text-sm md:text-lg mb-8 line-clamp-2 max-w-xl font-medium drop-shadow-md leading-relaxed">
                                    {{ $game->description }}
                                </p>
                                <a href="{{ route('game.show', $game->id) }}" class="bg-brand hover:bg-white text-black px-10 py-4 font-bold rounded transition-all hover:scale-105 uppercase text-sm tracking-widest shadow-[0_0_15px_rgba(0,212,255,0.5)]">
                                    Дэлгэрэнгүй
                                </a>
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
            
            {{-- 1. COMING SOON SECTION --}}
            @php $comingSoonList = $games->where('tag', 'Тун удахгүй'); @endphp
            @if($comingSoonList->count() > 0)
            <section class="relative group/section">
                <div class="flex justify-between items-end mb-4 px-2">
                    <h2 class="text-2xl md:text-3xl font-bold text-white uppercase italic tracking-wide flex items-center gap-3">
                        <span class="w-1 h-8 bg-purple-600 rounded-full shadow-[0_0_15px_rgba(147,51,234,0.5)]"></span>
                        Coming Soon
                    </h2>
                    <div class="flex gap-2">
                        <button class="prev-coming w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button class="next-coming w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-purple-600 hover:text-white transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>
                <div class="swiper swiperComing !overflow-visible !pb-10">
                    <div class="swiper-wrapper">
                        @foreach($comingSoonList as $game)
                            {{-- ЗАСВАР: Хувьсагч функцийг дуудах --}}
                            @php $renderGameCard($game, 'hover:border-purple-500') @endphp
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            {{-- 2. SALE SECTION --}}
            @php $onSaleGames = $games->filter(function($g){ return $g->sale_price > 0; }); @endphp
            @if($onSaleGames->count() > 0)
            <section class="relative group/section">
                <div class="flex justify-between items-end mb-4 px-2">
                    <h2 class="text-2xl md:text-3xl font-bold text-white uppercase italic tracking-wide flex items-center gap-3">
                        <span class="w-1 h-8 bg-red-600 rounded-full shadow-[0_0_15px_rgba(220,38,38,0.6)] animate-pulse"></span>
                        <span class="text-red-500 drop-shadow-lg">🔥 ХЯМДРАЛТАЙ (ON SALE)</span>
                    </h2>
                    <div class="flex gap-2">
                        <button class="prev-sale w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button class="next-sale w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-red-600 hover:text-white transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>
                <div class="swiper swiperSale !overflow-visible !pb-10">
                    <div class="swiper-wrapper">
                        @foreach($onSaleGames as $game)
                            @php $renderGameCard($game, 'hover:border-red-600') @endphp
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            {{-- 3. FREE TO PLAY SECTION --}}
            @php 
                $freeGames = $games->filter(function($g) { 
                    return (is_numeric($g->price) && $g->price == 0) || $g->tag == 'FreeGame'; 
                }); 
            @endphp
            @if($freeGames->count() > 0)
            <section class="relative group/section">
                <div class="flex justify-between items-end mb-4 px-2">
                    <h2 class="text-2xl md:text-3xl font-bold text-white uppercase italic tracking-wide flex items-center gap-3">
                        <span class="w-1 h-8 bg-green-500 rounded-full shadow-[0_0_15px_rgba(34,197,94,0.6)]"></span>
                        <span class="text-green-500 drop-shadow-lg">🎁 FREE TO PLAY</span>
                    </h2>
                    <div class="flex gap-2">
                        <button class="prev-free w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-green-500 hover:text-black transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                        <button class="next-free w-12 h-12 rounded-full border border-white/20 flex items-center justify-center hover:bg-green-500 hover:text-black transition-all"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                    </div>
                </div>
                <div class="swiper swiperFree !overflow-visible !pb-10">
                    <div class="swiper-wrapper">
                        @foreach($freeGames as $game) 
                            @php $renderGameCard($game, 'hover:border-green-500') @endphp
                        @endforeach
                    </div>
                </div>
            </section>
            @endif

            {{-- 4. OTHER SECTIONS (Dynamic) --}}
            @php
                $sections = [
                    'GOTY'          => ['title' => '🏆 Шилдэг тоглоомууд', 'color' => 'yellow-500', 'border' => 'hover:border-yellow-500'],
                    'BestSelling'   => ['title' => '💎 Best Sellers', 'color' => 'blue-500', 'border' => 'hover:border-blue-500'],
                    'Эрэлттэй'      => ['title' => '⚡ Эрэлттэй', 'color' => 'orange-500', 'border' => 'hover:border-orange-500'],
                    'EditorsChoice' => ['title' => '🎖️ Editer сонголт', 'color' => 'pink-500', 'border' => 'hover:border-pink-500'],
                    'Шинэ'          => ['title' => '🔥 Шинэ (New)', 'color' => 'green-500', 'border' => 'hover:border-green-500'],
                    'Trending'          => ['title' => '🔥 Trending', 'color' => 'green-500', 'border' => 'hover:border-green-500'],
                    
                ];
            @endphp
            {{-- 5. DYNAMIC SECTIONS --}}
            @foreach($sections as $key => $style)
                @php 
                    $filteredGames = collect();

                    if ($key === 'New' || $key === 'Шинэ') {
                        $filteredGames = $games->whereNotIn('tag', ['Тун удахгүй', 'ComingSoon', 'PreOrder', 'EarlyAccess', "Хямдралтай", "FreeGame", 'GOTY'])
                                                       ->take(10);
                    } else {
                        $filteredGames = $games->where('tag', $key);
                    }
                @endphp

                @if($filteredGames->count() > 0)
                <section class="relative group/section">
                    <div class="flex justify-between items-end mb-4 px-2">
                        <h2 class="text-2xl md:text-3xl font-bold text-white uppercase italic tracking-wide flex items-center gap-3">
                            <span class="w-1 h-8 bg-{{ $style['color'] }} rounded-full shadow-[0_0_15px_rgba(255,255,255,0.2)]"></span>
                            {{ $style['title'] }}
                        </h2>
                        <div class="flex gap-2">
                            <button class="prev-{{ $key }} w-10 h-10 rounded border border-white/10 flex items-center justify-center hover:bg-{{ $style['color'] }} hover:text-black transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg></button>
                            <button class="next-{{ $key }} w-10 h-10 rounded border border-white/10 flex items-center justify-center hover:bg-{{ $style['color'] }} hover:text-black transition-all"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg></button>
                        </div>
                    </div>
                    <div class="swiper swiper-{{ $key }} !overflow-visible !pb-10">
                        <div class="swiper-wrapper">
                            @foreach($filteredGames as $game)
                                @php $renderGameCard($game, $style['border']) @endphp
                            @endforeach
                        </div>
                    </div>
                </section>
                @endif
            @endforeach

        

        </div>

        <footer class="bg-darkSurface border-t border-white/5 py-12 mt-auto">
            <div class="max-w-[1920px] mx-auto px-6 text-center">
                <a href="#" class="text-2xl font-black tracking-tighter uppercase italic inline-block mb-4 text-white">Play<span class="text-brand">Vision</span></a>
                <p class="text-gray-500 text-xs">&copy; 2025 PlayVision Entertainment.</p>
            </div>
        </footer>
    </main>

    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
    <script>
        // --- MOBILE MENU LOGIC ---
        const menuBtn = document.getElementById('mobileMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');
        if(menuBtn && mobileMenu) {
            menuBtn.addEventListener('click', () => { mobileMenu.classList.toggle('hidden'); });
        }

        // --- HEADER CATEGORY DROPDOWN ---
        function toggleCategoryMenu() {
            const menu = document.getElementById('categoryDropdown');
            menu.classList.toggle('hidden');
        }
        document.addEventListener('click', function(event) {
            const searchComponent = document.getElementById('searchComponent');
            const menu = document.getElementById('categoryDropdown');
            if (searchComponent && !searchComponent.contains(event.target)) {
                if (!menu.classList.contains('hidden')) { menu.classList.add('hidden'); }
            }
        });

        // --- SWIPER LOGIC ---
        const commonBreakpoints = {
            320: { slidesPerView: 2, spaceBetween: 12 },
            640: { slidesPerView: 3, spaceBetween: 20 },
            1024: { slidesPerView: 4, spaceBetween: 24 },
            1280: { slidesPerView: 5, spaceBetween: 24 },
            1536: { slidesPerView: 6, spaceBetween: 32 }
        };

        new Swiper('.heroSwiper', { 
            loop: true, effect: 'fade', speed: 1000, 
            autoplay: { delay: 6000, disableOnInteraction: false },
            navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },
        });

        new Swiper('.swiperComing', { slidesPerView: 2, spaceBetween: 16, navigation: { nextEl: '.next-coming', prevEl: '.prev-coming' }, breakpoints: commonBreakpoints });
        
        new Swiper('.swiperSale', { slidesPerView: 2, spaceBetween: 16, navigation: { nextEl: '.next-sale', prevEl: '.prev-sale' }, breakpoints: commonBreakpoints });

        new Swiper('.swiperFree', { slidesPerView: 2, spaceBetween: 16, navigation: { nextEl: '.next-free', prevEl: '.prev-free' }, breakpoints: commonBreakpoints });

        // Dynamic Swipers
        const sections = @json(array_keys($sections));
        sections.forEach(key => {
            new Swiper('.swiper-' + key, {
                slidesPerView: 2, spaceBetween: 16,
                navigation: { nextEl: '.next-' + key, prevEl: '.prev-' + key },
                breakpoints: commonBreakpoints
            });
        });

        // --- CATEGORY SWIPERS (FIXED JS) ---
        // Ensure $navCategories is available
        const categoryIds = @json((isset($navCategories) && is_object($navCategories) && method_exists($navCategories, 'pluck')) ? $navCategories->pluck('id') : []);
        categoryIds.forEach(id => {
            new Swiper('.swiper-cat-' + id, {
                slidesPerView: 2, spaceBetween: 16,
                navigation: { nextEl: '.next-cat-' + id, prevEl: '.prev-cat-' + id },
                breakpoints: commonBreakpoints
            });
        });

        // --- CATEGORY SEARCH LOGIC (New) ---
        const catSearchInput = document.getElementById('catSearchInput');
        if(catSearchInput) {
            catSearchInput.addEventListener('input', function() {
                const filter = this.value.toLowerCase();
                const items = document.querySelectorAll('.cat-item');
                
                items.forEach(item => {
                    const text = item.querySelector('.cat-name').innerText.toLowerCase();
                    if(text.includes(filter)) {
                        item.style.display = 'flex';
                    } else {
                        item.style.display = 'none';
                    }
                });
            });
        }

        // --- GAME SEARCH LOGIC ---
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
                        div.innerHTML = `<img src="${game.img}" class="w-10 h-14 object-cover rounded shadow-sm"><div class="flex flex-col"><h4 class="text-sm font-bold text-white group-hover:text-brand">${game.title}</h4></div>`;
                        resultsContainer.appendChild(div);
                    });
                    searchResults.classList.remove('hidden');
                } else { searchResults.classList.add('hidden'); }
            });
            document.addEventListener('click', (e) => {
                if(!searchInput.contains(e.target) && !searchResults.contains(e.target)) { searchResults.classList.add('hidden'); }
            });
        }
    </script>
</body> 
</html>