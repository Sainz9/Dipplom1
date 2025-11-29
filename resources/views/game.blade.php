<!DOCTYPE html>
<html lang="mn" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $game['title'] }} - PlayVision</title>
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
                        surface: '#18181b'
                    }
                }
            }
        }
    </script>
</head>
<body class="bg-dark text-white antialiased">

    <nav class="fixed w-full z-50 top-0 bg-dark/95 backdrop-blur-xl border-b border-white/5">
        <div class="max-w-[1920px] mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16 md:h-20">
                <a href="/" class="text-2xl font-black tracking-tighter uppercase italic">
                    Play<span class="text-brand">Vision</span>
                </a>
                <div class="hidden md:flex items-center space-x-8">
                    <a href="/" class="text-sm font-bold hover:text-brand transition-colors flex items-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
                        BACK TO STORE
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <main class="pt-20">
        
        <div class="relative h-[60vh] w-full">
            <img src="{{ $game['banner'] }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-gradient-to-t from-dark via-dark/50 to-transparent"></div>
            
            <div class="absolute bottom-0 left-0 w-full">
                <div class="max-w-7xl mx-auto px-4 pb-10 flex items-end">
                    <div>
                        <span class="bg-brand text-black text-xs font-bold px-2 py-1 rounded uppercase mb-4 inline-block">{{ $game['genre'] }}</span>
                        <h1 class="text-5xl md:text-7xl font-black uppercase mb-4 drop-shadow-2xl">{{ $game['title'] }}</h1>
                    </div>
                </div>
            </div>
        </div>

        <div class="max-w-7xl mx-auto px-4 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
            
            <div class="lg:col-span-2 space-y-12">

           <section>
    <h2 class="text-2xl font-bold mb-6 border-l-4 border-brand pl-4">JAWHLAN BAYAR</h2>
    <div class="aspect-video rounded-xl overflow-hidden shadow-2xl border border-white/10">
        <iframe 
            src="{{ $game['trailer'] }}?rel=0" 
            class="w-full h-full" 
            title="YouTube video player" 
            frameborder="0" 
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" 
            allowfullscreen>
        </iframe>
    </div>
</section>
                <section>
                    <h2 class="text-2xl font-bold mb-4 border-l-4 border-brand pl-4">Тоглоом тайлбар</h2>
                    <div class="text-gray-300 leading-relaxed space-y-4 text-lg">
                        <p class="font-medium text-white">{{ $game['description'] }}</p>
                        <p class="text-gray-400 text-sm leading-7">{{ $game['long_description'] }}</p>
                    </div>
                </section>

                <section class="bg-surface p-6 rounded-xl border border-white/5">
                    <h2 class="text-xl font-bold mb-6 text-brand">System Requirements</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                        <div>
                            <h3 class="text-white font-bold mb-3 uppercase tracking-wider border-b border-white/10 pb-2">Minimum</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li class="flex justify-between"><span class="text-gray-500">OS:</span> <span>Windows 10 (64-bit)</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Processor:</span> <span>Intel Core i5 / AMD Ryzen 3</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Memory:</span> <span>8 GB RAM</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Graphics:</span> <span>NVIDIA GTX 1060</span></li>
                            </ul>
                        </div>
                        <div>
                            <h3 class="text-white font-bold mb-3 uppercase tracking-wider border-b border-white/10 pb-2">Recommended</h3>
                            <ul class="space-y-2 text-gray-400">
                                <li class="flex justify-between"><span class="text-gray-500">OS:</span> <span>Windows 11 (64-bit)</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Processor:</span> <span>Intel Core i7 / AMD Ryzen 7</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Memory:</span> <span>16 GB RAM</span></li>
                                <li class="flex justify-between"><span class="text-gray-500">Graphics:</span> <span>NVIDIA RTX 3060</span></li>
                            </ul>
                        </div>
                    </div>
                </section>
            </div>

            <div class="relative">
                <div class="sticky top-24 bg-surface p-6 rounded-xl border border-white/10 shadow-2xl">
                    <div class="flex justify-center mb-6 relative group">
                        <img src="{{ $game['img'] }}" class="rounded-lg shadow-lg w-full max-w-[240px] transition-transform transform group-hover:scale-105">
                        
                        @if($game['discount'])
                            <div class="absolute top-2 right-10 bg-brand text-black font-bold px-2 py-1 rounded shadow-md animate-pulse">
                                {{ $game['discount'] }}
                            </div>
                        @endif
                    </div>
                    
                    <div class="flex items-center justify-between mb-2">
                        <span class="bg-gray-700 text-xs px-2 py-1 rounded text-gray-300">Base Game</span>
                    </div>

                    <h2 class="text-2xl font-bold mb-4 text-center">{{ $game['title'] }}</h2>
                    
                    <div class="border-t border-b border-white/10 py-6 mb-6">
                        <div class="flex items-center justify-between mb-1">
                            <span class="text-gray-400 text-sm">Үнэ</span>
                            <div class="flex flex-col items-end">
                                @if($game['sale_price'])
                                    <span class="text-sm text-gray-500 line-through">${{ $game['price'] }}</span>
                                    <span class="text-3xl font-black text-brand">${{ $game['sale_price'] }}</span>
                                @elseif($game['price'] == 0)
                                    <span class="text-2xl font-black text-brand">₮213353.7</span>
                                @else
                                    <span class="text-3xl font-black text-white">${{ $game['price'] }}</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    @if($game['price'] > 0)
                        <button class="w-full bg-brand text-black font-black py-4 rounded hover:bg-white transition-colors uppercase tracking-wider mb-3 shadow-[0_0_15px_rgba(0,212,255,0.3)]">
                            Buy Now
                        </button>
                    @else
                        <button class="w-full bg-white text-black font-black py-4 rounded hover:bg-gray-200 transition-colors uppercase tracking-wider mb-3">
                            Урьдчилж захиалах
                        </button>
                    @endif
                    
                    <button class="w-full border border-white/20 text-white font-bold py-3 rounded hover:bg-white/5 transition-colors text-sm flex items-center justify-center gap-2">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" /></svg>
                        Банк холбох
                    </button>

                    <div class="mt-6 text-xs text-gray-500 space-y-2 pt-4 border-t border-white/10">
                        <div class="flex justify-between"><span>Developer</span> <span class="text-white">{{ $game['developer'] ?? 'Unknown' }}</span></div>
                        <div class="flex justify-between"><span>Publisher</span> <span class="text-white">{{ $game['publisher'] ?? 'Unknown' }}</span></div>
                        <div class="flex justify-between"><span>Platform</span> <span class="text-white">Windows</span></div>
                    </div>
                </div>
            </div>

        </div>
    </main>

    <footer class="bg-surface border-t border-white/5 py-12 mt-12">
        <div class="max-w-7xl mx-auto px-4 text-center text-gray-500">
            &copy; 2025 PlayVision. All rights reserved.
        </div>
    </footer>

</body>
</body>
</html>
</html>