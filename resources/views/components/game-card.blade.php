<div class="swiper-slide transition-transform duration-300 hover:z-20 hover:scale-105">
    <a href="{{ route('game.show', $game->id) }}" class="block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-white/5 {{ $customBorder ?? 'hover:border-brand/50' }} hover:shadow-neon group">
        
        {{-- 1. Тоглоомын зураг --}}
        <img src="{{ $game->img }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
        
        {{-- 2. Градиент (Доороосоо уусах) --}}
        <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-darkBG/20 to-transparent opacity-90"></div>
        
        {{-- ===================================================================================== --}}
        {{-- ШИНЭ БАЙРШИЛ: КАТЕГОРИУД (Зураг дээр зүүн доод буланд давхарлаж харагдана) --}}
        {{-- ===================================================================================== --}}
        <div class="absolute bottom-[68px] left-3 z-20 flex flex-wrap gap-1 group-hover:bottom-[82px] transition-all duration-300 pointer-events-none">
            {{-- Зөвхөн эхний 3 категорийг харуулна, хэт олон бол муухай харагдана --}}
            @foreach($game->categories->take(3) as $c)
                <span class="text-[8px] font-black uppercase tracking-wider bg-black/70 text-gray-200 border border-white/10 px-1.5 py-0.5 rounded backdrop-blur-md shadow-sm">
                    {{ Str::before($c->name, ' (') }}
                </span>
            @endforeach
        </div>
        {{-- ===================================================================================== --}}

        {{-- 3. BADGE LOGIC (Баруун дээд буланд) --}}
        @if($game->sale_price)
            <div class="absolute top-2 right-2 bg-green-500 text-black text-[10px] font-black px-2 py-1 rounded shadow-lg uppercase z-20">SALE</div>
        @elseif($game->tag)
            @php
                $badge = match($game->tag) {
                    'GOTY' => ['class' => 'bg-yellow-500 text-black border-yellow-400 shadow-[0_0_15px_rgba(234,179,8,0.6)]', 'icon' => '🏆', 'label' => 'Оны шилдэг'],
                    'BestSelling' => ['class' => 'bg-blue-500 text-white border-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.6)]', 'icon' => '💎', 'label' => 'Best Seller'],
                    'EditorsChoice' => ['class' => 'bg-purple-600 text-white border-purple-400', 'icon' => '🎖️', 'label' => 'Онцлох'],
                    'Шинэ' => ['class' => 'bg-green-500 text-white border-green-400', 'icon' => '🔥', 'label' => 'Шинэ'],
                    'Эрэлттэй' => ['class' => 'bg-orange-500 text-white border-orange-400', 'icon' => '⚡', 'label' => 'Эрэлттэй'],
                    'Тун удахгүй' => ['class' => 'bg-gray-700 text-gray-300 border-gray-600', 'icon' => '🚀', 'label' => 'Тун удахгүй'],
                    default => ['class' => 'bg-gray-600 text-white border-gray-500', 'icon' => '', 'label' => $game->tag]
                };
            @endphp
            <div class="absolute top-2 right-2 z-20">
                <span class="{{ $badge['class'] }} text-[10px] font-black uppercase px-2 py-1 rounded-md border flex items-center gap-1 shadow-lg transform group-hover:scale-105 transition-transform">
                    @if($badge['icon'])<span>{{ $badge['icon'] }}</span>@endif
                    <span>{{ $badge['label'] }}</span>
                </span>
            </div>
        @endif

        {{-- 4. ДООД ТАЛЫН МЭДЭЭЛЭЛ (Гарчиг, Үнэ) --}}
        {{-- Эндээс категорийн кодыг УСТГАСАН --}}
        <div class="absolute bottom-0 p-4 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            
            <h3 class="font-bold text-white truncate text-base mb-1 group-hover:text-brand transition-colors leading-tight">{{ $game->title }}</h3>
            
            <div class="flex justify-between items-center mt-2">
                @if(is_numeric($game->price) && $game->price == 0)
                    <span class="text-brand font-bold text-sm tracking-wider">FREE</span>
                @elseif(is_numeric($game->price))
                    <div class="flex flex-col leading-none">
                        @if($game->sale_price)
                            <span class="text-[10px] text-gray-500 line-through">{{ number_format($game->price) }}₮</span>
                            <span class="text-green-400 font-bold text-sm">{{ number_format($game->sale_price) }}₮</span>
                        @else
                            <span class="text-gray-300 font-bold text-sm">{{ number_format($game->price) }}₮</span>
                        @endif
                    </div>
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