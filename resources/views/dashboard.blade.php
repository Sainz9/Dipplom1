<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Library - PlayVision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: { 
                    colors: { brand: '#00D4FF', dark: '#050507', surface: '#0f0f13', card: '#18181b' },
                    fontFamily: { sans: ['Inter', 'sans-serif'] }
                }
            }
        }
    </script>
    <style>
        body { background-color: #050507; color: #e5e7eb; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #0f0f13; }
        ::-webkit-scrollbar-thumb { background: #333; border-radius: 3px; }
        ::-webkit-scrollbar-thumb:hover { background: #00D4FF; }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative selection:bg-brand selection:text-black">

    {{-- Background Effects --}}
    <div class="fixed inset-0 pointer-events-none z-0 overflow-hidden">
        <div class="absolute top-0 left-1/4 w-[500px] h-[500px] bg-brand/5 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-purple-600/5 rounded-full blur-[120px]"></div>
    </div>

    {{-- Navbar --}}
    <nav class="border-b border-white/5 bg-surface/80 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 h-20 flex items-center justify-between">
            <a href="/" class="flex items-center gap-2 group">
                <div class="text-2xl font-black italic text-white tracking-tighter group-hover:scale-105 transition-transform">
                    Play<span class="text-brand">Vision</span>
                </div>
            </a>

            <div class="flex items-center gap-6">
                <div class="flex items-center gap-3 bg-white/5 border border-white/5 px-4 py-2 rounded-full">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-tr from-brand to-purple-500 flex items-center justify-center text-black font-bold text-xs">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xs font-bold text-white leading-none">{{ Auth::user()->name }}</span>
                        <span class="text-[10px] text-gray-400 leading-none mt-1">{{ Auth::user()->email }}</span>
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-gray-400 hover:text-red-500 transition-colors p-2 rounded-lg hover:bg-white/5" title="Гарах">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    </button>
                </form>
            </div>
        </div>
    </nav>

    {{-- Main Content --}}
    <main class="relative z-10 flex-1 py-12">
        <div class="max-w-7xl mx-auto px-6">
            
            {{-- Header --}}
            <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-12">
                <div>
                    <h1 class="text-4xl font-black text-white uppercase tracking-tight italic flex items-center gap-3">
                        <span class="w-2 h-10 bg-gradient-to-b from-brand to-purple-600 rounded-full"></span>
                        My Library
                    </h1>
                    <p class="text-gray-400 mt-2 ml-5">Таны худалдаж авсан бүх тоглоом энд хадгалагдана.</p>
                </div>
                
                <div class="flex gap-4">
                    <div class="bg-card border border-white/5 px-6 py-3 rounded-xl">
                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Нийт тоглоом</div>
                        {{-- Энд isset ашиглаж алдаанаас сэргийлнэ --}}
                        <div class="text-2xl font-black text-white">{{ isset($orders) ? $orders->count() : 0 }}</div>
                    </div>
                    <div class="bg-card border border-white/5 px-6 py-3 rounded-xl">
                        <div class="text-[10px] text-gray-500 uppercase font-bold tracking-widest">Статус</div>
                        <div class="text-2xl font-black text-brand">Gamer</div>
                    </div>
                </div>
            </div>

            {{-- Flash Messages --}}
            @if(session('error'))
                <div class="mb-8 p-4 bg-red-500/10 border border-red-500/50 rounded-xl flex items-center gap-3 animate-pulse">
                    <svg class="w-6 h-6 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <span class="text-red-400 font-bold text-sm">{{ session('error') }}</span>
                </div>
            @endif

            {{-- Games Grid --}}
            @if(isset($orders) && $orders->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($orders as $order)
                        {{-- Game Card --}}
                        <div class="group relative bg-card rounded-2xl overflow-hidden border border-white/5 hover:border-brand/50 transition-all duration-500 hover:shadow-[0_0_40px_rgba(0,212,255,0.15)] hover:-translate-y-2">
                            
                            {{-- Image --}}
                            <div class="relative h-64 overflow-hidden">
                                <img src="{{ $order->game->img }}" alt="{{ $order->game->title }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                                <div class="absolute inset-0 bg-gradient-to-t from-card via-card/50 to-transparent opacity-90"></div>
                                
                                <div class="absolute top-4 right-4 bg-black/60 backdrop-blur-md border border-brand/30 text-brand text-[10px] font-bold px-3 py-1 rounded-full uppercase tracking-widest flex items-center gap-1 shadow-lg">
                                    <span class="w-1.5 h-1.5 rounded-full bg-brand animate-pulse"></span>
                                    Owned
                                </div>
                            </div>

                            {{-- Content --}}
                            <div class="absolute bottom-0 left-0 w-full p-6">
                                <h3 class="text-xl font-black text-white mb-1 line-clamp-1 group-hover:text-brand transition-colors">
                                    {{ $order->game->title }}
                                </h3>
                                <p class="text-xs text-gray-500 mb-6 font-mono flex items-center gap-2">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                                    {{ $order->created_at->format('Y.m.d') }}
                                </p>

                                {{-- Download Button --}}
                                <a href="{{ route('game.download', $order->game->id) }}" target="_blank" class="relative block w-full bg-white/5 hover:bg-brand border border-white/10 hover:border-brand text-white hover:text-black font-bold py-3.5 rounded-xl uppercase tracking-widest text-xs transition-all duration-300 text-center group/btn overflow-hidden cursor-pointer">
                                    <span class="relative z-10 flex items-center justify-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                        Татах (Download)
                                    </span>
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            
            @else
                {{-- Empty State --}}
                <div class="w-full bg-card/50 border border-dashed border-white/10 rounded-3xl p-20 text-center relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-r from-brand/5 to-purple-600/5 opacity-0 group-hover:opacity-100 transition-opacity duration-700"></div>
                    
                    <div class="relative z-10">
                        <div class="w-24 h-24 bg-white/5 rounded-full flex items-center justify-center mx-auto mb-6 text-gray-600 group-hover:text-brand group-hover:scale-110 transition-all duration-500 border border-white/5 group-hover:border-brand/30">
                            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                        </div>
                        
                        <h3 class="text-2xl font-black text-white mb-3">Одоогоор хоосон байна</h3>
                        <p class="text-gray-400 mb-8 max-w-md mx-auto text-sm leading-relaxed">
                            Танд одоогоор худалдаж авсан тоглоом алга байна. <br>Манай дэлгүүрээс хүссэн тоглоомоо сонгоорой.
                        </p>
                        
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 px-8 py-4 bg-brand text-black font-black uppercase tracking-widest text-xs rounded-xl hover:bg-white hover:shadow-[0_0_30px_rgba(0,212,255,0.4)] transition-all transform hover:-translate-y-1 shadow-lg shadow-brand/20">
                            Дэлгүүр хэсэх
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                </div>
            @endif

        </div>
    </main>

</body>
</html>