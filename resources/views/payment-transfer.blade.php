<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Төлбөр шилжүүлэх</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #050507; color: white; }
        @keyframes scan-line {
            0% { top: 0%; opacity: 0; }
            10% { opacity: 1; }
            90% { opacity: 1; }
            100% { top: 100%; opacity: 0; }
        }
        .animate-scan { animation: scan-line 2.5s linear infinite; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4">

    <div class="max-w-md w-full bg-[#0f0f13] border border-white/10 rounded-3xl p-8 relative overflow-hidden shadow-2xl">
        
        <div class="absolute top-0 right-0 w-64 h-64 bg-brand/5 rounded-full blur-[80px] -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>

        <div class="text-center mb-6">
            <div class="w-16 h-16 mx-auto bg-white rounded-2xl flex items-center justify-center mb-4 shadow-lg p-2">
                <img src="{{ $bankInfo['logo'] }}" class="w-full h-full object-contain">
            </div>
            <h1 class="text-xl font-bold text-gray-200">
                @if(!empty($bankInfo['qr_image']))
                    QR уншуулах
                @else
                    Төлбөр шилжүүлэх
                @endif
            </h1>
        </div>

        @if(!empty($bankInfo['qr_image']))
            <div class="bg-white p-6 rounded-2xl mb-8 flex flex-col items-center shadow-lg relative overflow-hidden group">
                
                <p class="text-black text-xs font-bold uppercase mb-4 tracking-widest">QPay-ээр уншуулах</p>
                
                <div class="relative w-64 h-64 mb-4">
                    <img src="{{ $bankInfo['qr_image'] }}" class="w-full h-full object-contain">
                    
                    <div class="absolute inset-0 border-2 border-[#00D4FF] rounded-lg pointer-events-none opacity-50"></div>
                    <div class="absolute left-0 w-full h-1 bg-[#00D4FF] shadow-[0_0_15px_#00D4FF] animate-scan"></div>
                </div>

                <div class="w-full border-t border-gray-200 pt-4 flex justify-between items-center">
                    <span class="text-gray-500 text-xs font-bold">Төлөх дүн:</span>
                    <span class="text-black text-xl font-black">{{ number_format($order->amount) }}₮</span>
                </div>
            </div>

        @else
                <div class="bg-[#1a1a20] rounded-2xl p-6 border border-white/5 space-y-5 mb-8">
                    <div class="flex justify-between items-center pb-4 border-b border-white/5">
                        <span class="text-xs text-gray-500 uppercase font-bold">Хүлээн авах банк</span>
                        <span class="font-bold text-white" style="color: {{ $bankInfo['color'] }}">{{ $bankInfo['name'] }}</span>
                    </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 uppercase font-bold">Дансны дугаар</span>
                    <div class="flex items-center gap-2">
                        <span class="font-mono text-lg font-bold text-white tracking-wider" id="accNum">{{ $bankInfo['account_number'] }}</span>
                        <button onclick="copyToClipboard('accNum')" class="text-gray-500 hover:text-white transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                        </button>
                    </div>
                </div>

                <div class="flex justify-between items-center">
                    <span class="text-xs text-gray-500 uppercase font-bold">Дансны нэр</span>
                    <span class="font-bold text-gray-300">{{ $bankInfo['account_name'] }}</span>
                </div>
                
                <div class="flex justify-between items-center pt-2 border-t border-white/5 border-dashed">
                    <span class="text-xs text-gray-500 uppercase font-bold">Төлөх дүн</span>
                    <span class="font-bold text-xl text-white">{{ number_format($order->amount) }}₮</span>
                </div>
            </div>
        @endif

        <div class="bg-yellow-500/10 border border-yellow-500/20 p-4 rounded-xl flex gap-3 items-start mb-8">
            <svg class="w-5 h-5 text-yellow-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
            <div class="text-[10px] text-gray-400 leading-relaxed">
                <strong class="text-yellow-500 block mb-1">Гүйлгээний утга:</strong>
                Төлбөр хийхдээ гүйлгээний утга дээр <span class="text-white font-mono bg-white/10 px-1 rounded">Order #{{ $order->id }}</span> гэж заавал бичээрэй.
            </div>
        </div>

        <form action="{{ route('payment.confirm', $order->id) }}" method="POST">
            @csrf
            <button type="submit" class="w-full bg-[#00D4FF] hover:bg-white text-black font-black uppercase py-4 rounded-xl shadow-[0_0_20px_rgba(0,212,255,0.4)] transition-all transform hover:scale-[1.02]">
                Төлбөр төлсөн
            </button>
        </form>
        
        <a href="/" class="block text-center text-xs text-gray-600 mt-4 hover:text-white transition">Буцах</a>

    </div>

    <script>
        function copyToClipboard(elementId) {
            var copyText = document.getElementById(elementId).innerText;
            navigator.clipboard.writeText(copyText).then(function() {
                // Toast message could go here
            });
        }
    </script>
</body>
</html>