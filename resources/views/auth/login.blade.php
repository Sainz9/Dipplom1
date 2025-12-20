    <!DOCTYPE html>
    <html lang="mn">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login - PlayVision</title>

        <script src="https://cdn.tailwindcss.com"></script>
        <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

        <script>
            tailwind.config = {
                theme: {
                    fontFamily: { sans: ['Inter', 'sans-serif'] },
                    extend: {
                        colors: {
                            brand: '#00D4FF', // PlayVision Blue
                            dark: '#0f0f0f',
                        }
                    }
                }
            }
        </script>

        <style>
            .glass-panel {
                background: rgba(24, 24, 27, 0.7); /* Darker for better contrast */
                backdrop-filter: blur(16px);
                -webkit-backdrop-filter: blur(16px);
                border: 1px solid rgba(255, 255, 255, 0.08);
            }
        </style>
    </head>
    <body class="bg-dark text-white font-sans antialiased selection:bg-brand selection:text-black">

        <!-- Background Image -->
        <div class="fixed inset-0 z-0">
            <img src="https://images5.alphacoders.com/139/1397346.jpg" class="w-full h-full object-cover opacity-30" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-[#0f0f0f]/80 to-transparent"></div>
        </div>

        <div class="relative z-10 min-h-screen flex flex-col justify-center items-center px-4 sm:px-6 lg:px-8">

            <!-- Logo -->
            <div class="text-center mb-8">
                <a href="/" class="inline-block group">
                    <h1 class="text-4xl md:text-5xl font-black italic tracking-tighter uppercase drop-shadow-2xl transition-transform transform group-hover:scale-105">
                        Play<span class="text-brand">Vision</span>
                    </h1>
                </a>
                <p class="text-gray-400 text-sm mt-2 font-medium uppercase tracking-widest">Welcome back, Gamer</p>
            </div>

            <!-- Login Card -->
            <div class="glass-panel w-full max-w-md rounded-2xl p-6 sm:p-8 shadow-[0_0_40px_rgba(0,212,255,0.1)]">
                
                <!-- Social Login Buttons -->
                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button class="flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg py-2.5 transition-all hover:border-brand/50 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.545 10.239v3.821h5.445c-0.712 2.315-2.647 3.972-5.445 3.972-3.332 0-6.033-2.701-6.033-6.032s2.701-6.032 6.033-6.032c1.498 0 2.866 0.549 3.921 1.453l2.814-2.814c-1.79-1.677-4.184-2.702-6.735-2.702-5.522 0-10 4.478-10 10s4.478 10 10 10c8.396 0 10.249-7.85 9.426-11.748l-9.426 0.082z"/></svg>
                        <span class="text-sm font-bold text-gray-300 group-hover:text-white">Google</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/10 rounded-lg py-2.5 transition-all hover:border-brand/50 group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                        <span class="text-sm font-bold text-gray-300 group-hover:text-white">Facebook</span>
                    </button>
                </div>

                <div class="relative flex py-2 items-center mb-6">
                    <div class="flex-grow border-t border-white/10"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-500 text-[10px] uppercase font-bold tracking-widest">Or sign in with email</span>
                    <div class="flex-grow border-t border-white/10"></div>
                </div>

                <!-- Session Status -->
                @if (session('status'))
                    <div class="mb-4 text-brand text-sm font-bold text-center">
                        {{ session('status') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <!-- Email Address -->
                    <div class="space-y-1">
                        <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-wide ml-1">Email</label>
                        <div class="relative group">
                            <input id="email" class="block w-full bg-black/40 border border-white/10 rounded-lg text-white px-4 py-3 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 text-sm group-hover:border-white/20" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="name@example.com" />
                        </div>
                        @error('email')
                            <p class="mt-1 text-red-500 text-xs font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="space-y-1">
                        <label for="password" class="text-xs font-bold text-gray-400 uppercase tracking-wide ml-1">Password</label>
                        <div class="relative group">
                            <input id="password" class="block w-full bg-black/40 border border-white/10 rounded-lg text-white px-4 py-3 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 text-sm group-hover:border-white/20" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
                        </div>
                        @error('password')
                            <p class="mt-1 text-red-500 text-xs font-bold ml-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="flex items-center justify-between">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox" class="rounded bg-white/10 border-transparent text-brand focus:ring-brand focus:ring-offset-0 w-4 h-4 cursor-pointer border-white/20" name="remember">
                            <span class="ms-2 text-xs text-gray-400 group-hover:text-white transition-colors">Remember me</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-xs text-brand font-bold hover:text-white transition-colors" href="{{ route('password.request') }}">
                                Forgot password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <button type="submit" class="w-full bg-brand hover:bg-white hover:text-black text-black font-black uppercase tracking-widest py-3.5 rounded-lg shadow-[0_0_15px_rgba(0,212,255,0.4)] transition-all transform hover:-translate-y-0.5 active:scale-95 text-sm">
                        Log In Now
                    </button>

                    <!-- Register Link -->
                    <div class="text-center pt-2">
                        <p class="text-xs text-gray-500">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-white font-bold hover:text-brand hover:underline transition-colors ml-1">Sign Up Free</a>
                        </p>
                    </div>
                </form>
            </div>
            
            <div class="mt-8 text-gray-600 text-xs">
                &copy; {{ date('Y') }} PlayVision. All rights reserved.
            </div>

        </div>
    </body>
    </html>