<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PlayVision - Төлбөр төлөх</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: { 
                        brand: '#00D4FF', 
                        darkBG: '#050507', 
                        darkSurface: '#0f0f13',
                        // Bank Colors
                        khan: '#005541',
                        golomt: '#A68846',
                        tdb: '#1F2B5B',
                        state: '#E31E24',
                        xac: '#F0B323',
                        mbank: '#5925DC',
                        bogd: '#8B2332',
                        capitron: '#0047BB'
                    }
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
        
        /* Bank Logo Filters */
        .bank-logo { filter: grayscale(100%) opacity(0.7); transition: all 0.3s; }
        input:checked ~ div .bank-logo, 
        label:hover .bank-logo { filter: grayscale(0%) opacity(1); }
    </style>
</head>
<body class="antialiased min-h-screen flex flex-col relative selection:bg-brand selection:text-black">

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="border-b border-white/5 bg-darkSurface/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <a href="/" class="text-xl font-black tracking-tighter uppercase italic text-white group">
                Play<span class="text-brand group-hover:text-white transition-colors">Vision</span>
            </a>
            
            <div class="flex items-center gap-4">
                @auth
                    <div class="hidden md:flex items-center gap-2 text-xs font-medium text-gray-400 border border-white/10 px-3 py-1.5 rounded-full bg-white/5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        {{ auth()->user()->email }}
                    </div>
                @endauth

                <a href="javascript:history.back()" class="flex items-center gap-2 text-xs font-bold text-gray-400 hover:text-white transition-colors uppercase tracking-widest">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Буцах
                </a>
            </div>
        </div>
    </div>

    <form action="{{ route('payment.process') }}" method="POST" class="relative z-10 min-h-screen flex flex-col lg:flex-row max-w-7xl mx-auto py-10 px-4 lg:px-8 gap-8 lg:gap-16">
        @csrf
        <input type="hidden" name="game_id" value="{{ $game->id }}">
        <input type="hidden" name="amount" value="{{ $game->sale_price ?? $game->price }}">

        <div class="flex-1 order-2 lg:order-1 animate-fade-in-up">
            <h1 class="text-3xl font-black italic uppercase text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-gradient-to-b from-brand to-purple-600 rounded-full"></span>
                Төлбөрийн мэдээлэл
            </h1>

            @guest
            <div class="bg-darkSurface/50 border border-white/10 rounded-2xl p-6 mb-8 hover:border-brand/30 transition-colors">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                    <span class="bg-white/10 text-xs w-6 h-6 rounded flex items-center justify-center text-gray-400">1</span>
                    Хувийн мэдээлэл
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-2">
                        <label class="block text-[10px] text-gray-500 uppercase font-bold tracking-wider">Имэйл хаяг</label>
                        <input type="email" class="w-full bg-[#050507] border border-white/10 rounded-xl p-3 text-white focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none placeholder-gray-700 transition-all text-sm" placeholder="name@example.com">
                    </div>
                    <div class="space-y-2">
                        <label class="block text-[10px] text-gray-500 uppercase font-bold tracking-wider">Утасны дугаар</label>
                        <input type="text" class="w-full bg-[#050507] border border-white/10 rounded-xl p-3 text-white focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none placeholder-gray-700 transition-all text-sm" placeholder="9911....">
                    </div>
                </div>
            </div>
            @endguest

            <div class="bg-darkSurface/50 border border-white/10 rounded-2xl p-6 hover:border-brand/30 transition-colors">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                    <span class="bg-white/10 text-xs w-6 h-6 rounded flex items-center justify-center text-gray-400">
                        @auth 1 @else 2 @endauth
                    </span>
                    Төлбөрийн хэрэгсэл
                </h3>
                
                <div class="space-y-8">
                    
                    <div>
                        <p class="text-[10px] uppercase text-gray-500 font-bold mb-3 tracking-widest pl-1">Түрийвч & Апп</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="qpay" class="peer hidden" checked>
                                <div class="h-24 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-2 peer-checked:border-brand peer-checked:bg-brand/5 peer-checked:shadow-[0_0_15px_rgba(0,212,255,0.1)] transition-all group-hover:border-white/30 p-2 overflow-hidden">
                                    <img src="https://solongo.medsoft.care:3001/static/media/qpay-logo.96d8ccc6ff2a2c3a0010.png" class="h-8 object-contain invert brightness-0 filter contrast-200" alt="QPay">
                                    <span class="font-bold text-[10px] text-gray-400 peer-checked:text-white">QPay</span>
                                </div>
                                <div class="absolute top-2 right-2 w-2 h-2 bg-brand rounded-full opacity-0 peer-checked:opacity-100 transition-opacity"></div>
                            </label>
                            
                       
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase text-gray-500 font-bold mb-3 tracking-widest pl-1">Банкууд</p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                            
                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="khanbank" class="peer hidden">
                                <div class="h-20 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-1 peer-checked:border-khan peer-checked:bg-khan/10 transition-all group-hover:border-white/30 overflow-hidden relative">
                                    <img src="https://www.servicenow.com/content/dam/servicenow-assets/public/en-us/digital-graphics/ds-logos/logo-khan-bank-2.png" class="h-6 object-contain bank-logo" alt="Khan Bank">
                                    <span class="font-bold text-[10px] text-gray-400 peer-checked:text-white mt-1">Хаан Банк</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-khan rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="golomt" class="peer hidden">
                                <div class="h-20 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-1 peer-checked:border-golomt peer-checked:bg-golomt/10 transition-all group-hover:border-white/30 overflow-hidden">
                                    <img src="https://anket.golomtbank.com/assets/Golomt%20logo.3af46c9d23bd6aae231d12227071b38c.png" class="h-6 object-contain bank-logo" alt="Golomt">
                                    <span class="font-bold text-[10px] text-gray-400 peer-checked:text-white mt-1">Голомт</span>
                                </div>
                                 <div class="absolute inset-0 border-2 border-golomt rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="tdb" class="peer hidden">
                                <div class="h-20 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-1 peer-checked:border-tdb peer-checked:bg-tdb/10 transition-all group-hover:border-white/30 overflow-hidden">
                                    <img src="https://www.tdbm.mn/sites/default/files/2024-11/logo_eng%20%282%29.png" class="h-8 w-full object-contain bank-logo" alt="TDB">
                                </div>
                                <div class="absolute inset-0 border-2 border-tdb rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="statebank" class="peer hidden">
                                <div class="h-20 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-1 peer-checked:border-state peer-checked:bg-state/10 transition-all group-hover:border-white/30 overflow-hidden">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBCEuWnamoyP22Qe1Snf9LKEEwVjuK93k_KA&s" class="h-6 object-contain bank-logo" alt="State Bank">
                                    <span class="font-bold text-[10px] text-gray-400 peer-checked:text-white mt-1">Төрийн Банк</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-state rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="xacbank" class="peer hidden">
                                <div class="h-20 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-1 peer-checked:border-xac peer-checked:bg-xac/10 transition-all group-hover:border-white/30 overflow-hidden">
                                    <img src="https://cdn.aptoide.com/imgs/e/a/6/ea6a9f10683d3c5f0c4469911dfa987a_fgraphic.png" class="h-6 object-contain bank-logo" alt="XacBank">
                                    <span class="font-bold text-[10px] text-gray-400 peer-checked:text-white mt-1">Хас Банк</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-xac rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                            </label>

                            <label class="cursor-pointer group relative">
                                <input type="radio" name="payment_method_ui" value="m_bank" class="peer hidden">
                                <div class="h-20 border border-white/10 rounded-xl bg-[#050507] flex flex-col items-center justify-center gap-1 peer-checked:border-mbank peer-checked:bg-mbank/10 transition-all group-hover:border-white/30 overflow-hidden">
                                    <img src="https://www.mongolchamber.mn/resource/mongolchamber/image/2024/03/18/rairwvn30xn9ch9r/M%20%D0%91%D0%90%D0%9D%D0%9A_l.png" class="h-6 object-contain bank-logo" alt="M Bank">
                                    <span class="font-bold text-[10px] text-gray-400 peer-checked:text-white mt-1">M Банк</span>
                                </div>
                                <div class="absolute inset-0 border-2 border-mbank rounded-xl opacity-0 peer-checked:opacity-100 transition-opacity pointer-events-none"></div>
                            </label>

                        </div>
                    </div>
                </div>

                <div class="mt-8 p-4 bg-blue-500/5 rounded-xl border border-blue-500/10 flex gap-3 items-start">
                    <svg class="w-5 h-5 text-brand shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-xs text-gray-400 leading-relaxed">
                        Сонгосон банкны аппликейшн рүү шилжих эсвэл QR код үүснэ. Гүйлгээний утга дээр захиалгын дугаарыг бичнэ үү.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-96 order-1 lg:order-2">
            <div class="sticky top-24 relative group">
                
                <div class="absolute -inset-0.5 bg-gradient-to-r from-brand to-purple-600 rounded-2xl blur opacity-30 group-hover:opacity-60 transition duration-1000 group-hover:duration-200"></div>

                <div class="relative bg-[#0a0a0f]/90 backdrop-blur-xl border border-white/10 rounded-2xl p-6 shadow-2xl overflow-hidden">
                    
                    <div class="absolute top-0 right-0 p-4 opacity-10 pointer-events-none">
                        <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"></path></svg>
                    </div>

                    <h3 class="text-xl font-black uppercase italic text-white mb-6 flex items-center gap-2">
                        <span class="w-1 h-6 bg-brand rounded-full shadow-[0_0_10px_rgba(0,212,255,0.8)]"></span>
                        Захиалга
                    </h3>

                    <div class="flex gap-4 mb-6 relative z-10">
                        <div class="w-20 h-28 bg-gray-800 rounded-lg overflow-hidden border border-white/10 shrink-0 shadow-lg group-hover:border-brand/50 transition-colors">
                            <img src="{{ $game->img ?? $game->banner }}" class="w-full h-full object-cover transform group-hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="flex flex-col justify-center">
                            <h4 class="font-bold text-white leading-tight mb-2 text-sm line-clamp-2">{{ $game->title }}</h4>
                            <div class="flex items-center gap-2">
                                <span class="text-[10px] font-bold bg-white/10 text-gray-300 px-2 py-0.5 rounded border border-white/5 uppercase tracking-wide">Key</span>
                                @if($game->sale_price)
                                    <span class="text-[10px] font-bold bg-green-500/20 text-green-400 px-2 py-0.5 rounded border border-green-500/20">SALE</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3 pt-4 border-t border-dashed border-white/10 text-sm relative z-10">
                        <div class="flex justify-between text-gray-400">
                            <span>Үндсэн үнэ</span>
                            <span class="font-medium">{{ number_format($game->price) }}₮</span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Хөнгөлөлт</span>
                            <span class="{{ $game->sale_price ? 'text-green-400' : '' }}">
                                @if($game->sale_price)
                                    -{{ number_format($game->price - $game->sale_price) }}₮
                                @else
                                    0₮
                                @endif
                            </span>
                        </div>

                        <div class="flex justify-between items-end pt-4 mt-2 border-t border-white/10">
                            <span class="text-gray-300 font-bold uppercase text-xs tracking-widest pb-1">Нийт дүн</span>
                            <span class="text-3xl font-black text-brand drop-shadow-[0_0_10px_rgba(0,212,255,0.4)]">
                                @if($game->sale_price)
                                    {{ number_format($game->sale_price) }}
                                @else
                                    {{ number_format($game->price) }}
                                @endif
                                <span class="text-sm font-medium text-gray-400">₮</span>
                            </span>
                        </div>
                    </div>

                    <button type="submit" class="mt-6 group relative w-full overflow-hidden rounded-xl bg-brand p-4 text-center font-black uppercase italic tracking-widest text-black shadow-[0_0_20px_rgba(0,212,255,0.4)] transition-all hover:scale-[1.02] hover:shadow-[0_0_30px_rgba(0,212,255,0.6)]">
                        <span class="absolute inset-0 h-full w-full bg-gradient-to-r from-transparent via-white/40 to-transparent -translate-x-full transition-transform duration-700 group-hover:translate-x-full"></span>
                        <span class="relative flex items-center justify-center gap-2">
                            Төлбөр төлөх
                            <svg class="w-4 h-4 transition-transform group-hover:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </span>
                    </button>
                    
                    <div class="mt-4 text-center">
                        <p class="text-[10px] text-gray-500 leading-tight">Сонгосон банкны сайт руу шилжиж гүйлгээ хийгдэнэ.</p>
                    </div>
                </div>
            </div>
        </div>
    </form> </body>
</html>