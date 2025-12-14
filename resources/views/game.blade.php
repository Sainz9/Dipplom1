<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game->title ?? 'Game Details' }} | PlayVision</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: {
                        brand: '#0078F2',
                        brandHover: '#0062c4',
                        dark: '#0f0f0f', 
                        surface: '#18181b', 
                    },
                    animation: { 'fade-in': 'fadeIn 0.5s ease-out' },
                    keyframes: {
                        fadeIn: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0f0f0f; color: #e5e5e5; }
        
        /* Scrollbar styles */
        .custom-scroll::-webkit-scrollbar { height: 6px; width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.05); }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 10px; }
        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        /* Active Thumbnail Glow */
        .active-thumb { 
            border-color: #0078F2 !important; 
            box-shadow: 0 0 20px rgba(0, 120, 242, 0.5);
            opacity: 1 !important; 
            transform: scale(1.05);
            z-index: 10;
        }
        .active-thumb img { opacity: 1 !important; }
        
        .force-hidden { display: none !important; visibility: hidden !important; z-index: -50 !important; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans bg-[#0f0f0f] overflow-x-hidden selection:bg-brand selection:text-white">

    {{-- 1. DATA PREPARATION --}}
    @php
        $video_id = null;
        if(isset($game->trailer) && !empty($game->trailer)) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $game->trailer, $match)) {
                $video_id = $match[1];
            }
        }
        $mainImg = $game->banner ?? $game->img ?? 'https://via.placeholder.com/800x450';

        // Media List Logic
        $mediaList = [];
        if($video_id) {
            $mediaList[] = ['type' => 'video', 'src' => $video_id, 'thumb' => $game->img];
        }
        $mediaList[] = ['type' => 'image', 'src' => $game->img, 'thumb' => $game->img];
        if(isset($game->screenshots) && is_array($game->screenshots)) {
            foreach($game->screenshots as $shot) {
                if(!empty($shot)) $mediaList[] = ['type' => 'image', 'src' => $shot, 'thumb' => $shot];
            }
        }
    @endphp

    {{-- BACKGROUND --}}
    <div class="fixed top-0 left-0 w-full h-[80vh] z-0 pointer-events-none">
        <img src="{{ $mainImg }}" class="w-full h-full object-cover opacity-30 blur-sm mask-image-gradient">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-[#0f0f0f]/80 to-transparent"></div>
    </div>

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 top-0 bg-[#0f0f0f]/70 backdrop-blur-xl border-b border-white/5 transition-all duration-300">
        <div class="max-w-[1600px] mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="group flex items-center gap-2">
                <div class="w-8 h-8 bg-brand rounded-lg flex items-center justify-center text-white font-black italic shadow-[0_0_15px_rgba(0,120,242,0.5)]">P</div>
                <span class="text-xl font-bold tracking-tight text-white">Play<span class="text-brand">Vision</span></span>
            </a>
            <div class="flex items-center gap-8">
                <a href="/" class="text-sm font-medium text-gray-400 hover:text-white transition-colors">STORE</a>
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="text-sm font-bold text-brand hover:text-white">ADMIN</a>
                @else
                    <a href="{{ route('login') }}" class="px-5 py-2 rounded-full border border-white/10 bg-white/5 text-sm font-bold text-white hover:bg-white/10">LOGIN</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 flex-grow relative z-10 animate-fade-in">
        <div class="max-w-[1400px] mx-auto px-6">
            
            {{-- TITLE HEADER --}}
            <div class="mb-8">
                <h1 class="text-5xl md:text-7xl font-black text-white mb-4 tracking-tighter drop-shadow-2xl">{{ $game->title }}</h1>
                <div class="flex items-center gap-6 text-sm text-gray-400 font-medium">
                    <div class="flex items-center gap-1.5 text-yellow-400">
                        <span class="text-lg">★</span>
                        <span class="text-white font-bold">{{ $game->rating ?? 'N/A' }}</span>
                    </div>
                    @if(isset($game->categories))
                        @foreach($game->categories as $category)
                            <span class="bg-brand/10 text-brand px-2 py-0.5 rounded text-[10px] uppercase font-bold border border-brand/20">{{ $category->name }}</span>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- MAIN GRID --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start relative">
                
                {{-- LEFT COLUMN (8 cols) - PREMIUM STYLE --}}
                <div class="lg:col-span-8 w-full min-w-0 flex flex-col gap-6">
                    
                    {{-- 1. MAIN DISPLAY (Clickable Loop) --}}
                    <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-2xl ring-1 ring-white/10 relative group z-20">
                        <div id="player-container" class="w-full h-full absolute inset-0 z-30 {{ ($mediaList[0]['type'] == 'video') ? '' : 'force-hidden' }}">
                            <div id="youtube-player"></div>
                        </div>
                        <img id="mainImage" 
                             onclick="nextMedia()" 
                             src="{{ ($mediaList[0]['type'] == 'video') ? $mediaList[1]['src'] : $mediaList[0]['src'] }}" 
                             class="w-full h-full object-cover transition-opacity duration-500 z-20 absolute inset-0 cursor-pointer hover:opacity-90 active:scale-[0.98] transition-all {{ ($mediaList[0]['type'] == 'video') ? 'force-hidden' : '' }}">
                        <div class="absolute right-4 bottom-4 bg-black/60 backdrop-blur px-3 py-1 rounded-full text-[10px] text-white/70 pointer-events-none opacity-0 group-hover:opacity-100 transition border border-white/10 uppercase font-bold tracking-widest">
                            Next Media ➔
                        </div>
                    </div>

                    {{-- 2. THUMBNAILS CAROUSEL (Scrollable) --}}
                    <div class="w-full relative group/thumbs mt-2">
                        {{-- Masks --}}
                        <div class="absolute left-0 top-0 bottom-0 w-12 bg-gradient-to-r from-[#0f0f0f] to-transparent z-10 pointer-events-none"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-12 bg-gradient-to-l from-[#0f0f0f] to-transparent z-10 pointer-events-none"></div>

                        {{-- Arrows --}}
                        <button onclick="scrollThumbs('left')" class="absolute left-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/50 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center opacity-0 group-hover/thumbs:opacity-100 transition hover:bg-brand hover:scale-110">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button onclick="scrollThumbs('right')" class="absolute right-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/50 backdrop-blur-md border border-white/10 rounded-full flex items-center justify-center opacity-0 group-hover/thumbs:opacity-100 transition hover:bg-brand hover:scale-110">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        {{-- Track --}}
                        <div id="thumbnails-track" class="flex gap-3 overflow-x-auto px-4 py-2 scroll-smooth no-scrollbar snap-x snap-mandatory">
                            @foreach($mediaList as $index => $media)
                                <button onclick="setMedia({{ $index }})" 
                                        id="thumb-{{ $index }}"
                                        class="thumb-item snap-center w-36 h-20 flex-shrink-0 rounded-xl overflow-hidden border-2 {{ $index == 0 ? 'border-brand active-thumb' : 'border-transparent' }} bg-surface relative cursor-pointer hover:border-white/50 transition-all duration-300 transform hover:scale-105">
                                    @if($media['type'] == 'video')
                                        <div class="absolute inset-0 flex items-center justify-center bg-black/40 pointer-events-none">
                                            <div class="w-8 h-8 bg-brand/90 rounded-full flex items-center justify-center shadow-lg">
                                                <svg class="w-4 h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                    @endif
                                    <img src="{{ $media['thumb'] }}" class="w-full h-full object-cover opacity-60 hover:opacity-100 transition-opacity">
                                </button>
                            @endforeach
                        </div>
                    </div>

                    {{-- 3. DESCRIPTION (WITH ICON) --}}
                    <div class="bg-surface/50 backdrop-blur-sm rounded-2xl p-8 border border-white/5">
                        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Тоглоомын тухай
                        </h3>
                        <div class="text-gray-300 leading-7 text-sm font-light">
                            {{ $game->description ?? 'No description.' }}
                        </div>
                    </div>

                    {{-- 4. SPECS (WITH ICON) --}}
                    <div class="bg-surface/50 backdrop-blur-sm rounded-2xl p-8 border border-white/5">
                        <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-3">
                            <svg class="w-6 h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            Шаардах үзүүлэлт
                        </h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm">
                            <div class="space-y-4">
                                <div class="text-gray-400 font-bold uppercase text-xs tracking-widest border-b border-white/10 pb-2">Хамгийн бага</div>
                                <div class="flex justify-between"><span class="text-gray-500">OS</span> <span class="text-gray-200">{{ $game->min_os ?? '-' }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">CPU</span> <span class="text-gray-200">{{ $game->min_cpu ?? '-' }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">RAM</span> <span class="text-gray-200">{{ $game->min_ram ?? '-' }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">GPU</span> <span class="text-gray-200">{{ $game->min_gpu ?? '-' }}</span></div>
                            </div>
                            <div class="space-y-4">
                                <div class="text-brand font-bold uppercase text-xs tracking-widest border-b border-brand/20 pb-2">Зөвлөмжит</div>
                                <div class="flex justify-between"><span class="text-gray-500">OS</span> <span class="text-gray-200">{{ $game->min_os ?? '-' }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">CPU</span> <span class="text-gray-200">{{ $game->min_cpu ?? '-' }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">RAM</span> <span class="text-gray-200">{{ $game->ram ?? '-' }}</span></div>
                                <div class="flex justify-between"><span class="text-gray-500">GPU</span> <span class="text-gray-200">{{ $game->gpu ?? '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div> 

                {{-- RIGHT COLUMN (SIDEBAR - PRESERVED & STYLED) --}}
                <div class="lg:col-span-4 h-fit space-y-6">
                    <div class="bg-[#1a1a1a]/90 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-2xl relative overflow-hidden">
                        {{-- Decorative Glow --}}
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand/20 blur-3xl rounded-full pointer-events-none"></div>

                        {{-- Price Block --}}
                        <div class="mb-6 relative z-10 text-center">
                            @php 
                                $isPriceNumeric = is_numeric($game->price);
                                $isComingSoon = ($game->tag == 'Тун удахгүй' || !$isPriceNumeric);
                            @endphp

                            @if($isComingSoon)
                                <div class="text-2xl font-black text-gray-200">COMING SOON</div>
                                <div class="text-gray-500 text-sm mt-1">Pre-order details coming soon.</div>
                            @elseif($game->price == 0)
                                <div class="text-3xl font-black text-white">Free to Play</div>
                            @elseif($game->sale_price && is_numeric($game->sale_price))
                                <div class="flex flex-col items-center">
                                    <span class="text-gray-500 line-through text-sm mb-1">{{ number_format((float)$game->price) }}₮</span>
                                    <div class="flex items-center gap-2">
                                        <span class="text-3xl font-black text-white">{{ number_format((float)$game->sale_price) }}₮</span>
                                        <span class="bg-green-500 text-black px-1.5 py-0.5 rounded text-xs font-bold">-{{ round((((float)$game->price - (float)$game->sale_price) / (float)$game->price) * 100) }}%</span>
                                    </div>
                                </div>
                            @else
                                <div class="text-3xl font-black text-white">{{ number_format((float)$game->price) }}₮</div>
                            @endif
                        </div>

                        {{-- Action Buttons --}}
                        <div class="space-y-3 mb-6 relative z-10">
                            @if($isComingSoon)
                                <button disabled class="w-full bg-white/5 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed border border-white/5">Not Available</button>
                            @else
                                <button class="w-full bg-brand hover:bg-brandHover text-white font-bold py-4 rounded-xl uppercase tracking-wider transition-all shadow-[0_0_20px_rgba(0,120,242,0.4)] hover:shadow-[0_0_30px_rgba(0,120,242,0.6)] transform active:scale-[0.98]">
                                    Худалдаж авах
                                </button>
                                <button class="w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-xl transition-colors border border-white/5">
                                    Сагсанд хийх
                                </button>
                            @endif
                        </div>

                        {{-- Meta Info --}}
                        <div class="border-t border-white/10 pt-4 space-y-3 text-sm text-gray-400">
                            <div class="flex justify-between"><span>Developer</span> <span class="text-white">{{ $game->developer ?? 'PlayVision' }}</span></div>
                            <div class="flex justify-between"><span>Publisher</span> <span class="text-white">{{ $game->publisher ?? 'PlayVision' }}</span></div>
                            <div class="flex justify-between"><span>Release Date</span> <span class="text-white">{{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->format('Y-m-d') : 'TBA' }}</span></div>
                        </div>
                    </div>
                </div> 

            </div> 

            {{-- RELATED GAMES (PRESERVED) --}}
          

        @if(isset($relatedGames) && $relatedGames->count() > 0)
            <div class="mt-20 border-t border-white/5 pt-10">
                <h2 class="text-2xl font-bold text-white mb-8 flex items-center gap-3">
                    <span class="bg-brand w-2 h-8 rounded-full"></span>
              Төстэй тоглоомууд
                </h2>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    @foreach($relatedGames as $related)
                        <a href="{{ route('game.show', $related->id) }}" class="group block bg-[#1a1a1a] rounded-xl overflow-hidden border border-white/5 hover:border-brand/50 hover:-translate-y-1 transition-all duration-300 shadow-lg">
                            <div class="relative h-40 overflow-hidden">
                                <img src="{{ $related->img }}" alt="{{ $related->title }}" class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/20 group-hover:bg-brand/10 transition-colors"></div>
                            </div>

                            <div class="p-4">
                              
                                <div class="flex flex-wrap gap-1 mb-2">
                                    @foreach($related->categories->take(2) as $c)
                                        <span class="text-[9px] bg-brand/10 text-brand px-1.5 py-0.5 rounded border border-brand/20 font-bold uppercase">{{ $c->name }}</span>
                                    @endforeach
                                </div>

                                <h3 class="text-white font-bold truncate group-hover:text-brand transition-colors">{{ $related->title }}</h3>

                                <div class="flex justify-between items-center mt-3">
                                    <div class="text-sm">
                                        @if(is_numeric($related->price) && $related->price == 0)
                                            <span class="text-gray-400 font-medium">Free</span>
                                        @elseif(is_numeric($related->sale_price) && $related->sale_price > 0)
                                            @if(is_numeric($related->price))
                                                <span class="bg-green-500/20 text-green-400 px-1.5 py-0.5 rounded text-xs">-{{ round((($related->price - $related->sale_price) / $related->price) * 100) }}%</span>
                                            @endif
                                            <span class="text-gray-300 font-bold ml-1">{{ number_format($related->sale_price) }}₮</span>
                                        @elseif(is_numeric($related->price))
                                            <span class="text-gray-300 font-bold">{{ number_format($related->price) }}₮</span>
                                        @else
                                            <span class="text-gray-400 font-bold text-xs uppercase">{{ $related->price }}</span>
                                        @endif
                                    </div>
                                    
                                    @if($related->tag)
                                        <span class="text-[10px] uppercase bg-white/10 text-gray-400 px-2 py-1 rounded border border-white/5">{{ $related->tag }}</span>
                                    @endif
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
            @endif

        </div>
    </main>

    <footer class="border-t border-white/5 py-12 text-center bg-[#0a0a0a]">
        <p class="text-gray-600 text-sm">&copy; 2025 PlayVision. All rights reserved.</p>
    </footer>

    {{-- JAVASCRIPT LOGIC --}}
    <script>
        const mediaList = @json($mediaList);
        let currentIndex = 0;
        let player;

        // 1. YouTube API Setup
        function onYouTubeIframeAPIReady() {
            if (mediaList[0] && mediaList[0].type === 'video') {
                player = new YT.Player('youtube-player', {
                    width: '100%', height: '100%', videoId: mediaList[0].src,
                    playerVars: { autoplay: 1, mute: 1, controls: 1, rel: 0, playsinline: 1, modestbranding: 1 },
                    events: { onStateChange: onPlayerStateChange }
                });
            } else {
                player = new YT.Player('youtube-player', {
                    width: '100%', height: '100%',
                    playerVars: { autoplay: 1, mute: 1, controls: 1 },
                    events: { onStateChange: onPlayerStateChange }
                });
            }
        }

        // 2. Video дуусах үед
        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) {
                nextMedia();
            }
        }

        // 3. MEDIA СОЛИХ ФУНКЦ
        function setMedia(index) {
            currentIndex = index;
            const item = mediaList[index];
            const img = document.getElementById('mainImage');
            const playerBox = document.getElementById('player-container');

            document.querySelectorAll('.thumb-item').forEach(t => {
                t.classList.remove('active-thumb', 'border-brand');
                t.classList.add('border-transparent');
            });
            const activeThumb = document.getElementById(`thumb-${index}`);
            if(activeThumb) {
                activeThumb.classList.add('active-thumb', 'border-brand');
                activeThumb.classList.remove('border-transparent');
                activeThumb.scrollIntoView({ behavior: 'smooth', block: 'nearest', inline: 'center' });
            }

            if (item.type === 'video') {
                img.classList.add('force-hidden');
                playerBox.classList.remove('force-hidden');
                if (player && typeof player.loadVideoById === 'function') {
                    player.loadVideoById(item.src);
                }
            } else {
                if (player && typeof player.stopVideo === 'function') player.stopVideo();
                playerBox.classList.add('force-hidden');
                img.src = item.src;
                img.classList.remove('force-hidden');
            }
        }

        // 4. NEXT BUTTON LOGIC
        function nextMedia() {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= mediaList.length) {
                nextIndex = 0;
            }
            setMedia(nextIndex);
        }

        // 5. THUMBNAIL SCROLL LOGIC
        function scrollThumbs(direction) {
            const container = document.getElementById('thumbnails-track');
            const scrollAmount = 200; 
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

        // 6. Load YouTube Script
        if (mediaList.some(m => m.type === 'video')) {
            let tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            let firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }
    </script>
</body>
</html>