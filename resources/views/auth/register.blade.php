<!DOCTYPE html>
<html lang="mn">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - PlayVision</title>

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
                    }
                }
            }
        }
    </script>

    <style>
        .glass-panel {
            background: rgba(24, 24, 27, 0.7);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }
        input:-webkit-autofill {
            -webkit-box-shadow: 0 0 0 30px #1a1a1a inset !important;
            -webkit-text-fill-color: white !important;
        }
    </style>
</head>
<body class="bg-dark text-white font-sans antialiased selection:bg-brand selection:text-black">

    <div class="fixed inset-0 z-0">
        <img src="https://images.alphacoders.com/134/1349380.png" class="w-full h-full object-cover opacity-20" alt="Background">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-[#0f0f0f]/80 to-transparent"></div>
    </div>

    <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 py-12">

        <div class="text-center mb-8">
            <a href="/" class="inline-block group">
                <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase drop-shadow-2xl transition-transform transform group-hover:scale-105">
                    Play<span class="text-brand">Vision</span>
                </h1>
            </a>
            <p class="text-gray-400 text-sm mt-2 font-medium uppercase tracking-widest">Join the community</p>
        </div>

        <div class="glass-panel w-full max-w-lg rounded-[2rem] p-8 sm:p-10 shadow-[0_0_50px_rgba(0,212,255,0.1)]">
            
            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                    <div class="space-y-1">
                        <label for="name" class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Full Name</label>
                        <input id="name" class="block w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 text-sm" type="text" name="name" :value="old('name')" required autofocus placeholder="John Doe" />
                        @error('name') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>

                    <div class="space-y-1">
                        <label for="email" class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Email</label>
                        <input id="email" class="block w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 text-sm" type="email" name="email" :value="old('email')" required placeholder="name@example.com" />
                        @error('email') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password" class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Create Password</label>
                    <input id="password" class="block w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 text-sm" type="password" name="password" required placeholder="••••••••" />
                    @error('password') <p class="text-red-500 text-[10px] mt-1 font-bold">{{ $message }}</p> @enderror
                </div>

                <div class="space-y-1">
                    <label for="password_confirmation" class="text-xs font-bold text-gray-500 uppercase tracking-widest ml-1">Confirm Password</label>
                    <input id="password_confirmation" class="block w-full bg-black/40 border border-white/10 rounded-xl text-white px-4 py-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 text-sm" type="password" name="password_confirmation" required placeholder="••••••••" />
                </div>

                <div class="flex items-start px-1 pt-2">
                    <div class="flex items-center h-5">
                        <input id="terms" type="checkbox" required class="w-4 h-4 rounded bg-white/10 border-white/20 text-brand focus:ring-brand focus:ring-offset-0">
                    </div>
                    <label for="terms" class="ml-3 text-xs text-gray-400 leading-normal">
                        I agree to the <a href="#" class="text-brand hover:underline font-bold">Terms of Service</a> and <a href="#" class="text-brand hover:underline font-bold">Privacy Policy</a>.
                    </label>
                </div>

                <button type="submit" class="w-full bg-brand hover:bg-white hover:text-black text-black font-black uppercase tracking-[0.2em] py-4 rounded-xl shadow-[0_10px_30px_rgba(0,212,255,0.2)] transition-all transform hover:-translate-y-1 active:scale-95 text-sm mt-4">
                    Create Account
                </button>

                <div class="text-center pt-6 border-t border-white/5">
                    <p class="text-xs text-gray-500">
                        Already a member?
                        <a href="{{ route('login') }}" class="text-white font-bold hover:text-brand transition-colors ml-1 uppercase tracking-widest">Sign In</a>
                    </p>
                </div>
            </form>
        </div>

        <div class="mt-8 text-gray-600 text-[10px] uppercase tracking-[0.3em]">
            &copy; {{ date('Y') }} PlayVision Engineering.
        </div>
    </div>
</body>
</html>