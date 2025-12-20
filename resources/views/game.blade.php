<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game->title ?? 'Game Details' }} | PlayVision</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* Rate Styles */
        .rate { float: left; height: 46px; padding: 0 10px; }
        .rate:not(:checked) > input { position:absolute; top:-9999px; }
        .rate:not(:checked) > label { float:right; width:1em; overflow:hidden; white-space:nowrap; cursor:pointer; font-size:30px; color:#ccc; }
        .rate:not(:checked) > label:before { content: '★ '; }
        .rate > input:checked ~ label { color: #ffc700; }
        .rate:not(:checked) > label:hover,
        .rate:not(:checked) > label:hover ~ label { color: #deb217; }
        .rate > input:checked + label:hover,
        .rate > input:checked + label:hover ~ label,
        .rate > input:checked ~ label:hover,
        .rate > input:checked ~ label:hover ~ label,
        .rate > label:hover ~ input:checked ~ label { color: #c59b08; }

        body { background-color: #0f0f0f; color: #e5e5e5; }
        
        .custom-scroll::-webkit-scrollbar { height: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #1a1a1a; border-radius: 4px; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #333; border-radius: 4px; border: 2px solid #1a1a1a; }
        .custom-scroll::-webkit-scrollbar-thumb:hover { background: #0078F2; }

        .no-scrollbar::-webkit-scrollbar { display: none; }
        .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
        
        .active-thumb { border-color: #0078F2 !important; box-shadow: 0 0 20px rgba(0, 120, 242, 0.5); opacity: 1 !important; transform: scale(1.05); z-index: 10; }
        .active-thumb img { opacity: 1 !important; }
        
        .play-overlay { background: rgba(0, 0, 0, 0.6); backdrop-filter: blur(2px); transition: all 0.3s; }
        .active-thumb .play-overlay { background: rgba(0, 212, 255, 0.2); }

        .force-hidden { display: none !important; visibility: hidden !important; z-index: -50 !important; }

        .mask-image-gradient {
            -webkit-mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
            mask-image: linear-gradient(to bottom, black 50%, transparent 100%);
        }
    </style>

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
</head>
<body class="antialiased min-h-screen flex flex-col font-sans bg-[#0f0f0f] overflow-x-hidden selection:bg-brand selection:text-white">

    {{-- DATA LOGIC (PHP) --}}
    @php
        $video_id = null;
        if(isset($game->trailer) && !empty($game->trailer)) {
            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $game->trailer, $match)) {
                $video_id = $match[1];
            }
        }
        
        $mainImg = $game->banner ?? $game->img ?? 'https://via.placeholder.com/800x450';
        $mediaList = [];

        if($video_id) {
            $mediaList[] = ['type' => 'video', 'src' => $video_id, 'thumb' => $game->img];
        }

        $screenshots = $game->screenshots;
        if (is_string($screenshots)) {
            $decoded = json_decode($screenshots, true);
            if (json_last_error() === JSON_ERROR_NONE) {
                $screenshots = $decoded;
            }
        }

        if(isset($screenshots) && is_array($screenshots)) {
            foreach($screenshots as $shot) {
                if(!empty($shot)) {
                    $mediaList[] = ['type' => 'image', 'src' => $shot, 'thumb' => $shot];
                }
            }
        }
        
        if(empty($mediaList) && $game->img) {
            $mediaList[] = ['type' => 'image', 'src' => $game->img, 'thumb' => $game->img];
        }

        // ҮНЭЛГЭЭ ТООЦООЛОХ
        $ratingCount = 0;
        $averageRating = 0;
        $displayRating = 'N/A';

        if($game->relationLoaded('reviews') || $game->reviews) {
            $ratedReviews = $game->reviews->whereNotNull('rating');
            $ratingCount = $ratedReviews->count();
            
            if($ratingCount > 0) {
                $averageRating = $ratedReviews->avg('rating');
                $displayRating = number_format($averageRating, 1);
            }
        }
    @endphp

    {{-- BACKGROUND --}}
    <div class="fixed top-0 left-0 w-full h-[60vh] md:h-[80vh] z-0 pointer-events-none">
        <img src="{{ $mainImg }}" class="w-full h-full object-cover opacity-30 blur-sm mask-image-gradient">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-[#0f0f0f]/80 to-transparent"></div>
    </div>

    {{-- NAVBAR --}}
    <nav class="fixed w-full z-50 top-0 bg-[#0f0f0f]/80 backdrop-blur-xl border-b border-white/5 transition-all duration-300">
        <div class="max-w-[1600px] mx-auto px-4 md:px-6 h-16 md:h-20 flex items-center justify-between">
            <a href="/" class="group flex items-center gap-2">
                <div class="w-7 h-7 md:w-8 md:h-8 bg-brand rounded-lg flex items-center justify-center text-white font-black italic shadow-[0_0_15px_rgba(0,120,242,0.5)]">P</div>
                <span class="text-lg md:text-xl font-bold tracking-tight text-white">Play<span class="text-brand">Vision</span></span>
            </a>
            <div class="flex items-center gap-4 md:gap-8">
                <a href="/" class="text-xs md:text-sm font-medium text-gray-400 hover:text-white transition-colors">STORE</a>
                @auth
                    @if(Auth::user()->usertype === 'admin')
                        <a href="{{ url('/admin/dashboard') }}" class="text-xs md:text-sm font-bold text-brand hover:text-white border border-brand/50 px-3 md:px-4 py-1.5 rounded-full">ADMIN</a>
                    @else
                        <a href="{{ route('dashboard') }}" class="text-xs md:text-sm font-bold text-white hover:text-brand border border-white/20 px-3 md:px-4 py-1.5 rounded-full">DASHBOARD</a>
                    @endif
                @else
                    <a href="{{ route('login') }}" class="px-4 md:px-5 py-1.5 md:py-2 rounded-full border border-white/10 bg-white/5 text-xs md:text-sm font-bold text-white hover:bg-white/10">LOGIN</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="pt-24 md:pt-32 pb-20 flex-grow relative z-10 animate-fade-in">
        <div class="max-w-[1400px] mx-auto px-4 md:px-6">
            
            {{-- TITLE HEADER --}}
            <div class="mb-6 md:mb-10">
                <div class="flex flex-wrap items-center gap-2 md:gap-3 mb-3 md:mb-4">
                    
                    {{-- Average Rating Badge --}}
                    <div class="flex items-center gap-1 bg-yellow-500/10 border border-yellow-500/20 px-2 py-1 rounded text-yellow-400 font-bold text-xs md:text-sm" title="{{ $ratingCount }} ratings">
                        <span>★</span> {{ $displayRating }} <span class="text-[10px] text-gray-500 ml-1">({{ $ratingCount }})</span>
                    </div>

                    @if(isset($game->categories))
                        @foreach($game->categories as $category)
                            <span class="relative group/cat px-2 md:px-3 py-1 rounded-md text-[9px] md:text-[10px] uppercase font-black tracking-widest border border-brand/30 bg-brand/5 text-brand cursor-default overflow-hidden">
                                <span class="relative z-10">#{{ $category->name }}</span>
                                <div class="absolute inset-0 bg-brand/10 translate-y-full group-hover/cat:translate-y-0 transition-transform duration-300"></div>
                            </span>
                        @endforeach
                    @endif
                </div>
                <h1 class="text-3xl sm:text-5xl md:text-7xl font-black text-white tracking-tight drop-shadow-2xl uppercase italic break-words leading-tight">{{ $game->title }}</h1>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 lg:gap-10 items-start relative">
                
                {{-- LEFT COLUMN --}}
                <div class="lg:col-span-8 w-full min-w-0 flex flex-col gap-6">
                    
                    {{-- 1. MAIN DISPLAY --}}
                    <div class="aspect-video w-full rounded-xl md:rounded-2xl overflow-hidden bg-black shadow-2xl ring-1 ring-white/10 relative group z-20">
                        <div id="player-container" class="w-full h-full absolute inset-0 z-30 {{ (count($mediaList) > 0 && $mediaList[0]['type'] == 'video') ? '' : 'force-hidden' }}">
                            <div id="youtube-player"></div>
                        </div>
                        
                        <img id="mainImage" 
                            onclick="nextMedia()" 
                            src="{{ (count($mediaList) > 0) ? (($mediaList[0]['type'] == 'video' && isset($mediaList[1])) ? $mediaList[1]['src'] : $mediaList[0]['src']) : $mainImg }}" 
                            class="w-full h-full object-cover transition-opacity duration-500 z-20 absolute inset-0 cursor-pointer hover:opacity-90 active:scale-[0.98] transition-all {{ (count($mediaList) > 0 && $mediaList[0]['type'] == 'video') ? 'force-hidden' : '' }}">
                        
                        @if(count($mediaList) > 1)
                        <div class="hidden md:block absolute right-4 bottom-4 bg-black/60 backdrop-blur px-3 py-1 rounded-full text-[10px] text-white/70 pointer-events-none opacity-0 group-hover:opacity-100 transition border border-white/10 uppercase font-bold tracking-widest">
                            Next Media ➔
                        </div>
                        @endif
                    </div>

                    {{-- 2. THUMBNAILS CAROUSEL --}}
                    @if(count($mediaList) > 1)
                    <div class="w-full relative group/thumbs mt-2">
                        <div class="absolute left-0 top-0 bottom-0 w-8 md:w-12 bg-gradient-to-r from-[#0f0f0f] to-transparent z-10 pointer-events-none"></div>
                        <div class="absolute right-0 top-0 bottom-0 w-8 md:w-12 bg-gradient-to-l from-[#0f0f0f] to-transparent z-10 pointer-events-none"></div>

                        <button onclick="scrollThumbs('left')" class="hidden md:flex absolute left-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/50 backdrop-blur-md border border-white/10 rounded-full items-center justify-center opacity-0 group-hover/thumbs:opacity-100 transition hover:bg-brand hover:scale-110">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        </button>
                        <button onclick="scrollThumbs('right')" class="hidden md:flex absolute right-2 top-1/2 -translate-y-1/2 z-20 w-10 h-10 bg-black/50 backdrop-blur-md border border-white/10 rounded-full items-center justify-center opacity-0 group-hover/thumbs:opacity-100 transition hover:bg-brand hover:scale-110">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>

                        <div id="thumbnails-track" class="flex gap-2 md:gap-3 overflow-x-auto px-4 py-2 scroll-smooth custom-scroll snap-x snap-mandatory pb-4">
                            @foreach($mediaList as $index => $media)
                                <button onclick="setMedia({{ $index }})" 
                                        id="thumb-{{ $index }}"
                                        class="thumb-item snap-center w-20 h-12 md:w-36 md:h-20 flex-shrink-0 rounded-lg md:rounded-xl overflow-hidden border-2 {{ $index == 0 ? 'border-brand active-thumb' : 'border-transparent' }} bg-surface relative cursor-pointer hover:border-white/50 transition-all duration-300 transform hover:scale-105 group/thumb">
                                    <img src="{{ $media['thumb'] }}" class="w-full h-full object-cover opacity-60 hover:opacity-100 transition-opacity">
                                    
                                    @if($media['type'] == 'video')
                                        <div class="absolute inset-0 flex items-center justify-center play-overlay">
                                            <div class="w-6 h-6 md:w-8 md:h-8 rounded-full bg-white/20 backdrop-blur-sm flex items-center justify-center border border-white/50 group-hover/thumb:scale-110 transition-transform">
                                                <svg class="w-3 h-3 md:w-4 md:h-4 text-white ml-0.5" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
                                            </div>
                                        </div>
                                    @endif
                                </button>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    {{-- 3. DESCRIPTION --}}
                    <div class="bg-surface/50 backdrop-blur-sm rounded-xl md:rounded-2xl p-6 md:p-8 border border-white/5">
                        <h3 class="text-lg md:text-xl font-bold text-white mb-4 flex items-center gap-3">
                            <svg class="w-5 h-5 md:w-6 md:h-6 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            Тоглоомын тухай
                        </h3>
                        <div class="text-gray-300 leading-relaxed text-sm md:text-base font-light break-words">
                            {{ $game->description ?? 'No description.' }}
                        </div>
                    </div>

                    {{-- 4. REVIEWS & RATING SECTION --}}
                    <div id="reviews-section" class="w-full mt-8 md:mt-12">
                        <h3 class="text-xl md:text-2xl font-bold text-white mb-6 border-l-4 border-yellow-500 pl-4">Сэтгэгдэл & Үнэлгээ</h3>

                        @if(session('success'))
                            <div class="bg-green-500/20 text-green-400 p-3 rounded-lg mb-4 text-sm font-bold border border-green-500/50">
                                {{ session('success') }}
                            </div>
                        @endif

                        @auth
                            <div class="bg-surface/50 p-6 rounded-xl border border-white/5 mb-8">
                                <h4 class="text-white font-bold mb-4 flex justify-between items-center">
                                    <span>Сэтгэгдэл үлдээх</span>
                                    <span class="text-xs text-gray-500 font-normal">(* Үнэлгээ өгөх нь заавал биш)</span>
                                </h4>
                                <form action="{{ route('reviews.store') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    
                                    <div class="rate mb-4">
                                        <input type="radio" id="star5" name="rating" value="5" />
                                        <label for="star5" title="5 stars">5 stars</label>
                                        <input type="radio" id="star4" name="rating" value="4" />
                                        <label for="star4" title="4 stars">4 stars</label>
                                        <input type="radio" id="star3" name="rating" value="3" />
                                        <label for="star3" title="3 stars">3 stars</label>
                                        <input type="radio" id="star2" name="rating" value="2" />
                                        <label for="star2" title="2 stars">2 stars</label>
                                        <input type="radio" id="star1" name="rating" value="1" />
                                        <label for="star1" title="1 star">1 star</label>
                                    </div>

                                    <textarea name="comment" rows="3" required class="w-full bg-black/40 border border-white/10 rounded-xl px-4 py-3 text-sm focus:border-brand focus:outline-none text-white resize-none placeholder-gray-600 mt-2" placeholder="Энэ тоглоомын талаар сэтгэгдлээ бичнэ үү..."></textarea>
                                    
                                    <button type="submit" class="mt-4 bg-brand hover:bg-brandHover text-black font-bold py-2 px-6 rounded-lg transition-colors text-sm uppercase tracking-wider">
                                        Илгээх
                                    </button>
                                </form>
                            </div>
                        @else
                            <div class="bg-surface/50 p-6 rounded-xl border border-white/5 mb-8 text-center">
                                <p class="text-gray-400 text-sm">Сэтгэгдэл үлдээхийн тулд <a href="{{ route('login') }}" class="text-brand hover:underline font-bold">нэвтэрнэ үү</a>.</p>
                            </div>
                        @endauth

                        {{-- Review List --}}
                        <div class="space-y-4">
                            @if(isset($game->reviews) && $game->reviews->count() > 0)
                                @foreach($game->reviews as $review)
                                    <div class="bg-white/5 p-4 rounded-xl border border-white/10 relative group/review">
                                        
                                        {{-- 1. Display Mode --}}
                                        <div id="review-display-{{ $review->id }}">
                                            <div class="flex justify-between items-start mb-2">
                                                <div class="flex items-center gap-3">
                                                    <div class="w-10 h-10 rounded-full bg-brand/20 flex items-center justify-center text-brand font-black text-sm uppercase border border-brand/30">
                                                        {{ substr($review->user->name ?? 'U', 0, 1) }}
                                                    </div>
                                                    <div>
                                                        <div class="text-white text-sm font-bold">{{ $review->user->name ?? 'User' }}</div>
                                                        @if($review->rating)
                                                            <div class="text-yellow-500 text-sm tracking-widest">
                                                                @for($i = 0; $i < $review->rating; $i++) ★ @endfor
                                                            </div>
                                                        @else
                                                            <div class="text-gray-600 text-xs italic">Үнэлгээгүй</div>
                                                        @endif
                                                    </div>
                                                </div>
                                                
                                                <div class="flex items-center gap-3">
                                                    <div class="text-gray-600 text-xs font-mono">{{ $review->created_at->diffForHumans() }}</div>
                                                    
                                                    {{-- Edit/Delete Buttons --}}
                                                    @if(auth()->id() === $review->user_id)
                                                        <div class="flex gap-2">
                                                            <button onclick="toggleEdit({{ $review->id }})" class="text-blue-500 hover:text-white text-xs uppercase font-bold transition">Засах</button>
                                                            <form action="{{ route('reviews.destroy', $review->id) }}" method="POST" onsubmit="return confirm('Устгах уу?');">
                                                                @csrf
                                                                @method('DELETE')
                                                                <button type="submit" class="text-red-500 hover:text-white text-xs uppercase font-bold transition">Устгах</button>
                                                            </form>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                            <p class="text-gray-300 text-sm pl-12 border-l-2 border-white/10 ml-1 break-words">{{ $review->comment }}</p>
                                        </div>

                                        {{-- 2. Edit Mode --}}
                                        @if(auth()->id() === $review->user_id)
                                            <div id="review-edit-{{ $review->id }}" class="hidden">
                                                <form action="{{ route('reviews.update', $review->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    
                                                    <div class="rate mb-2 scale-75 origin-left">
                                                        @for($i=5; $i>=1; $i--)
                                                            <input type="radio" id="star{{$i}}-{{$review->id}}" name="rating" value="{{$i}}" {{ $review->rating == $i ? 'checked' : '' }} />
                                                            <label for="star{{$i}}-{{$review->id}}">{{$i}} stars</label>
                                                        @endfor
                                                    </div>

                                                    <textarea name="comment" rows="3" required class="w-full bg-black/40 border border-brand/50 rounded-xl px-4 py-3 text-sm focus:outline-none text-white resize-none mt-2">{{ $review->comment }}</textarea>
                                                    
                                                    <div class="flex gap-2 mt-2 justify-end">
                                                        <button type="button" onclick="toggleEdit({{ $review->id }})" class="text-gray-400 hover:text-white text-xs font-bold px-3 py-1">Болих</button>
                                                        <button type="submit" class="bg-brand hover:bg-white text-black font-bold text-xs px-4 py-1.5 rounded transition">Хадгалах</button>
                                                    </div>
                                                </form>
                                            </div>
                                        @endif

                                    </div>
                                @endforeach
                            @else
                                <div class="text-center py-8 opacity-50">
                                    <div class="text-4xl mb-2">💬</div>
                                    <p class="text-gray-500 text-sm italic">Одоогоор сэтгэгдэл байхгүй байна. Та анхны сэтгэгдлийг бичээрэй!</p>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 5. SPECS (FIXED LAYOUT) --}}
                    <div class="w-full mt-8 md:mt-12 mb-8 md:mb-12">
                        <h3 class="text-xl md:text-2xl font-bold text-white mb-6 border-l-4 border-brand pl-4">System Requirements</h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                            
                            {{-- Minimum --}}
                            <div class="bg-white/5 p-5 md:p-6 rounded-xl border border-white/10">
                                <h4 class="text-red-500 font-bold uppercase tracking-widest text-xs mb-4 flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full bg-red-500"></span> Minimum
                                </h4>
                                <ul class="space-y-4 text-sm">
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-white/5 flex items-center justify-center text-gray-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">OS</div>
                                            <div class="text-sm text-gray-200 leading-tight break-words">{{ $game->min_os ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-white/5 flex items-center justify-center text-gray-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Processor</div>
                                            <div class="text-sm text-gray-200 leading-tight break-words">{{ $game->min_cpu ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-white/5 flex items-center justify-center text-gray-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Memory</div>
                                            <div class="text-sm text-gray-200 leading-tight break-words">{{ $game->min_ram ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-white/5 flex items-center justify-center text-gray-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Graphics</div>
                                            <div class="text-sm text-gray-200 leading-tight break-words">{{ $game->min_gpu ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-white/5 flex items-center justify-center text-gray-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Storage</div>
                                            <div class="text-sm text-gray-200 leading-tight break-words">{{ $game->min_storage ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            {{-- Recommended --}}
                            <div class="bg-gradient-to-br from-surface to-surface/50 p-5 md:p-6 rounded-xl border border-green-500/20">
                                <div class="flex items-center gap-2 mb-6 border-b border-white/5 pb-3">
                                    <span class="w-2 h-2 rounded-full bg-green-500 shadow-[0_0_10px_rgba(34,197,94,0.8)] animate-pulse"></span>
                                    <h4 class="text-green-400 font-bold uppercase tracking-widest text-xs">Зөвлөмжит (Recommended)</h4>
                                </div>
                                <ul class="space-y-4 text-sm">
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-green-500/10 flex items-center justify-center text-green-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">OS</div>
                                            <div class="text-sm text-white font-medium leading-tight break-words">{{ $game->rec_os ?? $game->min_os }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-green-500/10 flex items-center justify-center text-green-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Processor</div>
                                            <div class="text-sm text-white font-medium leading-tight break-words">{{ $game->rec_cpu ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-green-500/10 flex items-center justify-center text-green-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Memory</div>
                                            <div class="text-sm text-white font-medium leading-tight break-words">{{ $game->rec_ram ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-green-500/10 flex items-center justify-center text-green-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Graphics</div>
                                            <div class="text-sm text-white font-medium leading-tight break-words">{{ $game->rec_gpu ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                    <li class="flex items-start gap-3">
                                        <div class="w-8 h-8 rounded bg-green-500/10 flex items-center justify-center text-green-500 shrink-0">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" /></svg>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <div class="text-[10px] md:text-xs font-bold text-gray-500 uppercase tracking-wider mb-0.5">Storage</div>
                                            <div class="text-sm text-white font-medium leading-tight break-words">{{ $game->rec_storage ?? 'Not specified' }}</div>
                                        </div>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- RIGHT: BUY CARD (Sticky only on desktop) --}}
                <div class="lg:col-span-4 h-fit space-y-6">
                    <div class="bg-surface/90 backdrop-blur-md p-6 rounded-2xl border border-white/10 shadow-2xl relative overflow-hidden lg:sticky lg:top-24">
                        <div class="absolute -top-10 -right-10 w-32 h-32 bg-brand/20 blur-3xl rounded-full pointer-events-none"></div>

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

                        <div class="space-y-3 mb-6 relative z-10">
                            @php
                                $user = auth()->user();
                                $userOwnsGame = false;
                                if($user) {
                                    if($user->usertype === 'admin') {
                                        $userOwnsGame = true; 
                                    }
                                }
                            @endphp

                            @if($userOwnsGame)
                                <a href="{{ route('game.download', $game->id) }}" class="block w-full text-center bg-green-600 hover:bg-green-500 text-white font-black py-4 rounded-xl uppercase tracking-wider transition-all shadow-[0_0_20px_rgba(34,197,94,0.4)] hover:shadow-[0_0_30px_rgba(34,197,94,0.6)] transform active:scale-[0.98] flex items-center justify-center gap-2">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                    ТАТАХ (Download)
                                </a>
                            @elseif($isComingSoon)
                                <button disabled class="w-full bg-white/5 text-gray-500 font-bold py-4 rounded-xl cursor-not-allowed border border-white/5 uppercase tracking-wide">Not Available</button>
                            @else
                                <form action="{{ route('checkout.index') }}" method="GET">
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    <button type="submit" class="w-full bg-brand hover:bg-brandHover text-black font-black py-4 rounded-xl uppercase tracking-wider transition-all shadow-[0_0_20px_rgba(0,120,242,0.4)] hover:shadow-[0_0_30px_rgba(0,120,242,0.6)] transform active:scale-[0.98]">
                                        Худалдаж авах
                                    </button>
                                </form>

                                <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
                                    @csrf
                                    <input type="hidden" name="game_id" value="{{ $game->id }}">
                                    <button type="submit" class="w-full bg-white/5 hover:bg-white/10 text-white font-bold py-3 rounded-xl transition-colors border border-white/5 uppercase text-xs tracking-widest">
                                        Сагсанд хийх
                                    </button>
                                </form>
                            @endif
                        </div>

                        <div class="border-t border-white/10 pt-4 space-y-3 text-sm text-gray-400">
                            <div class="flex justify-between"><span>Developer</span> <span class="text-white font-medium">{{ $game->developer ?? 'PlayVision' }}</span></div>
                            <div class="flex justify-between"><span>Publisher</span> <span class="text-white font-medium">{{ $game->publisher ?? 'PlayVision' }}</span></div>
                            <div class="flex justify-between"><span>Release Date</span> <span class="text-white font-medium">{{ $game->release_date ? \Carbon\Carbon::parse($game->release_date)->format('Y-m-d') : 'TBA' }}</span></div>
                        </div>
                    </div>
                </div>

            </div> 
        </div>

        {{-- RELATED GAMES --}}
        @if(isset($relatedGames) && $relatedGames->count() > 0)
            <div class="max-w-[1400px] mx-auto px-6 mt-20 border-t border-white/5 pt-10">
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

    </main>

    <footer class="border-t border-white/5 py-12 text-center bg-[#0a0a0a]">
        <p class="text-gray-600 text-sm">&copy; 2025 PlayVision. All rights reserved.</p>
    </footer>

    {{-- JAVASCRIPT --}}
    <script>
        const mediaList = @json($mediaList);
        let currentIndex = 0;
        let player;

        function onYouTubeIframeAPIReady() {
            if (mediaList[0] && mediaList[0].type === 'video') {
                player = new YT.Player('youtube-player', {
                    width: '100%', height: '100%', videoId: mediaList[0].src,
                    playerVars: { autoplay: 1, mute: 1, controls: 1, rel: 0, playsinline: 1, modestbranding: 1 },
                    events: { onStateChange: onPlayerStateChange }
                });
            }
        }

        function onPlayerStateChange(event) {
            if (event.data === YT.PlayerState.ENDED) { nextMedia(); }
        }

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

        function nextMedia() {
            let nextIndex = currentIndex + 1;
            if (nextIndex >= mediaList.length) {
                nextIndex = 0;
            }
            setMedia(nextIndex);
        }

        function scrollThumbs(direction) {
            const container = document.getElementById('thumbnails-track');
            const scrollAmount = 200; 
            if (direction === 'left') {
                container.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
            } else {
                container.scrollBy({ left: scrollAmount, behavior: 'smooth' });
            }
        }

        // --- NEW: TOGGLE EDIT FUNCTION ---
        function toggleEdit(reviewId) {
            const displayDiv = document.getElementById('review-display-' + reviewId);
            const editDiv = document.getElementById('review-edit-' + reviewId);
            
            if (displayDiv.classList.contains('hidden')) {
                displayDiv.classList.remove('hidden');
                editDiv.classList.add('hidden');
            } else {
                displayDiv.classList.add('hidden');
                editDiv.classList.remove('hidden');
            }
        }

        if (mediaList.some(m => m.type === 'video')) {
            let tag = document.createElement('script');
            tag.src = "https://www.youtube.com/iframe_api";
            let firstScriptTag = document.getElementsByTagName('script')[0];
            firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
        }
    </script>
</body>
</html>r