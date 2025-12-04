<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game->title }} - PlayVision</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: {
                        brand: '#00D4FF',
                        dark: '#0f0f0f',
                        surface: '#18181b',
                        surfaceHighlight: '#27272a'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white antialiased selection:bg-brand selection:text-black min-h-screen flex flex-col">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 top-0 bg-dark/90 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <a href="/" class="text-2xl font-black tracking-tighter uppercase italic group">
                    Play<span class="text-brand group-hover:text-white transition-colors">Vision</span>
                </a>
                <div class="flex items-center gap-6">
                    <a href="/" class="text-sm font-bold text-gray-400 hover:text-white transition-colors">← Back to Store</a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow pt-20 relative">
        
        <!-- ========================================================================= -->
        <!-- BANNER ХЭСЭГ (Fixed & Parallax effect) -->
        <!-- ========================================================================= -->
        <div class="fixed top-0 left-0 w-full h-full overflow-hidden z-0 pointer-events-none">
            @if(!empty($game->banner))
                <img src="{{ $game->banner }}" class="w-full h-full object-cover opacity-40">
            @else
                <img src="{{ $game->img }}" class="w-full h-full object-cover blur-2xl opacity-20 scale-110">
            @endif
            
            {{-- Градиент уусгалтууд --}}
            <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/80 to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-b from-dark/60 via-transparent to-transparent"></div>
            <div class="absolute inset-0 bg-gradient-to-r from-dark/50 via-transparent to-dark/50"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 py-10">
            
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-12">
                
                <!-- LEFT COLUMN: Image -->
                <div class="lg:col-span-1">
                    <div>
                        {{-- Cover Image --}}
                        <div class="relative group rounded-2xl overflow-hidden shadow-[0_0_40px_rgba(0,0,0,0.5)] border border-white/10">
                            <img src="{{ $game->img }}" alt="{{ $game->title }}" class="w-full h-auto object-cover transform group-hover:scale-105 transition-transform duration-700">
                            
                            @if($game->tag)
                                <div class="absolute top-4 right-4 bg-brand text-black text-xs font-black px-3 py-1 rounded shadow-lg">
                                    {{ $game->tag }}
                                </div>
                            @endif
                        </div>

                        <!-- Short Details Box -->
                        <div class="mt-8 bg-surface/80 backdrop-blur-md rounded-xl p-6 border border-white/5">
                            <h3 class="text-gray-400 text-xs font-bold uppercase mb-4 tracking-wider">Game Details</h3>
                            <div class="space-y-3 text-sm">
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span class="text-gray-500">Developer</span>
                                    <span class="text-white">{{ $game->developer ?? 'PlayVision Studio' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span class="text-gray-500">Publisher</span>
                                    <span class="text-white">{{ $game->publisher ?? 'Global Games' }}</span>
                                </div>
                                <div class="flex justify-between border-b border-white/5 pb-2">
                                    <span class="text-gray-500">Release Date</span>
                                    <span class="text-white">{{ $game->created_at->format('Y-m-d') }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Platform</span>
                                    <div class="flex gap-2 text-gray-300">
                                        Windows
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN: Details -->
                <div class="lg:col-span-2 flex flex-col justify-center">
                    
                    <!-- Breadcrumb & Category -->
                    <div class="flex items-center gap-3 mb-4">
                        <span class="bg-white/10 text-brand px-3 py-1 rounded text-xs font-bold uppercase tracking-wide hover:bg-white/20 cursor-pointer transition-colors">
                            {{ $game->category->name ?? 'Game' }}
                        </span>
                    </div>

                    <!-- Title -->
                    <h1 class="text-4xl md:text-6xl font-black uppercase leading-none mb-6 text-white drop-shadow-2xl">
                        {{ $game->title }}
                    </h1>

                    <!-- Price Block -->
                    <div class="bg-surfaceHighlight/50 border border-white/10 rounded-2xl p-6 mb-8 backdrop-blur-sm shadow-xl">
                        <div class="flex flex-col md:flex-row items-center justify-between gap-6">
                            <div>
                                @if(isset($game->sale_price) && $game->sale_price > 0 && $game->sale_price < $game->price)
                                    <div class="flex items-baseline gap-3">
                                        <span class="text-3xl font-bold text-brand">{{ number_format($game->sale_price) }}₮</span>
                                        <span class="text-lg text-gray-500 line-through decoration-red-500">{{ number_format($game->price) }}₮</span>
                                        <span class="bg-brand text-black text-xs font-bold px-2 py-1 rounded">-{{ round((($game->price - $game->sale_price) / $game->price) * 100) }}%</span>
                                    </div>
                                @elseif($game->price == 0)
                                    <span class="text-3xl font-bold text-brand">Free to Play</span>
                                @else
                                    <span class="text-3xl font-bold text-white">{{ number_format($game->price) }}₮</span>
                                @endif
                            </div>

                            <div class="flex gap-4 w-full md:w-auto">
                                <button class="flex-1 md:flex-none bg-brand text-black px-8 py-4 rounded-xl font-bold text-lg hover:bg-white hover:scale-105 transition-all shadow-[0_0_20px_rgba(0,212,255,0.3)] uppercase tracking-wide">
                                    Buy Now
                                </button>
                                <button class="bg-white/10 text-white p-4 rounded-xl hover:bg-white hover:text-black transition-colors">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Trailer Section (Smart Embed & AutoPlay) -->
                    @if(isset($game->trailer) && !empty($game->trailer))
                        @php
                            $video_id = '';
                            if (preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $game->trailer, $match)) {
                                $video_id = $match[1];
                            }
                        @endphp

                        @if($video_id)
                        <div class="mb-10">
                            <h2 class="text-2xl font-bold text-white mb-4 border-b border-white/10 pb-2 flex items-center gap-2">
                                <svg class="w-6 h-6 text-brand" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                Official Trailer
                            </h2>
                            <div class="relative w-full aspect-video rounded-xl overflow-hidden shadow-2xl border border-white/10 group">
                                {{-- ЗАСВАР: mute=1-ийг авч хаясан (Дуутай). Chrome дээр Autoplay ажиллахгүй байж магадгүй. --}}
                                <iframe 
                                    class="w-full h-full" 
                                    src="https://www.youtube.com/embed/{{ $video_id }}?autoplay=1" 
                                    title="Game Trailer" 
                                    frameborder="0" 
                                    allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" 
                                    allowfullscreen>
                                </iframe>
                            </div>
                        </div>
                        @endif
                    @endif

                    <!-- Description -->
                    <div class="prose prose-invert max-w-none mb-10">
                        <h2 class="text-2xl font-bold text-white mb-4 border-b border-white/10 pb-2">About This Game</h2>
                        <p class="text-gray-300 text-lg leading-relaxed whitespace-pre-line">
                            {{ $game->description ?? 'No description available for this game.' }}
                        </p>
                    </div>

                    <!-- System Requirements -->
                    @if(isset($game->min_os) || isset($game->min_cpu))
                    <div class="bg-black/40 rounded-xl p-6 border border-white/5 shadow-lg">
                        <h2 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                            <svg class="w-5 h-5 text-brand" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            System Requirements
                        </h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                            <div class="space-y-4">
                                <h3 class="font-bold text-gray-400 uppercase tracking-wide border-b border-white/10 pb-1">Minimum</h3>
                                <div class="grid grid-cols-3 gap-2">
                                    <span class="text-gray-500 text-xs uppercase font-bold pt-1">OS</span>
                                    <span class="text-white col-span-2">{{ $game->min_os ?? '-' }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <span class="text-gray-500 text-xs uppercase font-bold pt-1">Processor</span>
                                    <span class="text-white col-span-2">{{ $game->min_cpu ?? '-' }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <span class="text-gray-500 text-xs uppercase font-bold pt-1">Memory</span>
                                    <span class="text-white col-span-2">{{ $game->min_ram ?? '-' }}</span>
                                </div>
                            </div>
                            <div class="space-y-4">
                                <h3 class="font-bold text-brand uppercase tracking-wide border-b border-white/10 pb-1">Recommended</h3>
                                <div class="grid grid-cols-3 gap-2">
                                    <span class="text-gray-500 text-xs uppercase font-bold pt-1">Graphics</span>
                                    <span class="text-white col-span-2">{{ $game->min_gpu ?? '-' }}</span>
                                </div>
                                <div class="grid grid-cols-3 gap-2">
                                    <span class="text-gray-500 text-xs uppercase font-bold pt-1">Storage</span>
                                    <span class="text-white col-span-2">{{ $game->min_storage ?? '-' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif

                </div>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="border-t border-white/5 bg-black py-8 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <p class="text-gray-600 text-sm">© 2025 PlayVision Entertainment. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>