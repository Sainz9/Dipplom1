
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#0078F2', /* Epic Blue */
                        dark: '#0f0f0f',
                    },
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&display=swap');
        .glass-panel {
            background: rgba(32, 32, 32, 0.7);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>

    <div class="relative min-h-screen flex items-center justify-center overflow-hidden bg-dark font-sans text-white selection:bg-brand selection:text-white">

        <div class="absolute inset-0 z-0">
            <img src="https://i.ytimg.com/vi/6cs-A1rNvEE/maxresdefault.jpg"
                 class="w-full h-full object-cover opacity-40 transform scale-105" alt="Background">
            <div class="absolute inset-0 bg-gradient-to-t from-[#0f0f0f] via-[#0f0f0f]/80 to-transparent"></div>
        </div>

        <div class="relative z-10 w-full max-w-md p-6 mx-4">

            <div class="text-center mb-8">
                <h1 class="text-4xl font-black italic tracking-tighter uppercase drop-shadow-lg">
                    Play<span class="text-brand">Vision</span>
                </h1>
                <p class="text-gray-400 text-sm mt-2 font-medium">Welcome back, Challenger</p>
            </div>

            <div class="glass-panel rounded-2xl p-8 shadow-2xl animate-fade-in-up">

                <div class="grid grid-cols-2 gap-4 mb-6">
                    <button class="flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-lg py-2.5 transition-colors group">
                        <svg class="w-5 h-5 text-gray-400 group-hover:text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12.545 10.239v3.821h5.445c-0.712 2.315-2.647 3.972-5.445 3.972-3.332 0-6.033-2.701-6.033-6.032s2.701-6.032 6.033-6.032c1.498 0 2.866 0.549 3.921 1.453l2.814-2.814c-1.79-1.677-4.184-2.702-6.735-2.702-5.522 0-10 4.478-10 10s4.478 10 10 10c8.396 0 10.249-7.85 9.426-11.748l-9.426 0.082z"/></svg>
                        <span class="text-sm font-bold text-gray-300 group-hover:text-white">Google</span>
                    </button>
                    <button class="flex items-center justify-center gap-2 bg-white/5 hover:bg-white/10 border border-white/5 rounded-lg py-2.5 transition-colors group">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-facebook" viewBox="0 0 16 16">
                            <path d="M16 8.049c0-4.446-3.582-8.05-8-8.05C3.58 0-.002 3.603-.002 8.05c0 4.017 2.926 7.347 6.75 7.951v-5.625h-2.03V8.05H6.75V6.275c0-2.017 1.195-3.131 3.022-3.131.876 0 1.791.157 1.791.157v1.98h-1.009c-.993 0-1.303.621-1.303 1.258v1.51h2.218l-.354 2.326H9.25V16c3.824-.604 6.75-3.934 6.75-7.951"/>
                          </svg>                        <span class="text-sm font-bold text-gray-300 group-hover:text-white">Facebook</span>
                    </button>
                </div>

                <div class="relative flex py-2 items-center mb-6">
                    <div class="flex-grow border-t border-white/10"></div>
                    <span class="flex-shrink-0 mx-4 text-gray-500 text-xs uppercase font-bold tracking-wider">Or sign in with email</span>
                    <div class="flex-grow border-t border-white/10"></div>
                </div>

                <x-auth-session-status class="mb-4 text-green-400 text-sm font-bold text-center" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-5">
                    @csrf

                    <div class="space-y-1">
                        <label for="email" class="text-xs font-bold text-gray-400 uppercase tracking-wide ml-1">
                            Email Address
                        </label>
                        <div class="relative group">
                            <input id="email"
                                   class="block w-full bg-black/40 border border-white/10 rounded-lg text-white px-4 py-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 group-hover:border-white/20"
                                   type="email"
                                   name="email"
                                   :value="old('email')"
                                   required autofocus autocomplete="username"
                                   placeholder="name@example.com" />
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-1 text-red-400 text-xs font-bold ml-1" />
                    </div>

                    <div class="space-y-1">
                        <label for="password" class="text-xs font-bold text-gray-400 uppercase tracking-wide ml-1">
                            Password
                        </label>
                        <div class="relative group">
                            <input id="password"
                                   class="block w-full bg-black/40 border border-white/10 rounded-lg text-white px-4 py-3.5 focus:border-brand focus:ring-1 focus:ring-brand focus:outline-none transition-all placeholder-gray-600 group-hover:border-white/20"
                                   type="password"
                                   name="password"
                                   required autocomplete="current-password"
                                   placeholder="••••••••" />
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-1 text-red-400 text-xs font-bold ml-1" />
                    </div>

                    <div class="flex items-center justify-between pt-2">
                        <label for="remember_me" class="inline-flex items-center cursor-pointer group">
                            <input id="remember_me" type="checkbox"
                                   class="rounded bg-white/10 border-transparent text-brand focus:ring-brand focus:ring-offset-0 w-4 h-4 cursor-pointer"
                                   name="remember">
                            <span class="ms-2 text-sm text-gray-400 group-hover:text-white transition-colors">{{ __('Remember me') }}</span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-brand font-semibold hover:text-blue-400 transition-colors" href="{{ route('password.request') }}">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>

                    <button type="submit"
                            class="w-full mt-4 bg-brand hover:bg-blue-600 text-white font-black uppercase tracking-widest py-4 rounded-lg shadow-lg shadow-brand/20 transition-all transform hover:-translate-y-0.5 active:scale-95 text-sm">
                        {{ __('Log in Now') }}
                    </button>

                    <div class="text-center pt-4">
                        <p class="text-sm text-gray-500">
                            Don't have an account?
                            <a href="{{ route('register') }}" class="text-white font-bold hover:underline">Sign Up Free</a>
                        </p>
                    </div>
                </form>
            </div>

            <p class="text-center text-gray-600 text-xs mt-8">
                &copy; {{ date('Y') }} PlayVision Inc. All rights reserved.
            </p>
        </div>
    </div>

