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
                        surfaceHover: '#2a2a2a',
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #121212; color: #f5f5f5; }
        .custom-scroll::-webkit-scrollbar { width: 6px; }
        .custom-scroll::-webkit-scrollbar-track { background: #121212; }
        .custom-scroll::-webkit-scrollbar-thumb { background: #444; border-radius: 3px; }
        .active-thumb { border-color: #0078F2 !important; opacity: 1 !important; }
        .prose p { margin-bottom: 1.5em; line-height: 1.6; color: #d1d5db; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col font-sans relative overflow-x-hidden bg-[#121212]">

    <div class="absolute top-0 left-0 w-full h-[800px] z-0 pointer-events-none overflow-hidden">
        <div class="relative w-full h-full">
            @php 
                $bgImage = $game->banner ?? $game->img ?? null; 
                if($bgImage && !str_starts_with($bgImage, 'http')) {
                    $bgImage = asset($bgImage);
                }
            @endphp
            
            @if($bgImage)
                <img src="{{ $bgImage }}" class="w-full h-full object-cover opacity-20 blur-[60px] scale-110">
            @else
                <div class="w-full h-full bg-gradient-to-b from-blue-900/20 to-dark"></div>
            @endif
            
            <div class="absolute inset-0 bg-gradient-to-b from-[#121212]/20 via-[#121212]/80 to-[#121212]"></div>
        </div>
    </div>

    <nav class="fixed w-full z-50 top-0 bg-[#121212]/90 backdrop-blur-md border-b border-white/10">
        <div class="max-w-[1600px] mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="text-2xl font-black tracking-tighter uppercase italic">
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
        <div class="max-w-[1280px] mx-auto px-6 lg:px-8">
            
            <div class="mb-10">
                <h1 class="text-5xl md:text-6xl font-black text-white mb-4 drop-shadow-2xl tracking-tight">{{ $game->title ?? 'Unknown Game' }}</h1>
                <div class="flex items-center gap-4 text-sm bg-black/30 backdrop-blur-md inline-flex px-4 py-2 rounded-full border border-white/5">
                    <div class="flex text-yellow-500 gap-1">
                        <span class="text-white font-bold">★ {{ $game->rating ?? 'N/A' }}</span>
                    </div>
                    @if(isset($game->category))
                        <span class="text-gray-500">|</span>
                        <span class="text-gray-300">{{ $game->category->name }}</span>
                    @endif
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 relative">
                
                <div class="lg:col-span-8 space-y-12">
                    
                    <div class="space-y-4">
                        @php
                            $video_id = null;
                            if(isset($game->trailer) && !empty($game->trailer)) {
                                if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $game->trailer, $match)) {
                                    $video_id = $match[1];
                                }
                            }
                            $mainImg = $bgImage ?? 'https://via.placeholder.com/800x450?text=No+Image';
                        @endphp

                        <div class="aspect-video w-full rounded-2xl overflow-hidden bg-black relative shadow-2xl ring-1 ring-white/10 group">
                            @if($video_id)
                                <iframe id="mainVideo" class="w-full h-full absolute inset-0" src="https://www.youtube.com/embed/{{ $video_id }}?autoplay=1&mute=1&rel=0&controls=1" frameborder="0" allowfullscreen></iframe>
                                <img id="mainImage" src="{{ $mainImg }}" class="w-full h-full object-cover hidden">
                            @else
                                <img id="mainImage" src="{{ $mainImg }}" class="w-full h-full object-cover">
                                <iframe id="mainVideo" class="w-full h-full absolute inset-0 hidden" src="" frameborder="0" allowfullscreen></iframe>
                            @endif
                        </div>

                        <div class="flex gap-3 overflow-x-auto pb-2 custom-scroll">
                            @if($video_id)
                                <button onclick="showVideo('{{ $game->trailer }}', this)" class="thumb-item w-28 h-16 flex-shrink-0 rounded-lg overflow-hidden border-2 border-brand relative bg-black active-thumb transition-all">
                                    <div class="absolute inset-0 flex items-center justify-center bg-black/40"><svg class="w-8 h-8 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg></div>
                                    <img src="{{ $game->img ?? $mainImg }}" class="w-full h-full object-cover opacity-60">
                                </button>
                            @endif

                            <button onclick="showImage('{{ $mainImg }}', this)" class="thumb-item w-28 h-16 flex-shrink-0 rounded-lg overflow-hidden border-2 border-transparent hover:border-brand transition-all {{ !$video_id ? 'active-thumb' : '' }}">
                                <img src="{{ $mainImg }}" class="w-full h-full object-cover opacity-80 hover:opacity-100">
                            </button>

                            @if(!empty($game->screenshot1))
                            <button onclick="showImage('{{ str_starts_with($game->screenshot1, 'http') ? $game->screenshot1 : asset($game->screenshot1) }}', this)" class="thumb-item w-28 h-16 flex-shrink-0 rounded-lg overflow-hidden border-2 border-transparent hover:border-brand transition-all">
                                <img src="{{ str_starts_with($game->screenshot1, 'http') ? $game->screenshot1 : asset($game->screenshot1) }}" class="w-full h-full object-cover opacity-80 hover:opacity-100">
                            </button>
                            @endif
                            @if(!empty($game->screenshot2))
                            <button onclick="showImage('{{ str_starts_with($game->screenshot2, 'http') ? $game->screenshot2 : asset($game->screenshot2) }}', this)" class="thumb-item w-28 h-16 flex-shrink-0 rounded-lg overflow-hidden border-2 border-transparent hover:border-brand transition-all">
                                <img src="{{ str_starts_with($game->screenshot2, 'http') ? $game->screenshot2 : asset($game->screenshot2) }}" class="w-full h-full object-cover opacity-80 hover:opacity-100">
                            </button>
                            @endif
                        </div>
                    </div>

                    <div>
                        <h2 class="text-xl font-bold text-white mb-4 pl-4 border-l-4 border-brand">About This Game</h2>
                        <div class="bg-surface/40 backdrop-blur-md p-8 rounded-2xl border border-white/5 shadow-inner text-gray-300 leading-relaxed whitespace-pre-line">
                            {{ $game->description ?? 'No description available.' }}
                        </div>
                    </div>

                    <div class="bg-surface/50 backdrop-blur-md rounded-2xl p-8 border border-white/5 shadow-xl">
                        <h2 class="text-xl font-bold text-white mb-6">System Requirements</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10 text-sm">
                            <div class="space-y-4">
                                <h3 class="text-gray-400 font-bold uppercase text-xs tracking-wider border-b border-white/10 pb-2">Minimum</h3>
                                <div><span class="block text-gray-500 mb-1">OS</span><span class="text-white">{{ $game->min_os ?? '-' }}</span></div>
                                <div><span class="block text-gray-500 mb-1">Processor</span><span class="text-white">{{ $game->min_cpu ?? '-' }}</span></div>
                                <div><span class="block text-gray-500 mb-1">Memory</span><span class="text-white">{{ $game->min_ram ?? '-' }}</span></div>
                                <div><span class="block text-gray-500 mb-1">Storage</span><span class="text-white">{{ $game->min_storage ?? '-' }}</span></div>
                            </div>
                            <div class="space-y-4">
                                <h3 class="text-brand font-bold uppercase text-xs tracking-wider border-b border-brand/30 pb-2">Recommended</h3>
                                <div><span class="block text-gray-500 mb-1">OS</span><span class="text-white">{{ $game->min_os ?? '-' }}</span></div>
                                <div><span class="block text-gray-500 mb-1">Processor</span><span class="text-white">{{ $game->min_cpu ?? '-' }}</span></div>
                                <div><span class="block text-gray-500 mb-1">Graphics</span><span class="text-white">{{ $game->min_gpu ?? '-' }}</span></div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="lg:col-span-4 relative">
                    <div class="sticky top-28 space-y-6 bg-surface/30 p-6 rounded-2xl border border-white/5 backdrop-blur-md shadow-2xl">
                        
                        @if(!empty($game->tag))
                            @if($game->tag == 'Тун удахгүй')
                                <div class="inline-block bg-brand/10 px-3 py-1 rounded text-[10px] font-black uppercase text-brand border border-brand/20 shadow-[0_0_10px_rgba(0,212,255,0.2)] animate-pulse">
                                    COMING SOON
                                </div>
                            @else
                                <div class="inline-block bg-white/10 px-3 py-1 rounded text-[10px] font-bold uppercase text-white border border-white/10 shadow-sm">
                                    {{ $game->tag }}
                                </div>
                            @endif
                        @endif

                        <div class="flex items-center gap-3">
                            @php 
                                $isPriceNumeric = is_numeric($game->price);
                                $isComingSoon = ($game->tag == 'Тун удахгүй' || !$isPriceNumeric);
                            @endphp

                            @if($isComingSoon)
                                <span class="text-3xl font-black text-white">COMING SOON</span>
                            @elseif($game->price == 0)
                                <span class="text-3xl font-black text-white">Free</span>
                            @elseif(!empty($game->sale_price) && is_numeric($game->sale_price))
                                <span class="bg-brand text-white px-2 py-1 rounded text-xs font-bold">-{{ round((($game->price - $game->sale_price) / $game->price) * 100) }}%</span>
                                <span class="text-gray-500 line-through text-lg">{{ number_format($game->price) }}₮</span>
                                <span class="text-3xl font-black text-white">{{ number_format($game->sale_price) }}₮</span>
                            @else
                                <span class="text-3xl font-black text-white">{{ number_format($game->price) }}₮</span>
                            @endif
                        </div>

                        <div class="space-y-3">
                            @if($isComingSoon)
                                <button disabled class="w-full bg-white/5 text-gray-500 font-bold py-4 rounded-xl uppercase tracking-wide cursor-not-allowed border border-white/5">
                                    Not Available
                                </button>
                            @else
                                <button class="w-full bg-brand hover:bg-brandHover text-white font-bold py-4 rounded-xl uppercase tracking-wide transition-colors shadow-lg shadow-brand/20 transform active:scale-95">
                                    Buy Now
                                </button>
                                <button class="w-full bg-transparent border border-gray-600 hover:bg-white/10 text-white font-bold py-4 rounded-xl uppercase tracking-wide transition-colors">
                                    Add to Cart
                                </button>
                            @endif
                        </div>

                        <div class="space-y-0 pt-4 border-t border-white/10">
                            <div class="flex justify-between py-3 border-b border-white/5">
                                <span class="text-gray-500 text-sm">Developer</span>
                                <span class="text-white text-sm font-medium">{{ $game->developer ?? 'PlayVision' }}</span>
                            </div>
                            <div class="flex justify-between py-3 border-b border-white/5">
                                <span class="text-gray-500 text-sm">Publisher</span>
                                <span class="text-white text-sm font-medium">{{ $game->publisher ?? 'PlayVision' }}</span>
                            </div>
                            
                            <div class="flex justify-between py-3 border-b border-white/5">
                                <span class="text-gray-500 text-sm">Release Date</span>
                                <span class="text-white text-sm font-medium">
                                    
                                    {{-- 1. Хэрэв Админаас огноо оруулсан бол ТЭРИЙГ Л харуулна (Бусдыг нь тоохгүй) --}}
                                    @if(!empty($game->release_date))
                                        {{ \Carbon\Carbon::parse($game->release_date)->format('Y-m-d') }}
                                    
                                    {{-- 2. Огноо байхгүй, гэхдээ "Тун удахгүй" бол Coming Soon гэж бичнэ --}}
                                    @elseif($isComingSoon)
                                        <span class="text-brand font-bold uppercase tracking-wider">Coming Soon</span>
                                    
                                    {{-- 3. Бусад бүх үед Available Now --}}
                                    @else
                                        Available Now
                                    @endif

                                </span>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </main>

    <footer class="bg-surface border-t border-white/10 py-12 mt-auto relative z-10">
        <div class="max-w-[1280px] mx-auto px-6 text-center">
            <p class="text-gray-500 text-xs">&copy; 2025 PlayVision. Designed for Gamers.</p>
        </div>
    </footer>

    <script>
        function setActiveThumb(element) {
            document.querySelectorAll('.thumb-item').forEach(el => {
                el.classList.remove('active-thumb', 'border-brand');
                el.classList.add('border-transparent');
            });
            if(element) {
                element.classList.remove('border-transparent');
                element.classList.add('active-thumb', 'border-brand');
            }
        }

        function showImage(src, element) {
            document.getElementById('mainVideo').classList.add('hidden');
            document.getElementById('mainVideo').src = "";
            const img = document.getElementById('mainImage');
            img.src = src;
            img.classList.remove('hidden');
            setActiveThumb(element);
        }

        function showVideo(url, element) {
            const regExp = /^.*(youtu.be\/|v\/|u\/\w\/|embed\/|watch\?v=|&v=)([^#&?]*).*/;
            const match = url.match(regExp);
            if (match && match[2].length === 11) {
                document.getElementById('mainImage').classList.add('hidden');
                const iframe = document.getElementById('mainVideo');
                iframe.src = `https://www.youtube.com/embed/${match[2]}?autoplay=1&mute=0&rel=0`;
                iframe.classList.remove('hidden');
                setActiveThumb(element);
            }
        }
    </script>
</body>
</html>