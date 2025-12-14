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
                        brandHover: '#005AC1',
                        dark: '#121212', 
                        surface: '#202020', 
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #121212; color: #f5f5f5; }
        .custom-scroll::-webkit-scrollbar { height: 8px; }
        .custom-scroll::-webkit-scrollbar-track { background: #1a1a1a; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #0078F2; }
        
        .active-thumb { 
            border-color: #0078F2 !important; 
            opacity: 1 !important; 
            transform: scale(1.05);
        }
        .active-thumb img { opacity: 1 !important; }
        
        .force-hidden { 
            display: none !important; 
            visibility: hidden !important; 
            z-index: -50 !important;
        }
        
        #player-container iframe {
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
        }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans bg-[#121212] overflow-x-hidden">

    {{-- BACKGROUND BLUR --}}
    <div class="fixed top-0 left-0 w-full h-screen z-0 pointer-events-none">
        @php 
            $bgImage = $game->banner ?? $game->img ?? null; 
        @endphp
        @if($bgImage)
            <img src="{{ $bgImage }}" class="w-full h-full object-cover opacity-[0.15] blur-[80px]">
        @else
            <div class="w-full h-full bg-gradient-to-b from-blue-900/10 to-dark"></div>
        @endif
    </div>

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 top-0 bg-[#121212]/80 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-[1600px] mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl font-black tracking-tighter uppercase italic text-white">
                Play<span class="text-brand">Vision</span>
            </a>
            <div class="flex items-center gap-6">
                <a href="/" class="text-sm font-bold text-gray-400 hover:text-white transition-colors">STORE</a>
                @auth
                    <a href="{{ url('/admin/dashboard') }}" class="text-sm font-bold text-brand hover:text-white transition-colors">ADMIN</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-white hover:text-brand transition-colors">LOGIN</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-32 pb-20 flex-grow relative z-10">
        <div class="max-w-[1400px] mx-auto px-6">
            
            {{-- TITLE HEADER --}}
            <div class="mb-10">
                <h1 class="text-4xl md:text-6xl font-black text-white mb-4 tracking-tight leading-none">{{ $game->title }}</h1>
                <div class="flex items-center gap-4 text-sm">
                    <div class="bg-white/5 backdrop-blur px-3 py-1 rounded-full border border-white/10 flex items-center gap-2">
                        <span class="text-yellow-500 text-lg">★</span>
                        <span class="font-bold">{{ $game->rating ?? 'N/A' }}</span>
                    </div>
                    @if(isset($game->categories) && $game->categories->count() > 0)
                        @foreach($game->categories as $category)
                            <span class="bg-brand/10 text-brand px-3 py-1 rounded-full border border-brand/20 font-bold uppercase text-xs tracking-wider">
                                {{ $category->name }}
                            </span>
                        @endforeach
                    @endif
                </div>
            </div>

            {{-- MAIN GRID: LEFT (Content) & RIGHT (Sidebar) --}}
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-start relative">
                
                {{-- LEFT COLUMN (8 cols) --}}
                <div class="lg:col-span-8 space-y-6 w-full min-w-0">
                    
                    @php
                        $video_id = null;
                        if(isset($game->trailer) && !empty($game->trailer)) {
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $game->trailer, $match)) {
                                $video_id = $match[1];
                            }
                        }
                        $mainImg = $bgImage ?? 'https://via.placeholder.com/800x450';
                        
                        $slides = [];
                        if($video_id) {
                            $slides[] = ['type' => 'video', 'src' => $video_id, 'thumb' => $game->img];
                        }
                        $slides[] = ['type' => 'image', 'src' => $mainImg, 'thumb' => $mainImg];
                        
                        if(is_array($game->screenshots)) {
                            foreach($game->screenshots as $shot) {
                                if(!empty($shot)) {
                                    $slides[] = ['type' => 'image', 'src' => $shot, 'thumb' => $shot];
                                }
                            }
                        }
                    @endphp

                    {{-- MEDIA PLAYER --}}
                    <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black shadow-2xl ring-1 ring-white/10 relative group z-20">
                        <div id="player-container" class="w-full h-full absolute inset-0 z-30 {{ $video_id ? '' : 'force-hidden' }}">
                            <div id="youtube-player"></div>
                        </div>
                        <img id="mainImage" 
                             src="{{ $mainImg }}" 
                             class="w-full h-full object-cover transition-opacity duration-500 z-20 absolute inset-0 {{ $video_id ? 'force-hidden' : '' }}">
                    </div>

                    {{-- THUMBNAILS --}}
                    <div class="w-full">
                        <div class="flex gap-3 overflow-x-auto pb-4 custom-scroll select-none p-1">
                            @if($video_id)
                                <button id="thumb-video"
                                        onclick="playTrailer('{{ $video_id }}', this)" 
                                        class="thumb-item w-40 h-24 flex-shrink-0 rounded-xl overflow-hidden border-2 border-brand bg-black relative active-thumb group cursor-pointer hover:brightness-110 transition">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40 group-hover:bg-black/20 transition z-10 pointer-events-none">
                                        <div class="w-10 h-10 bg-brand/90 rounded-full flex items-center justify-center shadow-lg">
                                            <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                        </div>
                                    </div>
                                    <img src="{{ $game->img }}" class="w-full h-full object-cover opacity-60">
                                </button>
                            @endif

                            <button id="thumb-cover"
                                    onclick="switchImage('{{ $mainImg }}', this)" 
                                    class="thumb-item w-40 h-24 flex-shrink-0 rounded-xl overflow-hidden border-2 border-transparent bg-black relative cursor-pointer hover:border-white/50 transition {{ !$video_id ? 'active-thumb' : '' }}">
                                <img src="{{ $mainImg }}" class="w-full h-full object-cover opacity-70 hover:opacity-100 transition">
                            </button>

                            @if(is_array($game->screenshots) && count($game->screenshots) > 0)
                                @foreach($game->screenshots as $screenshot)
                                    @if(!empty($screenshot))
                                        <button onclick="switchImage('{{ $screenshot }}', this)" 
                                                class="thumb-item w-40 h-24 flex-shrink-0 rounded-xl overflow-hidden border-2 border-transparent bg-black relative cursor-pointer hover:border-white/50 transition">
                                            <img src="{{ $screenshot }}" class="w-full h-full object-cover opacity-70 hover:opacity-100 transition">
                                        </button>
                                    @endif
                                @endforeach
                            @endif
                        </div>
                    </div>

                    {{-- DESCRIPTION --}}
                    <div class="bg-white/5 rounded-2xl p-8 border border-white/5 mt-8">
                        <h3 class="text-xl font-bold text-white mb-4">Тоглоомын тухай</h3>
                        <div class="text-gray-300 leading-relaxed whitespace-pre-line text-sm">
                            {{ $game->description ?? 'No description.' }}
                        </div>
                    </div>

                  
                    <div class="bg-white/5 rounded-2xl p-8 border border-white/5">
                        <h3 class="text-xl font-bold text-white mb-6">Шаардах үзүүлэлт</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <div class="space-y-3">
                                <div class="text-brand font-bold uppercase text-xs mb-3">Хамгийн бага шаардах үзүүлэлт</div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">OS</span> <span>{{ $game->min_os ?? '-' }}</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">CPU</span> <span>{{ $game->min_cpu ?? '-' }}</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">RAM</span> <span>{{ $game->min_ram ?? '-' }}</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">GPU</span> <span>{{ $game->min_gpu ?? '-' }}</span></div>
                            </div>
                            <div class="space-y-3">
                                <div class="text-brand font-bold uppercase text-xs mb-3">Зөвлөмжит</div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">OS</span> <span>{{ $game->min_os ?? '-' }}</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">CPU</span> <span>{{ $game->min_cpu ?? '-' }}</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">RAM</span> <span>{{ $game->ram ?? '-' }}</span></div>
                                <div class="flex justify-between border-b border-white/5 pb-2"><span class="text-gray-500">GPU</span> <span>{{ $game->gpu ?? '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div> 


           
                <div class="lg:col-span-4 h-fit sticky  space-y-6">
<div class="bg-[#1a1a1a]/90 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-2xl">


                        <div class="mb-6">
                            @php 
                                $isPriceNumeric = is_numeric($game->price);
                                $isComingSoon = ($game->tag == 'Тун удахгүй' || !$isPriceNumeric);
                            @endphp

                            @if($isComingSoon)
                                <div class="text-3xl font-black text-white mb-2">COMING SOON</div>
                                <div class="text-gray-500 text-sm">Pre-order details coming soon.</div>
                            @elseif($game->price == 0)
                                <div class="text-3xl font-black text-white">Free to Play</div>
                            @elseif($game->sale_price && is_numeric($game->sale_price))
                                <div class="flex items-center gap-3 mb-1">
                                    <span class="bg-green-500 text-black px-2 py-0.5 rounded text-sm font-bold">-{{ round((($game->price - $game->sale_price) / $game->price) * 100) }}%</span>
                                    <span class="text-gray-500 line-through">{{ number_format($game->price) }}₮</span>
                                </div>
                                <div class="text-3xl font-black text-white">{{ number_format($game->sale_price) }}₮</div>
                            @else
                                <div class="text-3xl font-black text-white">{{ number_format($game->price) }}₮</div>
                            @endif
                        </div>

                        <div class="space-y-3 mb-6">
                            @if($isComingSoon)
                                <button disabled class="w-full bg-white/5 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed">Not Available</button>
                            @else
                                <button class="w-full bg-brand hover:bg-brandHover text-white font-bold py-4 rounded-xl uppercase tracking-wider transition-all shadow-[0_0_20px_rgba(0,120,242,0.3)] hover:shadow-[0_0_30px_rgba(0,120,242,0.5)] transform active:scale-[0.98]">Buy Now</button>
                                <button class="w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-xl transition-colors border border-white/5">Add to Cart</button>
                            @endif
                        </div>

                        <div class="border-t border-white/10 pt-4 space-y-3 text-sm">
                            <div class="flex justify-between"><span class="text-gray-500">Developer</span> <span class="text-gray-300">{{ $game->developer ?? 'PlayVision' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Publisher</span> <span class="text-gray-300">{{ $game->publisher ?? 'PlayVision' }}</span></div>
                            <div class="flex justify-between"><span class="text-gray-500">Release Date</span> <span class="text-gray-300">{{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->format('Y-m-d') : 'TBA' }}</span></div>
                        </div>
                    </div>
                </div> 

            </div> 
            
            
          
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

    <footer class="border-t border-white/5 py-12 text-center bg-[#0f0f0f]">
        <p class="text-gray-600 text-sm">&copy; 2025 PlayVision. All rights reserved.</p>
    </footer>

    {{-- SCRIPTS --}}
    <script>
    let player;
    let videoId = @json($video_id);

    if (videoId) {
        let tag = document.createElement('script');
        tag.src = "https://www.youtube.com/iframe_api";
        let firstScriptTag = document.getElementsByTagName('script')[0];
        firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
    }

    function onYouTubeIframeAPIReady() {
        if (!videoId) return;

player = new YT.Player('youtube-player', {
    width: '100%',
    height: '100%',
    videoId: videoId,
    playerVars: {
        autoplay: 1,
        mute: 1,
        controls: 1,
        rel: 0,
        playsinline: 1
    },
    events: {
        onStateChange: onPlayerStateChange
    }
});
function onPlayerStateChange(event) {
    // VIDEO DUUSAH ҮЕД
    if (event.data === YT.PlayerState.ENDED) {
        const img = document.getElementById('mainImage');
        const playerBox = document.getElementById('player-container');

        // Video-г зогсооно
        player.stopVideo();

        // Player-ийг нууж зураг харуулна
        playerBox.classList.add('force-hidden');
        img.classList.remove('force-hidden');

        // Cover thumbnail-г active болгоно
        const coverThumb = document.getElementById('thumb-cover');
        if (coverThumb) setActive(coverThumb);
    }
}

        
    }

    function playTrailer(id, el) {
        const img = document.getElementById('mainImage');
        const playerBox = document.getElementById('player-container');

        img.classList.add('force-hidden');
        playerBox.classList.remove('force-hidden');

        if (player) {
            player.playVideo();
        }

        setActive(el);
    }

    function switchImage(src, el) {
        const img = document.getElementById('mainImage');
        const playerBox = document.getElementById('player-container');

        if (player) player.stopVideo();

        playerBox.classList.add('force-hidden');
        img.src = src;
        img.classList.remove('force-hidden');

        setActive(el);
    }

    function setActive(el) {
        document.querySelectorAll('.thumb-item').forEach(t => {
            t.classList.remove('active-thumb', 'border-brand');
            t.classList.add('border-transparent');
        });
        el.classList.add('active-thumb', 'border-brand');
    }
</script>

</body>
</html>