<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Нууц үг сэргээх | PlayVision</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    
    <script>
        tailwind.config = {
            theme: {
                fontFamily: { sans: ['Inter', 'sans-serif'] },
                extend: {
                    colors: {
                        brand: '#00D4FF',
                        darkBG: '#0a0a0f',
                        darkSurface: '#121218',
                    },
                    animation: {
                        'fade-in-up': 'fadeInUp 0.5s ease-out forwards',
                    },
                    keyframes: {
                        fadeInUp: {
                            '0%': { opacity: '0', transform: 'translateY(10px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background-color: #0a0a0f; color: #e5e5e5; }
    </style>
</head>
<body class="min-h-screen flex items-center justify-center p-4 relative overflow-hidden">

    {{-- Background Effects --}}
    <div class="absolute top-0 left-1/4 w-96 h-96 bg-brand/10 rounded-full blur-[120px] pointer-events-none"></div>
    <div class="absolute bottom-0 right-1/4 w-96 h-96 bg-purple-600/10 rounded-full blur-[120px] pointer-events-none"></div>

    <div class="max-w-md w-full bg-darkSurface border border-white/10 rounded-3xl p-8 relative z-10 shadow-2xl animate-fade-in-up">
        
        {{-- Logo --}}
        <div class="text-center mb-8">
            <a href="/" class="text-3xl font-black tracking-tighter uppercase italic text-white inline-block hover:scale-105 transition-transform">
                Play<span class="text-brand">Vision</span>
            </a>
            <h2 class="text-xl font-bold text-white mt-6">Нууц үг сэргээх</h2>
            <p class="text-gray-400 text-sm mt-2 leading-relaxed">
                Та бүртгэлтэй имэйл хаягаа оруулна уу. Бид танд нууц үг сэргээх холбоосыг илгээх болно.
            </p>
        </div>

        {{-- Success Message --}}
        @if (session('status'))
            <div class="mb-6 bg-green-500/10 border border-green-500/20 text-green-400 px-4 py-3 rounded-xl text-sm flex items-center gap-3">
                <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
            @csrf

            {{-- Email Input --}}
            <div class="space-y-2">
                <label for="email" class="block text-xs font-bold text-gray-400 uppercase tracking-wider ml-1">Имэйл хаяг</label>
                <div class="relative group">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500 group-focus-within:text-brand transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                        </svg>
                    </div>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full bg-black/40 border border-white/10 text-white text-sm rounded-xl block pl-12 p-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600" 
                        placeholder="name@example.com">
                </div>
                @error('email')
                    <p class="text-red-500 text-xs mt-1 ml-1 font-medium">{{ $message }}</p>
                @enderror
            </div>

            {{-- Submit Button --}}
            <button type="submit" class="w-full bg-brand hover:bg-white text-black font-black uppercase tracking-widest py-4 rounded-xl shadow-[0_0_20px_rgba(0,212,255,0.3)] hover:shadow-[0_0_30px_rgba(0,212,255,0.5)] transition-all transform hover:-translate-y-0.5 active:scale-[0.98] text-sm">
                Сэргээх холбоос илгээх
            </button>
        </form>

        {{-- Footer Links --}}
        <div class="mt-8 text-center border-t border-white/5 pt-6">
            <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-white transition-colors flex items-center justify-center gap-2 group">
                <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Нэвтрэх хэсэг рүү буцах
            </a>
        </div>

    </div>

</body>
</html>