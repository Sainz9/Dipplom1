@props(['game', 'customBorder' => ''])

@php
    // Safety Checks
    if (!$game) return;

    // Badge Logic
    $tagText = $game->tag ?? ''; 
    $isFree = (is_numeric($game->price) && $game->price == 0) || $tagText == 'FreeGame';
    $isOnSale = ($game->sale_price && is_numeric($game->sale_price) && $game->sale_price > 0);
    $badgeHTML = '';

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

    $borderClass = $customBorder ?: 'hover:border-brand/50';
    $imgSrc = $game->img ?? '';
    $route = route('game.show', $game->id);
@endphp

<div class="swiper-slide transition-transform duration-300 hover:z-20 hover:scale-105">
    <a href="{{ $route }}" class="block relative aspect-[3/4] rounded-xl overflow-hidden bg-[#1a1a20] border border-white/5 {{ $borderClass }} hover:shadow-neon group">
        <img src="{{ $imgSrc }}" class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-110" loading="lazy">
        <div class="absolute inset-0 bg-gradient-to-t from-darkBG via-darkBG/20 to-transparent opacity-90"></div>
        {!! $badgeHTML !!}
        
        <div class="absolute bottom-[68px] left-3 z-20 flex flex-wrap gap-1 group-hover:bottom-[82px] transition-all duration-300 pointer-events-none">
            @if($game->categories)
                @foreach($game->categories->unique('id')->take(3) as $c)
                    <span class="text-[8px] font-black uppercase tracking-wider bg-black/70 text-gray-200 border border-white/10 px-1.5 py-0.5 rounded backdrop-blur-md shadow-sm">
                        {{ \Str::before($c->name ?? 'Game', ' (') }}
                    </span>
                @endforeach
            @endif
        </div>

        <div class="absolute bottom-0 p-4 w-full translate-y-2 group-hover:translate-y-0 transition-transform duration-300">
            <h3 class="font-bold text-white truncate text-base mb-1 group-hover:text-brand transition-colors leading-tight">{{ $game->title }}</h3>
            <div class="flex justify-between items-center mt-2">
                @if ($tagText === 'Тун удахгүй')
                    <span class="text-[10px] text-gray-500 font-bold uppercase tracking-widest border border-white/10 px-2 py-1 rounded bg-white/5">Тун удахгүй</span>
                    <div class="bg-white/5 p-1.5 rounded-full text-gray-500"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" /></svg></div>
                @elseif($isFree)
                    <span class="text-brand font-bold text-sm tracking-wider">FREE</span>
                    <div class="bg-white/10 p-1.5 rounded-full group-hover:bg-brand group-hover:text-black transition-colors text-white shadow-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                @else
                    @if($isOnSale)
                        <div class="flex flex-col leading-none"><span class="text-[10px] text-gray-500 line-through">{{ number_format((float)$game->price) }}₮</span><span class="text-green-400 font-bold text-sm">{{ number_format((float)$game->sale_price) }}₮</span></div>
                    @else
                        <span class="text-gray-300 font-bold text-sm">{{ number_format((float)$game->price) }}₮</span>
                    @endif
                    <div class="bg-white/10 p-1.5 rounded-full group-hover:bg-brand group-hover:text-black transition-colors text-white shadow-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg></div>
                @endif
            </div>
        </div>
    </a>
</div>