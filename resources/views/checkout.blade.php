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
                        brand: '#00D4FF', darkBG: '#050507', darkSurface: '#0f0f13',
                        khan: '#005541', golomt: '#A68846', tdb: '#1F2B5B',
                        state: '#E31E24', xac: '#F0B323', mbank: '#5925DC'
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
        
        /* Bank Logo Filters & Transitions */
        .bank-logo { filter: grayscale(100%) opacity(0.5); transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
        input:checked ~ div .bank-logo { filter: grayscale(0%) opacity(1); transform: scale(1.1); }
        label:hover .bank-logo { filter: grayscale(0%) opacity(0.9); }
        
        .bank-card { transition: all 0.3s ease; border: 1px solid rgba(255,255,255,0.03); position: relative; overflow: hidden; }
        .bank-card::before { content: ''; position: absolute; inset: 0; background: currentColor; opacity: 0; transition: opacity 0.3s; }
        label:hover .bank-card { border-color: rgba(255,255,255,0.1); transform: translateY(-2px); }
    </style>
</head>

<body class="antialiased min-h-screen flex flex-col relative selection:bg-brand selection:text-black">

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px]"></div>
    </div>

    <div class="border-b border-white/5 bg-darkSurface/50 backdrop-blur-md sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center text-white">
            <a href="/" class="text-xl font-black tracking-tighter uppercase italic group">
                Play<span class="text-brand group-hover:text-white transition-colors">Vision</span>
            </a>
            <div class="flex items-center gap-4">
                @auth
                    <div class="hidden md:flex items-center gap-2 text-xs font-medium text-gray-400 border border-white/10 px-3 py-1.5 rounded-full bg-white/5">
                        <span class="w-1.5 h-1.5 rounded-full bg-green-500 animate-pulse"></span>
                        {{ auth()->user()->email }}
                    </div>
                @endauth
                <a href="javascript:history.back()" class="text-xs font-bold uppercase tracking-widest text-gray-400 hover:text-white transition-colors italic">Буцах</a>
            </div>
        </div>
    </div>

    <form action="{{ route('payment.process') }}" method="POST" class="relative z-10 min-h-screen flex flex-col lg:flex-row max-w-7xl mx-auto py-10 px-4 lg:px-8 gap-8 lg:gap-16">
        @csrf
        <input type="hidden" name="game_id" value="{{ $game->id }}">
        <input type="hidden" name="amount" value="{{ $game->sale_price ?? $game->price }}">

        <div class="flex-1 order-2 lg:order-1">
            <h1 class="text-3xl font-black italic uppercase text-white mb-8 flex items-center gap-3">
                <span class="w-1.5 h-8 bg-gradient-to-b from-brand to-purple-600 rounded-full shadow-[0_0_15px_rgba(0,212,255,0.5)]"></span>
                Төлбөрийн мэдээлэл
            </h1>

            @if(session('auth_error'))
                <div class="mb-6 bg-red-500/10 border border-red-500/50 rounded-2xl p-4 flex items-center gap-4 shadow-[0_0_20px_rgba(239,68,68,0.2)] animate-bounce">
                    <div class="bg-red-500 rounded-full p-2 text-white font-black">!</div>
                    <div class="flex-1">
                        <p class="text-white font-bold text-sm italic">{{ session('auth_error') }}</p>
                        @guest <a href="{{ route('login') }}" class="text-brand text-xs font-black uppercase hover:underline mt-1 inline-block">Нэвтрэх →</a> @endguest
                    </div>
                </div>
            @endif

            @if(auth()->check() && isset($existingOrder) && $existingOrder)
                @if($existingOrder->status == 'checking')
                    <div class="mb-8 bg-yellow-500/10 border border-yellow-500/30 rounded-2xl p-5 flex items-start gap-4 animate-pulse shadow-[0_0_30px_rgba(234,179,8,0.1)]">
                        <div class="bg-yellow-500 rounded-full p-2 text-black shrink-0">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                        </div>
                        <div class="flex-1 leading-tight">
                            <p class="text-white font-black text-sm uppercase italic">Захиалга шалгагдаж байна</p>
                            <p class="text-gray-400 text-[10px] mt-2 font-medium uppercase tracking-widest leading-relaxed">Таны төлбөрийг админ баталгаажуулж байна. Түр хүлээнэ үү.</p>
                        </div>
                    </div>
                @elseif($existingOrder->status == 'paid')
                    <div class="mb-8 bg-green-500/10 border border-green-500/30 rounded-2xl p-5 flex items-start gap-4 shadow-[0_0_30px_rgba(34,197,94,0.1)]">
                        <div class="bg-green-500 rounded-full p-2 text-white shrink-0">✓</div>
                        <div class="flex-1 leading-tight text-left">
                            <p class="text-white font-black text-sm uppercase italic">Энэ тоглоом чамд байна</p>
                            <p class="text-green-400 text-[10px] mt-2 font-black italic uppercase tracking-widest">Таны худалдан авалт амжилттай баталгаажсан байна.</p>
                        </div>
                    </div>
                    <a href="/dashboard" class="mb-8 block text-center py-4 bg-white/5 border border-white/10 rounded-xl text-[10px] font-black text-brand uppercase tracking-[0.2em] hover:bg-brand hover:text-black transition-all italic">Миний тоглоомууд руу очих →</a>
                @endif
            @endif

            <div class="bg-darkSurface/50 border border-white/10 rounded-3xl p-8 hover:border-brand/20 transition-all shadow-2xl">
                <h3 class="text-lg font-black text-white mb-8 flex items-center gap-3 uppercase italic">
                    <span class="bg-white/10 text-[10px] w-7 h-7 rounded-lg flex items-center justify-center text-gray-400 not-italic border border-white/5">01</span>
                    Төлбөрийн хэрэгсэл сонгох
                </h3>
                
                <div class="space-y-10">
                    <div>
                        <p class="text-[10px] uppercase text-gray-500 font-black mb-4 tracking-[0.2em] pl-1 flex items-center gap-2">
                            <span class="w-1 h-1 bg-brand rounded-full"></span> Дижитал түрийвч
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method_ui" value="qpay" class="peer hidden" checked>
                                <div class="bank-card h-28 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-3 peer-checked:border-brand peer-checked:bg-brand/5 peer-checked:shadow-[0_0_25px_rgba(0,212,255,0.2)]">
                                    <img src="https://solongo.medsoft.care:3001/static/media/qpay-logo.96d8ccc6ff2a2c3a0010.png" class="h-9 object-contain invert brightness-0 filter contrast-200 bank-logo">
                                    <span class="font-black text-[10px] text-gray-500 peer-checked:text-white uppercase italic tracking-tighter transition-colors">QPay QR</span>
                                </div>
                            </label>
                        </div>
                    </div>

                    <div>
                        <p class="text-[10px] uppercase text-gray-500 font-black mb-4 tracking-[0.2em] pl-1 flex items-center gap-2">
                            <span class="w-1 h-1 bg-brand rounded-full"></span> Банкаар шилжүүлэх
                        </p>
                        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method_ui" value="khanbank" class="peer hidden">
                                <div class="bank-card h-24 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-2 peer-checked:border-khan peer-checked:bg-khan/10 peer-checked:shadow-[0_0_20px_rgba(0,85,65,0.4)]">
                                    <img src="https://www.servicenow.com/content/dam/servicenow-assets/public/en-us/digital-graphics/ds-logos/logo-khan-bank-2.png" class="h-6 object-contain bank-logo">
                                    <span class="font-black text-[9px] text-gray-500 peer-checked:text-white uppercase italic tracking-tighter transition-colors">Хаан Банк</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group text-center">
                                <input type="radio" name="payment_method_ui" value="golomt" class="peer hidden">
                                <div class="bank-card h-24 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-2 peer-checked:border-golomt peer-checked:bg-golomt/10 peer-checked:shadow-[0_0_20px_rgba(166,136,70,0.4)]">
                                    <img src="https://anket.golomtbank.com/assets/Golomt%20logo.3af46c9d23bd6aae231d12227071b38c.png" class="h-6 object-contain bank-logo">
                                    <span class="font-black text-[9px] text-gray-500 peer-checked:text-white uppercase italic tracking-tighter">Голомт</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group text-center">
                                <input type="radio" name="payment_method_ui" value="statebank" class="peer hidden">
                                <div class="bank-card h-24 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-2 peer-checked:border-state peer-checked:bg-state/10 peer-checked:shadow-[0_0_20px_rgba(227,30,36,0.4)]">
                                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQBCEuWnamoyP22Qe1Snf9LKEEwVjuK93k_KA&s" class="h-6 object-contain bank-logo">
                                    <span class="font-black text-[9px] text-gray-500 peer-checked:text-white uppercase italic tracking-tighter leading-none">Төрийн Банк</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group text-center">
                                <input type="radio" name="payment_method_ui" value="xacbank" class="peer hidden">
                                <div class="bank-card h-24 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-2 peer-checked:border-xac peer-checked:bg-xac/10 peer-checked:shadow-[0_0_20px_rgba(240,179,35,0.4)]">
                                    <img src="https://upload.wikimedia.org/wikipedia/commons/7/7e/XacBank_logo.png" class="h-6 object-contain bank-logo brightness-200">
                                    <span class="font-black text-[9px] text-gray-500 peer-checked:text-white uppercase italic tracking-tighter">Хас Банк</span>
                                </div>
                            </label>
                            <label class="cursor-pointer group">
                                <input type="radio" name="payment_method_ui" value="tdb" class="peer hidden">
                                <div class="bank-card h-24 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-2 peer-checked:border-tdb peer-checked:bg-tdb/10 peer-checked:shadow-[0_0_20px_rgba(31,43,91,0.4)]">
                                    <img src="https://www.tdbm.mn/sites/default/files/2024-11/logo_eng%20%282%29.png" class="h-8 w-full object-contain bank-logo">
                                </div>
                            </label>
                            <label class="cursor-pointer group text-center">
                                <input type="radio" name="payment_method_ui" value="mbank" class="peer hidden">
                                <div class="bank-card h-24 rounded-2xl bg-darkBG flex flex-col items-center justify-center gap-2 peer-checked:border-mbank peer-checked:bg-mbank/10 peer-checked:shadow-[0_0_20px_rgba(89,37,220,0.4)]">
                                    <img src="https://www.mongolchamber.mn/resource/mongolchamber/image/2024/03/18/rairwvn30xn9ch9r/M%20%D0%91%D0%90%D0%9D%D0%9A.png" class="h-6 object-contain bank-logo">
                                    <span class="font-black text-[9px] text-gray-500 peer-checked:text-white uppercase italic tracking-tighter">М Банк</span>
                                </div>
                            </label>
                        </div>
                    </div>
                </div>

                <div class="mt-12 p-5 bg-brand/5 rounded-2xl border border-brand/10 flex gap-4 items-start">
                    <svg class="w-6 h-6 text-brand shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    <p class="text-[11px] text-gray-400 leading-relaxed uppercase italic tracking-widest font-medium">
                        Сонгосон банкны апп руу автоматаар үсрэх эсвэл QR код харагдана. Гүйлгээний утга дээр захиалгын дугаарыг ЗААВАЛ бичээрэй.
                    </p>
                </div>
            </div>
        </div>

        <div class="w-full lg:w-96 order-1 lg:order-2">
            <div class="sticky top-24 relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-brand/20 to-purple-600/20 rounded-3xl blur-xl opacity-50 group-hover:opacity-100 transition duration-1000"></div>
                <div class="relative bg-darkSurface/90 backdrop-blur-2xl border border-white/10 rounded-3xl p-8 shadow-2xl overflow-hidden">
                    
                    <h3 class="text-xl font-black uppercase italic text-white mb-8 flex items-center gap-3">
                        <span class="w-1.5 h-6 bg-brand rounded-full"></span> Захиалга
                    </h3>

                    <div class="flex gap-5 mb-8">
                        <div class="w-24 h-32 bg-gray-800 rounded-xl overflow-hidden border border-white/10 shrink-0 shadow-xl">
                            <img src="{{ $game->img ?? $game->banner }}" class="w-full h-full object-cover transform hover:scale-110 transition-transform duration-700">
                        </div>
                        <div class="flex flex-col justify-center">
                            <h4 class="font-black text-white text-base line-clamp-2 uppercase italic leading-tight mb-3">{{ $game->title }}</h4>
                            <span class="text-[10px] font-black bg-brand/10 text-brand px-3 py-1 rounded-lg border border-brand/20 uppercase tracking-[0.2em] italic w-fit">Digital Key</span>
                        </div>
                    </div>

                    <div class="space-y-4 pt-6 border-t border-dashed border-white/10">
                        <div class="flex justify-between items-end italic">
                            <span class="text-gray-400 font-bold uppercase text-[10px] tracking-widest pb-1">Нийт төлөх дүн</span>
                            <span class="text-4xl font-black text-brand drop-shadow-[0_0_20px_rgba(0,212,255,0.4)]">
                                {{ number_format($game->sale_price ?? $game->price) }} <span class="text-sm font-medium text-gray-500 not-italic uppercase">₮</span>
                            </span>
                        </div>
                    </div>

                    @php
                        $isDisabled = false;
                        if(auth()->check() && isset($existingOrder) && $existingOrder) {
                            if (in_array($existingOrder->status, ['checking', 'paid'])) {
                                $isDisabled = true;
                            }
                        }
                    @endphp

                    <button type="submit" 
                        @if($isDisabled) disabled @endif
                        class="w-full rounded-2xl p-5 font-black uppercase italic tracking-[0.2em] transition-all mt-10 shadow-2xl text-sm
                        {{ $isDisabled 
                            ? 'bg-gray-800 text-gray-500 cursor-not-allowed border border-white/5 opacity-80' 
                            : 'bg-brand text-black hover:scale-[1.02] hover:shadow-[0_0_40px_rgba(0,212,255,0.5)] active:scale-95' }}">
                        
                        <span class="flex items-center justify-center gap-3">
                            @if(!auth()->check())
                                Нэвтэрч үргэлжлүүлэх
                            @elseif(isset($existingOrder) && $existingOrder->status == 'checking')
                                Шалгагдаж байна...
                            @elseif(isset($existingOrder) && $existingOrder->status == 'paid')
                                Эзэмшсэн байна
                            @else
                                Төлбөр төлөх
                            @endif
                        </span>
                    </button>
                    
                    <div class="mt-6 text-center">
                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-[0.3em] italic opacity-50">PlayVision Secure Checkout</p>
                    </div>
                </div>
            </div>
        </div>
    </form>
</body>
</html>